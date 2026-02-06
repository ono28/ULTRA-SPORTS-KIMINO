import pug from 'pug';
import chokidar from 'chokidar';
import path from 'node:path';
import fs from 'node:fs';
import fg from 'fast-glob';
import prettier from 'prettier';
import { siteConfig } from '../../site.config.mjs';
import { debug } from '../utils/logger.mjs';

const cwd = process.cwd();
const pugRoot = path.resolve(cwd, `${siteConfig.srcPath}/pug`);

const base = siteConfig.basePath.replace(/^\/|\/$/g, '');
const baseDir = base ? `${base}/` : '';
const distRoot = path.resolve(cwd, `${siteConfig.distPath}/${baseDir}`);

const isPartial = (file) => path.basename(file).startsWith('_');

const getOutFile = (file) => {
  const rel = path.relative(pugRoot, file).replace(/\.pug$/, '.html');
  return path.join(distRoot, rel);
};

const formatHtml = (html) => {
  return prettier.format(html, {
    parser: 'html',
    printWidth: 9999,
    tabWidth: 2,
    useTabs: false,
    htmlWhitespaceSensitivity: 'ignore',
  });
};

// 閉じスラッシュ削除
const removeSelfClosingSlashes = (html) => {
  const tags = ['img', 'meta', 'source', 'link', 'br', 'hr', 'input'];

  for (const tag of tags) {
    const re = new RegExp(`<${tag}([^>]*?)\\s*\\/?>`, 'gi');
    html = html.replace(re, `<${tag}$1>`);
  }

  return html;
};

/* ------------------
   build one file
------------------ */
const buildFile = async (file) => {
  if (isPartial(file)) return;

  const outFile = getOutFile(file);
  fs.mkdirSync(path.dirname(outFile), { recursive: true });

  try {
    const html = pug.renderFile(file, {
      basedir: pugRoot,
      pretty: true,
    });

    let formatted = html;

    formatted = await formatHtml(formatted);
    formatted = await removeSelfClosingSlashes(formatted);

    fs.writeFileSync(outFile, formatted);
  } catch (err) {
    console.error('\n🟥 PUG ERROR');
    console.error(`📄 ${path.relative(pugRoot, file)}`);
    console.error(err.message.split('\n').slice(0, 6).join('\n'));
    console.error('🟥 build skipped, watch continues\n');
  }
};

/* ------------------
   clean unused html
------------------ */
const cleanUnusedHtml = (entryFiles) => {
  const expected = new Set(entryFiles.map(getOutFile));

  const existing = fg.sync('**/*.html', {
    cwd: distRoot,
    absolute: true,
  });

  for (const file of existing) {
    if (!expected.has(file)) {
      fs.rmSync(file, { force: true });
      console.log(`🧹 remove unused html: ${path.relative(distRoot, file)}`);
    }
  }

  removeEmptyDirs(distRoot);
};

const removeEmptyDirs = (dir) => {
  if (!fs.existsSync(dir)) return;

  for (const entry of fs.readdirSync(dir)) {
    const full = path.join(dir, entry);
    if (fs.statSync(full).isDirectory()) {
      removeEmptyDirs(full);
    }
  }

  // 中身が空なら削除
  if (fs.readdirSync(dir).length === 0) {
    fs.rmdirSync(dir);
  }
};

/* ------------------
   build
------------------ */
export async function build() {
  debug('pug', 'Starting pug build');

  const entryFiles = fg
    .sync(siteConfig.pug.entries, {
      ignore: siteConfig.pug.ignore,
    })
    .filter((file) => !isPartial(file));

  debug('pug', `Found ${entryFiles.length} entry files`);

  if (entryFiles.length === 0) {
    console.log('⚠️ pug entry not found. skip.');
    return;
  }

  await Promise.all(entryFiles.map((file) => buildFile(file)));
  cleanUnusedHtml(entryFiles);

  console.log('📄 pug build done');
}

/* ------------------
   watch
------------------ */
export async function watch() {
  await build();

  const watcher = chokidar.watch(pugRoot, {
    ignoreInitial: true,
  });

  watcher.on('all', async (event, file) => {
    if (!file.endsWith('.pug')) return;

    const relative = path.relative(pugRoot, file);

    // partial 変更 → 全 rebuild + clean
    if (isPartial(file)) {
      console.log(`♻️  partial changed: ${relative}`);
      await build();
      return;
    }

    if (event === 'unlink') {
      const outFile = getOutFile(file);
      fs.rmSync(outFile, { force: true });
      console.log(`❌ pug removed: ${relative}`);
      return;
    }

    await buildFile(file);
    console.log(`✏️ pug ${event}: ${relative}`);
  });
}
