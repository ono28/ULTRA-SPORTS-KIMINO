<?php
  // サイドメニューを非表示
  function remove_menus() {
    // remove_menu_page( 'index.php' ); // ダッシュボード
    // remove_menu_page( 'edit.php' ); // 投稿
    // remove_menu_page( 'upload.php' ); // メディア
    // remove_menu_page( 'edit.php?post_type=page' ); // 固定ページ
    remove_menu_page( 'edit-comments.php' ); // コメント
    // remove_menu_page( 'themes.php' ); // 外観
    // remove_menu_page( 'plugins.php' ); // プラグイン
    // remove_menu_page( 'users.php' ); // ユーザー
    // remove_menu_page( 'tools.php' ); // ツール
    // remove_menu_page( 'options-general.php' ); // 設定
  }
  add_action( 'admin_menu', 'remove_menus', 999 );