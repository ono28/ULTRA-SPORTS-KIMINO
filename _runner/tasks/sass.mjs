import { spawn } from 'node:child_process';
import path from 'node:path';
import fs from 'node:fs';
import { promisify } from 'node:util';
import { exec } from 'node:child_process';
import { siteConfig } from '../../site.config.mjs';
import { debug } from '../utils/logger.mjs';

const execAsync = promisify(exec);

const cwd = process.cwd();
const base = siteConfig.basePath.replace(/^\/|\/$/g, '');
const baseDir = base ? `${base}/` : '';

const srcDir = path.resolve(cwd, `${siteConfig.srcPath}/sass`);
const outDir = path.resolve(cwd, `${siteConfig.distPath}/${baseDir}${siteConfig.assets.outDir}/${siteConfig.assets.css.outDir}`);
const style = siteConfig.sass?.compressed ? 'compressed' : 'expanded';

/* ------------------
   utils
------------------ */
const removeSourceMaps = () => {
  if (!fs.existsSync(outDir)) return;

  for (const file of fs.readdirSync(outDir)) {
    if (file.endsWith('.map')) {
      fs.unlinkSync(path.join(outDir, file));
    }
  }
};

const runPostCSS = async () => {
  debug('sass', 'Running PostCSS (Autoprefixer)...');
  try {
    await execAsync(`npx postcss ${outDir}/**/*.css --replace --no-map`);
  } catch (error) {
    console.error('PostCSS failed:', error.message);
    throw error;
  }
};

/* ------------------
   build
------------------ */
export async function build() {
  debug('sass', `Compiling ${srcDir} -> ${outDir}`);
  debug('sass', `Style: ${style}`);

  const child = spawn('sass', [`${srcDir}:${outDir}`, `--style=${style}`, '--load-path=node_modules', '--no-source-map'], { stdio: 'inherit' });

  await new Promise((resolve, reject) => {
    child.on('exit', (code) => {
      if (code !== 0) {
        reject(new Error(`sass build failed with exit code ${code}`));
      } else {
        resolve();
      }
    });
    child.on('error', (err) => {
      reject(new Error(`sass process error: ${err.message}`));
    });
  });

  // 念のため既存 map を掃除
  removeSourceMaps();

  // PostCSS で Autoprefixer を実行
  await runPostCSS();

  console.log('🎨 sass build done');
}

/* ------------------
   watch
------------------ */
export async function watch({ onChange } = {}) {
  const child = spawn('sass', [`${srcDir}:${outDir}`, '--load-path=node_modules', `--style=${style}`, '--watch'], { stdio: 'inherit' });

  child.stdout?.on('data', () => {
    onChange?.('sass');
  });
}
