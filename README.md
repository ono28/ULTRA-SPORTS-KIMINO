# 開発環境 README

この開発環境は、  
**静的HTMLサイト** および **WordPress等のCMSサイト** の開発を目的とした自作タスクランナーです。

プロジェクト全体の挙動は `site.config.mjs` にて管理します。

## 動作要件

- Node.js v20 以上

---

## ディレクトリ構成
```
├── _runner/           # ビルド・watch・serve 実行ロジック　※基本的に触らない
│   ├── tasks/         # 各タスクの実装（js, sass, pug, static, serve）
│   ├── utils/         # ユーティリティ（logger等）
│   └── index.mjs      # エントリーポイント
├── dist/              # 出力先（公開物）
├── public/            # クライアント指定の静的ファイル等
├── src/               # 開発用ファイル（pug / sass / js）
│   ├── pug/
│   ├── sass/
│   └── js/
├── package.json
└── site.config.mjs    # プロジェクト全体設定
```

---

## セットアップ ＆ 起動

```bash
npm install

# 開発モード（watch + ローカルサーバー + 自動リロード）
npm run dev

# 開発モード（デバッグログ付き）
npm run dev:debug

#  詳細ガイド

### 📁 public について

- public に配置したファイルはディレクトリ構造ごと dist に出力されます
- 画像、動画、JSON、クライアント支給の共通ファイルなどを配置
- コンパイル不要な静的ファイルの管理に使用します

### 🌐 CMS（WordPress）開発について

- DB・PHP 環境は MAMP（または同等のローカルサーバー）を使用
- 本リポジトリは 静的ビルド環境の管理を目的としています
- CMS 開発時は `site.config.mjs` で `serve.enable: false` に設定
- dist 内に CMS ファイル一式を配置
- 画像ファイルなども public を使わずに直接 dist 内に配置してください

### 💻 JavaScript の設計方針

#### 基本方針
- **ES Modules（ESM）** 前提
- **自作コード** は非バンドル（`dist/assets/js/app/` に出力）
- **npm パッケージ** は自動検出して vendor として個別ビルド（`dist/assets/js/vendor/` に出力）

#### 外部ライブラリの自動検出
```javascript
// src/js/main.js
import { Splide } from '@splidejs/splide';
import '@splidejs/splide/css/core';
import SimpleBar from 'simplebar';
import 'simplebar/dist/simplebar.css';
```

上記のように記述するだけで：
1. npm パッケージを自動検出
2. vendor ディレクトリにバンドル
3. CSS は JS に埋め込み（実行時に `<style>` タグとして挿入）
4. import 文を自動的に相対パスに書き換え

`site.config.mjs` での vendor 定義は不要です。

#### コード処理
- **dist/assets/js/app/** 内: コメントアウトが削除される（minifyは設定次第）
- **dist/assets/js/vendor/** 内: 常に圧縮される

### 🐛 トラブルシューティング

#### ビルドエラーが発生する
```bash
# デバッグモードで詳細ログを確認
npm run build:debug
```

#### 古いファイルが残っている
```bash
# dist をクリーンアップしてから再ビルド
npm run clean
npm run build
```

#### 設定が反映されない
- `site.config.mjs` の設定値を確認
- 起動時のバリデーションエラーメッセージを確認
- basePath は `/` で始まり `/` で終わる必要があります

#### CSS が表示されない（外部ライブラリ）
- 外部ライブラリの CSS は自動的に JS に埋め込まれます
- import 文がコメントアウトされていないか確認
- デバッグモードで検出状況を確認: `npm run dev:debug`

---

## 開発フロー例

### 新規プロジェクト開始
```bash
# 設定ファイルを編集（basePath等）
vim site.config.mjs

# 依存関係をインストール
npm install

# 開発開始
npm run dev
```

### 本番ビルド
```bash
# site.config.mjs で圧縮設定を確認
# sass.compressed: true
# js.minify: true

# クリーンビルド
npm run clean
npm run build

# dist/ ディレクトリをサーバーにアップロード
```
ビルド時は全タスク（static, pug, sass, js）を並列実行し、高速化を実現。

### 🔍 デバッグモード
`npm run dev:debug` または `npm run build:debug` で詳細ログを出力。  
各処理ステップ、検出ファイル数、設定値などを確認できます。

### 🚨 エラーハンドリング
ビルドエラー発生時は適切なメッセージを表示し、CI/CD環境で正しく検知可能。  
開発モード（watch）ではエラーが出てもタスクは継続します。

### 🧹 クリーンタスク
`npm run clean` で dist ディレクトリを完全削除。  
ビルドキャッシュをクリアしたい場合に便利です。

---

## site.config.mjs について

プロジェクトの全設定を集中管理するファイルです。  
各設定項目には詳細なコメントとJSDoc型定義が付いています。

### 主な設定項目

- **basePath**: 公開時のディレクトリ階層（例: `/hoge/fuga/piyo/`）
- **serve**: ローカルサーバー設定（ポート、リロード機能）
- **pug**: Pug→HTML変換設定
- **sass**: Sass→CSS変換設定（圧縮、ソースマップ）
- **js**: JavaScript設定（圧縮、外部ライブラリ自動検出）
- **staticFile**: 静的ファイルコピー設定

設定を変更した場合、起動時に自動バリデーションが実行されます。

---

### publicについて

- public に配置したファイルはディレクトリ構造ごと dist に出力されます
- 画像や動画ファイルはここで管理します

### CMS（WordPress）開発について

- DB・PHP 環境は MAMP（または同等のローカルサーバー）を使用
- 本リポジトリは 静的ビルド環境の管理を目的としています
- CMS 開発時はローカルサーバーは起動しません。MAMP 等を使用してください
- CMS 開発時は dist 内に CMS ファイル一式を入れて、画像ファイルなども public を使わずに直接配置してください

### JavaScript の設計方針

- ES Modules（ESM）前提
- 自作コードは 非バンドル
- npm パッケージは vendor として個別ビルド
- dist 内の js はコメントアウトが削除される
- dist/vendor 内の js は常に圧縮される

---

#### コピーライト

@mattune