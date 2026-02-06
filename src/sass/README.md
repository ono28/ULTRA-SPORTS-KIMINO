# SASS README

## ディレクトリ構成

ページが増える場合は適宜追加。  
レスポンシブ対応として、各デバイスサイズ別にファイルを分割しています。

```
src/sass/
├── core/                              # CSS に出力しない設定ファイル（@use でインポート）
│   ├── _config_max.scss               # 最大幅サイズ設定（固定px化）
│   ├── _config_pc.scss                # PC サイズ設定
│   ├── _config_sp_horizontal.scss     # スマホ横向きサイズ設定
│   ├── _config_sp.scss                # スマホサイズ設定
│   ├── _config_tb.scss                # タブレットサイズ設定
│   ├── _easing.scss                   # イージング関数定義
│   ├── _function.scss                 # 汎用関数（px, vw 等）
│   ├── _index.scss                    # core モジュールのエントリーポイント
│   ├── _mixin.scss                    # メディアクエリ等の mixin
│   └── _variable.scss                 # サイト用の変数等を定義するファイル
│
├── project/
│   ├── base/                          # リセット CSS・基礎スタイル
│   │   ├── _device.scss               # デバイス別表示制御（data-pc, data-tb, data-sp）
│   │   ├── _index.scss                # base モジュールのエントリーポイント
│   │   ├── _keyframe.scss             # アニメーション @keyframes 定義
│   │   ├── _reset.scss                # CSS リセット
│   │   └── _root.scss                 # :root 変数定義
│   │
│   ├── module/                        # 汎用コンポーネント・パーツの CSS
│   │   ├── _index.scss                # module モジュールのエントリーポイント
│   │   ├── _modal.scss                # モーダルウィンドウ
│   │   ├── _movie-player.scss         # 動画プレイヤー（汎用）
│   │   ├── _scroll-class.scss         # スクロール連動クラス制御
│   │   └── _yt-player.scss            # YouTube プレイヤー
│   │
│   └── view/                          # サイトのレイアウト・ページ別スタイル
│       ├── common/                    # 全ページ共通レイアウト
│       │   ├── _index.scss            # 全デバイス共通スタイル
│       │   ├── _max.scss              # 最大幅時のスタイル
│       │   ├── _pc.scss               # PC 用スタイル
│       │   ├── _sp_horizontal.scss    # スマホ横向き用スタイル
│       │   ├── _sp.scss               # スマホ用スタイル
│       │   └── _tb.scss               # タブレット用スタイル
│       │
│       ├── parts/                     # 共通パーツコンポーネント
│       │   ├── _index.scss
│       │   ├── _max.scss
│       │   ├── _pc.scss
│       │   ├── _sp_horizontal.scss
│       │   ├── _sp.scss
│       │   └── _tb.scss
│       │
│       └── top/                       # トップページ専用スタイル
│           ├── _index.scss
│           ├── _max.scss
│           ├── _pc.scss
│           ├── _sp_horizontal.scss
│           ├── _sp.scss
│           └── _tb.scss
│
├── main.scss                          # メインスタイルシートのエントリーポイント
└── top.scss                           # トップページ専用スタイルシートのエントリーポイント
```

---

## 出力ファイル

Sass ファイルのうち、`_`（アンダースコア）で始まらないファイルが CSS として出力されます。

- `main.scss` → `dist/assets/css/main.css`（共通スタイル）
- `top.scss` → `dist/assets/css/top.css`（トップページ専用）

新しいページを追加する場合は、ルートに `pagename.scss` を作成し、  
`project/view/pagename/` ディレクトリを作成してデバイス別ファイルを配置します。

---

## デバイス別表示制御

`project/base/_device.scss` でデバイス別の表示制御を定義しています。

HTML に以下の data 属性を付与することで、特定デバイスでのみ表示可能：

```html
<!-- PC のみ表示 -->
<div data-pc>PC専用コンテンツ</div>

<!-- タブレットのみ表示 -->
<div data-tb>タブレット専用コンテンツ</div>

<!-- スマホのみ表示 -->
<div data-sp>スマホ専用コンテンツ</div>
```

---

## ユーティリティ関数

`core/_function.scss` で定義されている便利な関数：

### px() 関数
```scss
// px 単位
margin: px(10);
// → margin: 10px;

// rem 単位
margin: px(10, rem);
// → margin: 1rem;
```

### vw() 関数
```scss
// vw 単位（レスポンシブ対応）
margin: vw(10);
// → margin: 0.625vw; （画面幅 1600px 基準の場合）
```

**注意**: vw の計算値は `core/_config_*.scss` で定義している画面サイズによって異なります。  
各デバイスで適切なサイズになるよう、デバイス別ファイルで調整してください。

---

## デバイス別ファイル構成

各 view ディレクトリ内では、デバイスサイズごとにファイルを分割しています：

| ファイル名              | 説明 | 適用される環境 |
|------------------------|------|---------------|
| `_index.scss`          | 全デバイス共通スタイル | すべての画面サイズ |
| `_max.scss`            | 最大サイズ固定（1600px以上） | PC 大画面 |
| `_pc.scss`             | PC サイズ | 760px〜1599px |
| `_tb.scss`             | タブレットサイズ | タブレット端末 |
| `_sp.scss`             | スマホサイズ（縦） | 〜759px（縦向き） |
| `_sp_horizontal.scss`  | スマホサイズ（横） | 〜759px（横向き） |

各ファイルの `vw()` 関数は、`core/_config_*.scss` で定義された画面幅基準で計算されます。

---

## デバイスサイズごとの例外処理

特定のデバイスサイズで異なる計算式を使いたい場合、  
名前空間付きで config をインポートして使用します。

**例**: `project/view/top/_pc.scss` 内で他デバイスの vw を使用

```scss
// 名前空間付きインポート
@use '../../../core/config_sp' as sp;
@use '../../../core/config_max' as max;

body {
  font-size: vw(16);  // 通常は PC 基準の vw

  @include sp {
    font-size: sp.vw(16);  // スマホ基準の vw に変更
  }

  @include max {
    font-size: max.vw(16);  // 最大サイズ基準（固定px）に変更
  }
}
```

**コンパイル結果**:
```css
body {
  font-size: calc(16 / 1600 * 100dvw);
}
@media screen and (max-width: 759.98px) and (orientation: portrait) {
  body {
    font-size: calc(16 / 750 * 100dvw);
  }
}
@media screen and (min-width: 1600px) {
  body {
    font-size: 16px;
  }
}
```

この方法は一箇所だけの修正に有効ですが、多用すると保守性が低下するため注意してください。

---

#### コピーライト

@mattune
