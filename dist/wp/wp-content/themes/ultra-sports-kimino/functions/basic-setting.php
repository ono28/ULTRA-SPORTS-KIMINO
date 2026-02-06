<?php
  add_theme_support( 'automatic-feed-links' );
  add_theme_support('post-thumbnails');
  add_editor_style("editor-style.css");

  // すべての不要な head 出力を削除
  function clean_up_wp_head() {
    // WordPress バージョン情報を削除
    remove_action('wp_head', 'wp_generator');
    add_filter('the_generator', '__return_empty_string');

    // RSD（Really Simple Discovery）リンクを削除
    remove_action('wp_head', 'rsd_link');

    // Windows Live Writer の wlwmanifest.xml を削除
    remove_action('wp_head', 'wlwmanifest_link');

    // Shortlink を削除
    remove_action('wp_head', 'wp_shortlink_wp_head', 10);
    remove_action('template_redirect', 'wp_shortlink_header', 11);

    // oEmbed 関連の `alternate` リンクを削除
    remove_action('wp_head', 'wp_oembed_add_discovery_links', 10);
    // remove_action('wp_head', 'rest_output_link_wp_head', 10); // ブロックエディタで必要

    // oEmbed のスクリプトを無効化
    remove_action('wp_head', 'wp_oembed_add_host_js');

    // WordPress REST API の link rel を削除
    // remove_action('template_redirect', 'rest_output_link_header', 11); // ブロックエディタで必要

    // Embed.js を無効化
    function disable_embed() {
        wp_dequeue_script('wp-embed');
    }
    add_action('wp_footer', 'disable_embed');

    // Dashicons を無効化（管理者以外）
    function disable_dashicons() {
        if (!is_admin()) {
            wp_dequeue_style('dashicons');
        }
    }
    add_action('wp_enqueue_scripts', 'disable_dashicons');

    // WordPress の RSS フィードを削除
    remove_action('wp_head', 'feed_links', 2);
    remove_action('wp_head', 'feed_links_extra', 3);

    // rel="canonical" を削除（Yoast SEOなどのプラグインを使っているなら不要）
    remove_action('wp_head', 'rel_canonical');

    // Prev / Next リンクを削除
    remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10);

    // DNS プリフェッチ（`s.w.org`）を削除
    remove_action('wp_head', 'wp_resource_hints', 2);

    // GutenbergのCSS（`wp-block-library`）を無効化
    function disable_gutenberg_css() {
        wp_dequeue_style('wp-block-library');
        wp_dequeue_style('wp-block-library-theme');
    }
    add_action('wp_enqueue_scripts', 'disable_gutenberg_css', 100);
  }
  add_action('init', 'clean_up_wp_head');


  // アイキャッチ画像のsrcset,size削除
  add_filter('wp_calculate_image_srcset_meta', '__return_null');

  // 簡易アイキャッチ画像出力
  function get_thumb_img($size = 'full', $alt = null, $p_id = null) {
    $p_id = ($p_id) ? $p_id : get_the_ID();
    $thumb_id = get_post_thumbnail_id($p_id);
    $thumb_img = wp_get_attachment_image_src($thumb_id, $size);
    $thumb_src = $thumb_img[0];
    $alt = ($alt) ? $alt : get_the_title($p_id);
    // XSS対策のためエスケープ処理を追加
    return '<img src="'.esc_url($thumb_src).'" alt="'.esc_attr($alt).'">';
  }


  function disable_emojis() {
    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
    remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
    remove_action( 'wp_print_styles', 'print_emoji_styles' );
    remove_action( 'admin_print_styles', 'print_emoji_styles' );
    remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
    remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
    remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
    add_filter( 'tiny_mce_plugins', 'disable_emojis_tinymce' );
    add_filter( 'wp_resource_hints', 'disable_emojis_remove_dns_prefetch', 10, 2 );
  }
  add_action( 'init', 'disable_emojis' );


  // 挿入画像サイズの指定
  function my_image_size_names_choose( $image_size_names ) {
    $image_size_names = array(
      'full' => __( 'Full Size' ),
    );

    return $image_size_names;
  }
  add_filter( 'image_size_names_choose', 'my_image_size_names_choose' );

  function disable_emojis_tinymce( $plugins ) {
    if ( is_array( $plugins ) ) {
    return array_diff( $plugins, array( 'wpemoji' ) );
    } else {
    return array();
    }
  }

  function disable_emojis_remove_dns_prefetch( $urls, $relation_type ) {
    if ( 'dns-prefetch' == $relation_type ) {
      /** This filter is documented in wp-includes/formatting.php */
      $emoji_svg_url = apply_filters( 'emoji_svg_url', 'https://s.w.org/images/core/emoji/2/svg/' );

      $urls = array_diff( $urls, array( $emoji_svg_url ) );
    }

    return $urls;
  }


  // セルフピンバックを無効化
  function wpsites_disable_self_pingbacks( &$links ) {
    foreach ( $links as $l => $link )
      if ( 0 === strpos( $link, get_option( 'home' ) ) )
        unset($links[$l]);
  }
  add_action( 'pre_ping', 'wpsites_disable_self_pingbacks' );


  // XML-RPC無効化（セキュリティ強化）
  add_filter('xmlrpc_enabled', '__return_false');

  // ログインエラーメッセージを曖昧化（セキュリティ強化）
  function custom_login_error_message() {
    return 'ログイン情報が正しくありません。';
  }
  add_filter('login_errors', 'custom_login_error_message');

  // 作成者アーカイブの無効化（ユーザー名漏洩対策）
  function disable_author_archives() {
    if (is_author()) {
      wp_redirect(home_url(), 301);
      exit;
    }
  }
  add_action('template_redirect', 'disable_author_archives');

  // REST APIからのユーザー情報露出を防止（ユーザーエンドポイントのみ、非ログインユーザーに404を返す）
  function restrict_rest_api_users($result, $server, $request) {
    $route = $request->get_route();

    // ユーザー情報エンドポイントの場合のみチェック
    if (strpos($route, '/wp/v2/users') !== false && !is_user_logged_in()) {
      return new WP_Error(
        'rest_forbidden',
        'このリソースにはアクセスできません。',
        array('status' => 403)
      );
    }

    return $result;
  }
  add_filter('rest_pre_dispatch', 'restrict_rest_api_users', 10, 3);

  // アプリケーションパスワード機能を無効化（WordPress 5.6以降）
  add_filter('wp_is_application_passwords_available', '__return_false');

  // ファイルエディター無効化（functions.phpの誤編集防止）
  // ※wp-config.phpに define('DISALLOW_FILE_EDIT', true); を追加する方が推奨
  // if (!defined('DISALLOW_FILE_EDIT')) {
  //   define('DISALLOW_FILE_EDIT', true);
  // }


  // クエリ文字列削除
  function remove_query_strings() {
    if(!is_admin()) {
      add_filter('script_loader_src', 'remove_query_strings_split', 15);
      add_filter('style_loader_src', 'remove_query_strings_split', 15);
    }
  }
  function remove_query_strings_split($src){
    $output = preg_split("/(&ver|\?ver)/", $src);
    return $output[0];
  }
  add_action('init', 'remove_query_strings');

  // theme.jsonからのインラインcssを無効化
  function dequeue_theme_json_styles() {
    remove_action( 'wp_enqueue_scripts', 'wp_enqueue_global_styles' );
    remove_action( 'wp_footer', 'wp_enqueue_global_styles', 1 );
  }
  add_action( 'wp_enqueue_scripts', 'dequeue_theme_json_styles', 9 );

  // あらゆる投稿でリビジョンを保存しない
  add_filter("wp_revisions_to_keep", "disable_all_revisions");
  function disable_all_revisions() {
    return 0;
  }

  // get_the_archive_title で勝手に追加される「アーカイブ:」などを削除
  function change_archive_title($title){
    $titleArray = explode(': ', $title);
    if($titleArray[1]):
      $title = $titleArray[1];
    endif;
    return $title;
  }
  add_filter('get_the_archive_title', 'change_archive_title');

  // Contact Form 7で自動挿入されるPタグ、brタグを削除
  add_filter('wpcf7_autop_or_not', 'wpcf7_autop_return_false');
  function wpcf7_autop_return_false() {
    return false;
  }

  // サイトURLとテンプレートディレクトリをglobal化
  // 各テンプレで宣言すること global $path, $siteURL, $homeURL;
  function set_global_template_paths() {
    global $path, $siteURL, $homeURL;
    $path = get_template_directory_uri();
    $homeURL = get_home_url( null, '/', 'ja' );
    $siteURL = get_bloginfo('url');
  }
  add_action('wp', 'set_global_template_paths');

  // get_template_partのインデント合わせや空白行削除に対応した便利呼び出し関数
  function get_component_with_indent($slug, $indent = 0, $args = []) {
    ob_start();
    get_template_part($slug, null, $args); // 第3引数で渡す
    $content = ob_get_clean();

    // 最初の空白行を削除
    $content = preg_replace('/^\s*\n/', '', $content, 1);

    // 行単位で処理
    $lines = explode("\n", rtrim($content));

    // 1行目以外にインデントを追加
    $indented = [$lines[0]];
    $space = str_repeat(' ', $indent);
    for ($i = 1; $i < count($lines); $i++) {
      $indented[] = $space . $lines[$i];
    }

    return implode("\n", $indented) . "\n";
  }


  // ブロックエディタでパターン登録とeditor-style.cssを有効化
  add_action('after_setup_theme', function () {
    add_theme_support('block-patterns');
    add_theme_support('editor-styles');
    add_editor_style('editor-style.css');
  });

  //固定ページの抜粋文機能を有効化する
  function my_custom_init() {
    add_post_type_support('page', 'excerpt');
  }
  add_action('init', 'my_custom_init');