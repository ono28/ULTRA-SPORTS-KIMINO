import fs from 'node:fs';
import path from 'node:path';
import chokidar from 'chokidar';
import { build as esbuild, transform as estransform } from 'esbuild';
import { siteConfig } from '../../site.config.mjs';
import { debug } from '../utils/logger.mjs';

const cwd = process.cwd();
let vendors = siteConfig.vendors || {}; // 後で自動検出したvendorsで上書き

const base = siteConfig.basePath.replace(/^\/|\/$/g, '');
const baseDir = base ? `${base}/` : '';

const srcRoot = () => path.resolve(cwd, `${siteConfig.srcPath}/js`);
const distJsRoot = () => path.resolve(cwd, `${siteConfig.distPath}/${baseDir}${siteConfig.assets.outDir}/${siteConfig.assets.js.outDir}`);
const distAppRoot = () => path.join(distJsRoot(), `${siteConfig.assets.js.appDir}`);
const distVendorRoot = () => path.join(distJsRoot(), `${siteConfig.assets.js.vendorDir}`);

const ensureDir = (p) => fs.mkdirSync(path.dirname(p), { recursive: true });

const listJsFilesRecursive = (dir) => {
  if (!fs.existsSync(dir)) return [];

  const out = [];
  for (const e of fs.readdirSync(dir, { withFileTypes: true })) {
    const full = path.join(dir, e.name);
    if (e.isDirectory()) {
      out.push(...listJsFilesRecursive(full));
    } else if (e.isFile() && e.name.endsWith('.js')) {
      out.push(full);
    }
  }
  return out;
};

const toDistAppPath = (srcPath) => {
  const rel = path.relative(srcRoot(), srcPath);
  return path.join(distAppRoot(), rel);
};

const copyAppAll = () => {
  fs.mkdirSync(distAppRoot(), { recursive: true });
  const files = listJsFilesRecursive(srcRoot());
  for (const file of files) {
    const dest = toDistAppPath(file);
    ensureDir(dest);
    fs.copyFileSync(file, dest);
  }
};

const removeAppFile = (srcPath) => {
  const dest = toDistAppPath(srcPath);
  fs.rmSync(dest, { force: true });
};

const removeEmptyDirs = (dir) => {
  if (!fs.existsSync(dir)) return;

  for (const entry of fs.readdirSync(dir)) {
    const full = path.join(dir, entry);
    if (fs.statSync(full).isDirectory()) {
      removeEmptyDirs(full);
    }
  }

  // 中身が空なら消す（appRoot 自体は消さない）
  if (dir !== distAppRoot() && fs.readdirSync(dir).length === 0) {
    fs.rmdirSync(dir);
  }
};

const cleanRemovedAppFiles = () => {
  const srcFiles = listJsFilesRecursive(srcRoot()).map((p) => path.relative(srcRoot(), p));

  const distFiles = listJsFilesRecursive(distAppRoot()).map((p) => path.relative(distAppRoot(), p));

  for (const file of distFiles) {
    if (!srcFiles.includes(file)) {
      const target = path.join(distAppRoot(), file);
      fs.rmSync(target, { force: true });
    }
  }

  removeEmptyDirs(distAppRoot());
};

/**
 * src/js 配下のファイルから外部ライブラリのimport文を自動検出
 */
const detectExternalImports = () => {
  const files = listJsFilesRecursive(srcRoot());
  const externals = new Set();

  // import文を抽出する正規表現（コメントアウトは除外）
  // import xxx from 'package'
  // import { xxx } from 'package'
  // import * as xxx from 'package'
  // import 'package/css' (CSS import)
  // ※行頭に // がある場合は除外
  const importWithNameRegex = /^(?![\s]*\/\/)[\s]*import\s+(?:[^\s]+|\{[^}]+\}|\*\s+as\s+[^\s]+)\s+from\s+['"]([^'"]+)['"]/gm;
  const importOnlyRegex = /^(?![\s]*\/\/)[\s]*import\s+['"]([^'"]+)['"]/gm;

  for (const file of files) {
    const code = fs.readFileSync(file, 'utf8');

    // 名前付きimport
    let match;
    while ((match = importWithNameRegex.exec(code)) !== null) {
      const importPath = match[1];
      if (!importPath.startsWith('.') && !importPath.startsWith('/')) {
        externals.add(importPath);
      }
    }

    // CSS等の直接import（import 'package/css'）
    importOnlyRegex.lastIndex = 0;
    while ((match = importOnlyRegex.exec(code)) !== null) {
      const importPath = match[1];
      if (!importPath.startsWith('.') && !importPath.startsWith('/')) {
        externals.add(importPath);
      }
    }
  }

  return Array.from(externals);
};

