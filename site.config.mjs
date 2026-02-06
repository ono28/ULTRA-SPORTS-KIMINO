/**
 * site.config.mjs
 * ------------------------------
 * プロジェクト全体の挙動を制御するための設定ファイル
 * タスクランナーの各機能をこのファイルで一元管理します
 *
 * 起動時に自動バリデーションが実行され、不正な値があればエラーを表示します
 */

export const siteConfig = {
  /**
   * ローカル開発サーバー設定
   * @property {boolean} enable - 開発サーバーを起動するか（npm run dev 時のみ有効）
   * @property {number} port - サーバーのポート番号（1-65535）
   * @property {boolean} reload - ファイル変更時の自動リロード機能
   */
  serve: {
    enable: false,
    port: 3000,
    reload: false,
  },

  /**
   * ベースパス設定（公開時のディレクトリ階層）
   * ※必ず「/」で開始・終了すること
   *
   * 例:
   *  '/'                 → http://localhost:3000/
   *  '/hoge/fuga/piyo/'  → http://localhost:3000/hoge/fuga/piyo/
   *
   * この設定により、本番環境と同じURL構造でローカル開発が可能になります
   */
  basePath: '/',

  /**
   * 出力先ディレクトリ
   * ビルド結果が格納されるフォルダ名
   * このディレクトリを本番サーバーにアップロードします
   */
  distPath: 'dist/wp/wp-content/themes/ultra-sports-kimino/',

  /**
   * ソースコードディレクトリ
   * Pug、Sass、JavaScriptなどの開発ファイルを配置
   * ビルド時にコンパイル・処理されて distPath に出力されます
   */
  srcPath: 'src',

  /**
   * 静的ファイル設定
   * @property {boolean} enable - 静的ファイルコピー機能の有効化
   * @property {string} path - 静的ファイルのディレクトリ（画像、JSON、クライアント支給ファイルなど）
   *
   * このディレクトリ内のファイルはコンパイル不要でそのまま distPath にコピーされます
   * 例: public/images/logo.png → dist/images/logo.png
   */
  staticFile: {
    enable: false,
    path: 'public',
  },

  /**
   * アセット出力先設定
   * dist内でのJS/CSSファイルの配置場所を定義
   */
  assets: {
    outDir: 'assets', // assetsディレクトリ名
    js: {
      outDir: 'js', // JSの出力先: dist/[basePath]/assets/js/
      appDir: 'app', // カスタムJSディレクトリ: dist/[basePath]/assets/js/app/
      vendorDir: 'vendor', // 外部ライブラリディレクトリ: dist/[basePath]/assets/js/vendor/
    },
    css: {
      outDir: 'css', // CSSの出力先: dist/[basePath]/assets/css/
    },
  },

  /**
   * Pug設定（HTMLテンプレートエンジン）
   * @property {boolean} enable - Pug機能の有効化
   * @property {string[]} entries - HTMLとして出力するPugファイルのパターン（globパターン）
   * @property {string[]} ignore - 出力対象外のPugファイル（パーシャル、ミックスインなど）
   *
   * 例: src/pug/index.pug → dist/[basePath]/index.html
   *     src/pug/_include/_header.pug → 出力しない（アンダースコア始まり）
   */
  pug: {
    enable: false,
    entries: ['src/pug/**/*.pug'],
    ignore: ['**/_*.pug'],
  },

  /**
   * Sass設定（CSSプリプロセッサ）
   * @property {boolean} compressed - 出力形式（true: 圧縮, false: 整形済み）
   * @property {boolean} sourceMap - ソースマップ生成（開発時はtrue推奨）
   *
   * 本番ビルド時は compressed: true にすることでファイルサイズを削減できます
   */
  sass: {
    compressed: true,
    sourceMap: true,
  },

  /**
   * JavaScript設定
   * @property {boolean} minify - コード圧縮（true: 圧縮, false: 非圧縮）
   *
   * 本番ビルド（npm run build）時のみ有効
   * 開発時（npm run dev）は常に非圧縮で高速ビルドを優先します
   */
  js: {
    minify: true,
  },

  /**
   * 除外ディレクトリ設定
   * srcディレクトリ内でコピー対象外とするフォルダ名
   *
   * これらのフォルダは各タスクで個別に処理されるため、
   * 静的ファイルとしてコピーする必要がありません
   */
  EXCLUDE_DIRS: ['pug', 'sass', 'js'],

  /**
   * 外部ライブラリ設定（オプション）
   * @type {Object}
   *
   * 通常は自動検出されるため設定不要です。
   * srcフォルダ内のimport文から自動的にnpmパッケージを検出し、
   * dist/assets/js/vendor/ にバンドルします。
   *
   * 手動で制御したい場合のみここに定義してください。
   * 例: vendors: { lodash: { entry: 'lodash' } }
   */
  vendors: {},
};
