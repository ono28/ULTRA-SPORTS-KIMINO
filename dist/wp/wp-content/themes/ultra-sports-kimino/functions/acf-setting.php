<?php
  // ACF Wysiwygにフォントサイズのセレクトボックスを追加
  function customize_acf_wysiwyg_toolbar( $toolbars ) {
    if (($key = array_search('fontsizeselect' , $toolbars['Full'][2])) !== true) {
      array_push($toolbars['Full'][2], 'fontsizeselect');
    }
    return $toolbars;
  }
  add_filter('acf/fields/wysiwyg/toolbars' , 'customize_acf_wysiwyg_toolbar');

  // フォントサイズの単位をpxに変更
  if ( ! function_exists( 'wpex_mce_font_sizes' ) ) {
    function wpex_mce_font_sizes( $initArray ){
      $initArray['fontsize_formats'] = "10px 12px 13px 14px 16px 18px 20px 22px 24px 26px 28px 30px 32px 34px 36px 38px 40px 42px 44px 46px 48px 50px";
      return $initArray;
    }
  }
  add_filter( 'tiny_mce_before_init', 'wpex_mce_font_sizes' );