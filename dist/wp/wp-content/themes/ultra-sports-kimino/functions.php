<?php
  // ------------------------------------------------------------------
  //
  // よく使う設定は別ファイルから適宜読み込み
  //
  // ------------------------------------------------------------------

  // 汎用的な設定
  require get_template_directory() . '/functions/basic-setting.php';


  // PREV NEXTボタンの調整
  require get_template_directory() . '/functions/pager-setting.php';


  // 投稿のスラッグやパーマリンクを変更
  require get_template_directory() . '/functions/change-post-setting.php';


  // 再利用ブロックを管理画面メニューに追加
  // require get_template_directory() . '/functions/recycle-block-setting.php';


  // bogoの設定
  // require get_template_directory() . '/functions/bogo-setting.php';


  // サイドメニューを非表示
  require get_template_directory() . '/functions/hide-menu-setting.php';


  // ACF用の追加設定
  require get_template_directory() . '/functions/acf-setting.php';


  // ------------------------------------------------------------------
  //
  // サイト固有の設定は以下に追記
  //
  // ------------------------------------------------------------------
  // 特定の固定ページのブロックエディタを無効化
  add_filter('use_block_editor_for_post',function($use_block_editor,$post){
    if($post->post_type==='page'){
      if(in_array($post->post_name,['faq'])){
        remove_post_type_support('page','editor');
        return false;
      }
    }
    return $use_block_editor;
  },10,2);