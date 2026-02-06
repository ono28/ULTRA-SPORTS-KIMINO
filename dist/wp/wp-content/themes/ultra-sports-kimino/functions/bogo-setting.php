<?php
// Bogoの言語スイッチャーの国旗を非表示
add_filter( 'bogo_use_flags','bogo_use_flags_false');
function bogo_use_flags_false(){
  return false;
}

// Bogoの言語スイッチャーの表記を変更
add_filter( 'bogo_language_switcher_links', function ( $links ) {
  for ( $i = 0; $i < count( $links ); $i ++ ) {
    // 日本語
    if ( 'ja' === $links[ $i ]['locale'] ) {
      $links[ $i ]['title'] = 'JP';
      $links[ $i ]['native_name'] = 'JP';
    }
    // 英語
    if ( 'en_US' === $links[ $i ]['locale'] || 'en' === $links[ $i ]['locale'] || 'en_NZ' === $links[ $i ]['locale'] ||'en_CA' === $links[ $i ]['locale'] ||'en_GB' === $links[ $i ]['locale'] ||'en_AU' === $links[ $i ]['locale'] ) {
      $links[ $i ]['title'] = 'EN';
      $links[ $i ]['native_name'] = 'EN';
    }
  }
  return $links;
});

//カスタム投稿をBOGO対応に
function my_localizable_post_types( $localizable ) {
  $custom_post_types = ['news', 'works', 'report']; // 自分のカスタム投稿タイプ名を入れる
  return array_merge($localizable,$custom_post_types);
}
add_filter( 'bogo_localizable_post_types', 'my_localizable_post_types', 10, 1 );