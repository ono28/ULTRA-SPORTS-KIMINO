import fs from 'node:fs';
import path from 'node:path';
import { siteConfig } from '../site.config.mjs';
import { startServe, triggerReload } from './tasks/serve.mjs';
import * as staticTask from './tasks/static.mjs';
import * as pug from './tasks/pug.mjs';
import * as sass from './tasks/sass.mjs';
import * as js from './tasks/js.mjs';
import { debug, isDebugMode } from './utils/logger.mjs';

// デバッグモードの表示
if (isDebugMode()) {
  console.log('\n🔍 DEBUG MODE ENABLED\n');
}

/**
 * 設定ファイルのバリデーション
 * 起動時に不正な設定値をチェックし、早期にエラーを検出
 */
function validateConfig() {
  debug('config', 'Starting configuration validation');
  const errors = [];

  // basePath のチェック
  if (typeof siteConfig.basePath !== 'string') {
    errors.push('basePath must be a string');
  } else if (!siteConfig.basePath.startsWith('/')) {
    errors.push('basePath must start with "/"');
  } else if (!siteConfig.basePath.endsWith('/')) {
    errors.push('basePath must end with "/"');
  }

  // serve のチェック
  if (siteConfig.serve) {
    if (typeof siteConfig.serve.enable !== 'boolean') {
      errors.push('serve.enable must be a boolean');
    }
    if (siteConfig.serve.port && !Number.isInteger(siteConfig.serve.port)) {
      errors.push('serve.port must be an integer');
    }
    if (siteConfig.serve.port && (siteConfig.serve.port < 1 || siteConfig.serve.port > 65535)) {
      errors.push('serve.port must be between 1 and 65535');
    }
    if (typeof siteConfig.serve.reload !== 'boolean') {
      errors.push('serve.reload must be a boolean');
    }
  }

  // distPath のチェック
  if (typeof siteConfig.distPath !== 'string' || siteConfig.distPath.length === 0) {
    errors.push('distPath must be a non-empty string');
  }

  // srcPath のチェック
  if (typeof siteConfig.srcPath !== 'string' || siteConfig.srcPath.length === 0) {
    errors.push('srcPath must be a non-empty string');
  }

  // staticFile のチェック
  if (siteConfig.staticFile) {
    if (typeof siteConfig.staticFile.enable !== 'boolean') {
      errors.push('staticFile.enable must be a boolean');
    }
    if (typeof siteConfig.staticFile.path !== 'string' || siteConfig.staticFile.path.length === 0) {
      errors.push('staticFile.path must be a non-empty string');
    }
  }

  // pug のチェック
  if (siteConfig.pug) {
    if (typeof siteConfig.pug.enable !== 'boolean') {
      errors.push('pug.enable must be a boolean');
    }
    if (!Array.isArray(siteConfig.pug.entries)) {
      errors.push('pug.entries must be an array');
    }
    if (!Array.isArray(siteConfig.pug.ignore)) {
      errors.push('pug.ignore must be an array');
    }
  }

  // sass のチェック
  if (siteConfig.sass) {
    if (typeof siteConfig.sass.compressed !== 'boolean') {
      errors.push('sass.compressed must be a boolean');
    }
  }

  // js のチェック
  if (siteConfig.js) {
    if (typeof siteConfig.js.minify !== 'boolean') {
      errors.push('js.minify must be a boolean');
    }
  }

  // エラーがある場合は表示して終了
  if (errors.length > 0) {
    console.error('\n❌ Configuration validation failed:\n');
    errors.forEach((error) => console.error(`  - ${error}`));
    console.error('');
    process.exit(1);
  }

  debug('config', 'Configuration validation passed');
}

// 起動時にバリデーション実行
validateConfig();

const mode = process.argv[2];

/**
 * クリーンモード
 * distディレクトリを完全削除します
 */
if (mode === 'clean') {
  debug('clean', `Target directory: ${siteConfig.distPath}`);
  const distPath = path.resolve(process.cwd(), siteConfig.distPath);

  if (fs.existsSync(distPath)) {
    debug('clean', `Removing directory: ${distPath}`);
    fs.rmSync(distPath, { recursive: true, force: true });
    console.log(`🧹 cleaned: ${siteConfig.distPath}/`);
  } else {
    console.log(`⚠️  ${siteConfig.distPath}/ does not exist`);
  }

  process.exit(0);
}

/**
 * 開発モード
 * ファイル監視とライブリロードを有効化
 */
if (mode === 'dev') {
  debug('dev', 'Starting development mode with file watching');
  debug('dev', `Serve: ${siteConfig.serve.enable}`);
  debug('dev', `Static files: ${siteConfig.staticFile.enable}`);
  debug('dev', `Pug: ${siteConfig.pug.enable}`);

  await Promise.all(
    [
      siteConfig.serve.enable && startServe(),
      siteConfig.staticFile.enable && staticTask.watch(),
      siteConfig.pug.enable && pug.watch(),
      js.watch(),
      sass.watch({
        onChange: siteConfig.serve.enable ? triggerReload : null,
      }),
    ].filter(Boolean)
  );
}

/**
 * ビルドモード
 * 本番用の最適化されたファイルを生成
 */
if (mode === 'build') {
  debug('build', 'Starting production build');
  debug('build', `Output directory: ${siteConfig.distPath}`);

  try {
    await Promise.all([siteConfig.staticFile.enable && staticTask.build(), siteConfig.pug.enable && pug.build(), sass.build(), js.build()].filter(Boolean));

    console.log('✅ build completed successfully');
  } catch (err) {
    console.error('\n❌ build failed:');
    console.error(err.message);
    process.exit(1);
  }
}
