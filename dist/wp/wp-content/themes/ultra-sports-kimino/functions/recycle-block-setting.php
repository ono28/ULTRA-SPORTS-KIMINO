<?php
  // 再利用ブロックを管理画面メニューに追加
  function add_my_admin_menu() {
    add_menu_page( '再利用ブロック', '再利用ブロック', 'manage_options', 'edit.php?post_type=wp_block', '', 'dashicons-block-default', 6 );
  }
  add_action( 'admin_menu', 'add_my_admin_menu' );