/**
 * 検出した外部ライブラリからvendor設定を自動生成
 * パッケージごとにグループ化（CSSなども同じファイルに含める）
 */
const generateVendorsConfig = (externalImports) => {
  const packageMap = {};

  // パッケージごとにimportをグループ化
  for (const importPath of externalImports) {
    // パッケージ名を取得: @splidejs/splide/css → @splidejs/splide
    const pkgName = importPath.startsWith('@') ? importPath.split('/').slice(0, 2).join('/') : importPath.split('/')[0];

    if (!packageMap[pkgName]) {
      packageMap[pkgName] = [];
    }
    packageMap[pkgName].push(importPath);
  }

  // vendor設定を生成（パッケージごとに1ファイル）
  const config = {};
  for (const [pkgName, imports] of Object.entries(packageMap)) {
    const safeName = pkgName.replace(/[@\/]/g, '-').replace(/^-/, '');
    const fileName = `${safeName}.min.js`;

    config[safeName] = {
      entry: imports, // 複数のimportをまとめる
      out: fileName,
      originalPaths: imports, // 書き換え用に元のパスを保持
    };
  }

  return config;
};

const buildVendors = async () => {
  const outBase = distVendorRoot();

  // vendorディレクトリをクリーンアップ（古いファイルを削除）
  if (fs.existsSync(outBase)) {
    fs.rmSync(outBase, { recursive: true, force: true });
  }
  fs.mkdirSync(outBase, { recursive: true });

  for (const [name, v] of Object.entries(vendors)) {
    const outfile = path.join(outBase, v.out);
    fs.mkdirSync(path.dirname(outfile), { recursive: true });

    // entryが配列の場合は複数、文字列の場合は単一
    const entryPoints = Array.isArray(v.entry) ? v.entry : [v.entry];

    // CSS用のプラグイン（CSSをJSにインライン化）
    const cssPlugin = {
      name: 'css-text',
      setup(build) {
        build.onLoad({ filter: /\.css$/ }, async (args) => {
          const css = await fs.promises.readFile(args.path, 'utf8');
          // CSSを圧縮して1行に（余分な空白・改行を削除）
          const minifiedCss = css
            .replace(/\s+/g, ' ') // 複数の空白を1つに
            .replace(/\s*([{}:;,])\s*/g, '$1') // セレクタやプロパティ周りの空白削除
            .trim();

          // JSON.stringifyで安全にエスケープ
          return {
            contents: `const style = document.createElement('style');
style.textContent = ${JSON.stringify(minifiedCss)};
document.head.appendChild(style);`,
            loader: 'js',
          };
        });
      },
    };

    // 複数のエントリーポイントを1つのファイルにまとめる場合は仮想エントリーを作成
    if (entryPoints.length > 1) {
      // CSSファイルとJSファイルを分離
      // より正確にCSSを判定（よくあるパターンに対応）
      const cssEntries = [];
      const jsEntries = [];

      for (const entry of entryPoints) {
        // 【パターン1】.css / .scss / .sass / .less で明確に終わる場合
        if (/\.(css|scss|sass|less)$/.test(entry)) {
          cssEntries.push(entry);
          continue;
        }

        // 【パターン2】/css/ や /styles/ や /themes/ を含むパス（慣習的にCSS）
        // 例: @splidejs/splide/css/core, package/styles/theme
        if (entry.includes('/css/') || entry.includes('/styles/') || entry.includes('/themes/') || entry.includes('/dist/css')) {
          cssEntries.push(entry);
          continue;
        }

        // 【パターン3】/css や /style や /styles で終わるパス
        // 例: @splidejs/splide/css, package/style
        if (entry.endsWith('/css') || entry.endsWith('/style') || entry.endsWith('/styles')) {
          cssEntries.push(entry);
          continue;
        }

        // 上記に該当しない場合はJSとして扱う
        jsEntries.push(entry);
      }

      // 仮想エントリーファイルの内容を生成
      // 最初のJSファイルから default export と named exports を転送
      // 残りのJSファイルからは named exports のみを転送
      const virtualEntry = [...(jsEntries.length > 0 ? [`export { default } from '${jsEntries[0]}';`] : []), ...jsEntries.map((e) => `export * from '${e}';`), ...cssEntries.map((e) => `import '${e}';`)].join('\n');

      // stdin からビルド
      await esbuild({
        stdin: {
          contents: virtualEntry,
          resolveDir: process.cwd(),
          loader: 'js',
        },
        outfile,
        bundle: true,
        minify: true,
        format: 'esm',
        target: 'es2020',
        platform: 'browser',
        logLevel: 'silent',
        plugins: [cssPlugin],
      });
    } else {
      // 単一エントリーの場合は通常通り
      await esbuild({
        entryPoints: [entryPoints[0]],
        outfile,
        bundle: true,
        minify: true,
        format: 'esm',
        target: 'es2020',
        platform: 'browser',
        absWorkingDir: process.cwd(),
        logLevel: 'silent',
        plugins: [cssPlugin],
      });
    }
  }
};

