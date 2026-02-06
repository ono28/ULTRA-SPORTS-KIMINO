import fs from 'node:fs';
import path from 'node:path';
import chokidar from 'chokidar';
import { siteConfig } from '../../site.config.mjs';
import { debug } from '../utils/logger.mjs';

const cwd = process.cwd();

const srcRoot = path.resolve(cwd, siteConfig.staticFile.path);
const distRoot = path.resolve(cwd, `${siteConfig.distPath}`);

const exists = (p) => fs.existsSync(p);

/* ------------------
   utils
------------------ */
const listFilesRecursive = (dir) => {
  if (!exists(dir)) return [];
  const results = [];

  for (const entry of fs.readdirSync(dir, { withFileTypes: true })) {
    const full = path.join(dir, entry.name);
    if (entry.isDirectory()) {
      results.push(...listFilesRecursive(full));
    } else {
      results.push(full);
    }
  }
  return results;
};

const toDistPath = (srcPath) => {
  const rel = path.relative(srcRoot, srcPath);
  return path.join(distRoot, rel);
};

const copyFile = (src) => {
  const dest = toDistPath(src);
  fs.mkdirSync(path.dirname(dest), { recursive: true });
  fs.copyFileSync(src, dest);
};

const removeEmptyDirs = (dir) => {
  if (!fs.existsSync(dir)) return;

  const entries = fs.readdirSync(dir);
  if (entries.length === 0) {
    fs.rmdirSync(dir);
    return;
  }

  for (const entry of entries) {
    const full = path.join(dir, entry);
    if (fs.statSync(full).isDirectory()) {
      removeEmptyDirs(full);
    }
  }

  // 子を消した結果、自分が空になったら消す
  if (fs.readdirSync(dir).length === 0) {
    fs.rmdirSync(dir);
  }
};

const removeDistPath = (srcPath) => {
  const rel = path.relative(srcRoot, srcPath);
  const dest = path.join(distRoot, rel);

  if (!exists(dest)) return;

  const stat = fs.statSync(dest);

  if (stat.isDirectory()) {
    fs.rmSync(dest, { recursive: true, force: true });
  } else {
    fs.rmSync(dest, { force: true });
  }
};

/* ------------------
   build（完全同期）
------------------ */
export async function build() {
  debug('static', `Syncing ${srcRoot} -> ${distRoot}`);

  if (!exists(srcRoot)) {
    console.log('📁 public not found, skip');
    return;
  }

  const srcFiles = listFilesRecursive(srcRoot);
  debug('static', `Found ${srcFiles.length} files to copy`);

  // 1. public → dist コピー
  srcFiles.forEach(copyFile);

  // 2. dist 側の不要ファイル削除（public 基準）
  const distFiles = listFilesRecursive(distRoot);
  for (const distFile of distFiles) {
    const rel = path.relative(distRoot, distFile);
    const srcFile = path.join(srcRoot, rel);
    if (!exists(srcFile)) {
      fs.rmSync(distFile, { force: true });
    }
  }

  // 3. 空ディレクトリ削除
  removeEmptyDirs(distRoot);

  console.log('📦 static build synced');
}

/* ------------------
   watch（差分）
------------------ */
export async function watch() {
  if (!exists(srcRoot)) {
    console.log('📁 public not found, skip');
    return;
  }

  await build();

  chokidar.watch(srcRoot, { ignoreInitial: true }).on('all', (event, file) => {
    if (event === 'add' || event === 'change') {
      copyFile(file);
      console.log(`📦 static ${event}: ${path.relative(srcRoot, file)}`);
    }

    if (event === 'unlink' || event === 'unlinkDir') {
      removeDistPath(file);
      console.log(`❌ static remove: ${path.relative(srcRoot, file)}`);
    }
  });
}
