/**
 * logger.mjs
 * ------------------------------
 * デバッグモード用のロガーユーティリティ
 * 環境変数 DEBUG=true でデバッグログを有効化
 */

const DEBUG = process.env.DEBUG === 'true';

/**
 * デバッグログを出力
 * @param {string} tag - ログのタグ（例: 'js', 'sass', 'pug'）
 * @param {...any} args - 出力する内容
 */
export function debug(tag, ...args) {
  if (DEBUG) {
    console.log(`[DEBUG:${tag}]`, ...args);
  }
}

/**
 * 情報ログを出力（常に表示）
 * @param {...any} args - 出力する内容
 */
export function info(...args) {
  console.log(...args);
}

/**
 * エラーログを出力（常に表示）
 * @param {...any} args - 出力する内容
 */
export function error(...args) {
  console.error(...args);
}

/**
 * デバッグモードが有効かどうか
 * @returns {boolean}
 */
export function isDebugMode() {
  return DEBUG;
}