const minifyAppJs = async () => {
  if (!siteConfig.js?.minify) return;

  const files = listJsFilesRecursive(distAppRoot());

  for (const file of files) {
    const result = await esbuild({
      entryPoints: [file],
      outfile: file,
      bundle: false,
      minify: siteConfig.js.minify,
      format: 'esm',
      target: 'es2020',
      write: true,
      logLevel: 'silent',
      allowOverwrite: true,
      legalComments: 'none',
    });
  }
};

const deleteCommentAppJs = async () => {
  const files = listJsFilesRecursive(distAppRoot());

  // esbuildでコメントアウトを消す場合（ソースが改変される）
  // const files = listJsFilesRecursive(distAppRoot());

  // for (const file of files) {
  //   const code = fs.readFileSync(file, 'utf8');

  //   const result = await estransform(code, {
  //     loader: 'js',
  //     format: 'esm',
  //     target: 'es2020',

  //     // ★ コメント削除のみ
  //     minifySyntax: true,
  //     minifyWhitespace: false,
  //     minifyIdentifiers: false,
  //   });

  //   fs.writeFileSync(file, result.code);
  // }

  // 正規表現でコメントアウトを消す場合（文字列のよってバグる可能性あり）
  for (const file of files) {
    let code = fs.readFileSync(file, 'utf8');
    code = stripComments(code);
    fs.writeFileSync(file, code);
  }
};

const stripComments = (code) => {
  // 1. ブロックコメント（前後改行ごと削除）
  code = code.replace(/(?:\r?\n)?\/\*[\s\S]*?\*\/(?:\r?\n)?/g, '\n');

  // 2. ラインコメント（`// ` のみ対象）
  code = code.replace(/(^|\s)\/\/\s.+$/gm, '');

  // 3. 空白・インデントだけの行を削除
  code = code.replace(/^[ \t]*\r?\n/gm, '');

  return code;
};

const rewriteImportsInApp = () => {
  const root = distAppRoot();
  const vendorRoot = distVendorRoot();
  const files = listJsFilesRecursive(root);

  for (const filePath of files) {
    let code = fs.readFileSync(filePath, 'utf8');

    // このJSファイルがあるディレクトリ
    const fromDir = path.dirname(filePath);

    // vendor までの相対パス
    const vendorRelBase = path.relative(fromDir, vendorRoot).replace(/\\/g, '/');

    // 各vendorファイルごとに処理
    for (const v of Object.values(vendors)) {
      const importPath = `${vendorRelBase}/${v.out}`.replace(/\\/g, '/');

      // 書き換え対象のパス（originalPathsがあればそれを、なければentryを使用）
      const targetPaths = v.originalPaths || (Array.isArray(v.entry) ? v.entry : [v.entry]);

      // このvendorファイルに対する名前付きimportを収集
      const namedImports = [];
      let hasDirectImport = false;

      for (const targetPath of targetPaths) {
        // エスケープ処理
        const escapedPath = targetPath.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');

        // 名前付きimport: import { xxx } from 'package'
        const reWithName = new RegExp(`import\\s+([^\\n]+?)\\s+from\\s+["']${escapedPath}["']`, 'g');
        let match;
        while ((match = reWithName.exec(code)) !== null) {
          namedImports.push(match[1]);
        }

        // CSS等の直接import: import 'package/css' が存在するかチェック
        const reDirectImport = new RegExp(`import\\s+["']${escapedPath}["']`, 'g');
        if (reDirectImport.test(code)) {
          hasDirectImport = true;
        }
      }

      // すべてのターゲットパスのimportを削除
      for (const targetPath of targetPaths) {
        const escapedPath = targetPath.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');

        // コメントアウトされたimportも含めて削除（// import ... の形式）
        const reCommentedImport = new RegExp(`^\\s*//\\s*import\\s+.*?["']${escapedPath}["'];?\\s*$`, 'gm');
        code = code.replace(reCommentedImport, '');

        // 通常の名前付きimport
        const reWithName = new RegExp(`^\\s*import\\s+[^\\n]+?\\s+from\\s+["']${escapedPath}["'];?\\s*$`, 'gm');
        code = code.replace(reWithName, '');

        // 直接import（CSS等）
        const reDirectImport = new RegExp(`^\\s*import\\s+["']${escapedPath}["'];?\\s*$`, 'gm');
        code = code.replace(reDirectImport, '');
      }

      // 統合したimport文を先頭に追加（名前付きimportがある場合のみ）
      if (namedImports.length > 0) {
        const consolidatedImport = `import ${namedImports.join(', ')} from "${importPath}";\n`;
        code = consolidatedImport + code;
      }
    }

    fs.writeFileSync(filePath, code);
  }
};

export async function build() {
  // console.log('🧩 js build (copy app + vendor + rewrite)');
  debug('js', 'Starting JS build process');

  try {
    cleanRemovedAppFiles();
    copyAppAll();

    // 外部ライブラリを自動検出
    const externalImports = detectExternalImports();
    debug('js', `Detected ${externalImports.length} external imports`);

    // site.config.mjsにvendorsが定義されていない場合は自動生成
    if (!siteConfig.vendors || Object.keys(siteConfig.vendors).length === 0) {
      vendors = generateVendorsConfig(externalImports);
      if (externalImports.length > 0) {
        console.log(`🔍 検出された外部ライブラリ: ${externalImports.join(', ')}`);
      }
    }

    debug('js', 'Building vendors');
    await buildVendors();

    debug('js', 'Rewriting imports in app files');
    rewriteImportsInApp();

    debug('js', `Minifying: ${siteConfig.js?.minify}`);
    await minifyAppJs();

    debug('js', 'Removing comments');
    await deleteCommentAppJs();

    console.log('🎉 js build done');
  } catch (err) {
    console.error('\n🟥 JS BUILD ERROR');
    console.error(err.message);
    if (err.errors) {
      err.errors.forEach((e) => console.error(e.text));
    }
    throw err;
  }

  return { stop: () => {} };
}

export async function watch() {
  const projectRoot = path.resolve(process.cwd(), '..');
  // console.log('👀 js watching');

  // 起動時に必ず1回
  await build();

  const watcher = chokidar.watch(srcRoot(), { ignoreInitial: true });

  watcher.on('all', async (event, file) => {
    try {
      const isJsFile = file.endsWith('.js');

      const rel = path.relative(srcRoot(), file);

      if (event === 'add' || event === 'change') {
        const dest = toDistAppPath(file);
        ensureDir(dest);
        fs.copyFileSync(file, dest);

        // 外部ライブラリを再検出してvendorを再ビルド
        const externalImports = detectExternalImports();
        if (!siteConfig.vendors || Object.keys(siteConfig.vendors).length === 0) {
          vendors = generateVendorsConfig(externalImports);
        }
        await buildVendors();

        rewriteImportsInApp();
        console.log(`📦 js ${event}: ${rel}`);
      }

      // ファイル削除
      if (event === 'unlink') {
        removeAppFile(file);
        removeEmptyDirs(distAppRoot());
        console.log(`❌ js removed: ${rel}`);
      }

      // ディレクトリ削除
      if (event === 'unlinkDir') {
        const destDir = toDistAppPath(file);
        fs.rmSync(destDir, { recursive: true, force: true });
        removeEmptyDirs(distAppRoot());
        console.log(`🧹 js dir removed: ${rel}`);
      }
    } catch (err) {
      console.error('\n🟥 JS WATCH ERROR');
      console.error(err.message);
      console.error('🟥 watch continues\n');
    }
  });

  return {
    stop: async () => watcher.close(),
  };
}
