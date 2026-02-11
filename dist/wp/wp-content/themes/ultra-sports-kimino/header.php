<?php
  if (!defined('ABSPATH')) {
    exit; // 安全のため、直接アクセスを防ぎます
  }

  global $path, $siteURL, $homeURL;
  $locale = get_locale();

  // meta設定
  $pageTITLE = '';
  $defaultTile = '';
  $ogDESCRIPTION = '';
  $pageDESCRIPTION = '';
  $siteKEYWORDS = '';
  $pageSTATUS = '';

  ob_start();
  bloginfo('url');
  $siteURL = ob_get_clean();

  ob_start();
  bloginfo('name');
  $siteNAME = ob_get_clean();

  ob_start();
  bloginfo('description');
  $siteDESCRIPTION = ob_get_clean();

  // フロントページ以外の固定ページの場合のみカスタムフィールドを使用
  if ( is_singular('page') && !is_front_page() ) {
    global $post;
    $description = '';

    // 通常の投稿タイプや固定ページは個別のカスタムフィールドを使用
    if ( $post->post_type === 'post' || $post->post_type === 'page' ) {
      $description = get_post_meta($post->ID, 'meta_description', true);

      // 親ページの description を取得
      if ( empty($description) && $post->post_parent ) {
        $parent_id = $post->post_parent;
        $description = get_post_meta($parent_id, 'meta_description', true);
      }
    }


    // description が設定されていなければデフォルトを設定
    if ( empty($description) ) {
        $description = $siteDESCRIPTION;
    }

    $siteDESCRIPTION = $description;
  }

  // カスタム投稿タイプは個別設定を無視し、1箇所で統一管理
  if ( get_post_type() === 'study' ) {
    $siteDESCRIPTION = "";

    if(is_single()) {
      $excerpt = get_the_excerpt();
      $siteDESCRIPTION = $excerpt ? $excerpt : $siteDESCRIPTION;
    }
  } elseif( get_post_type() === 'page' ) {
    $excerpt = get_the_excerpt();
    $siteDESCRIPTION = $excerpt ? $excerpt : $siteDESCRIPTION;
  }



  // bodyのid/class設定
  if(is_front_page()) {
    $pageSTATUS = 'data-parent="top"';
    $namespace = 'top';
  }
  elseif(is_home()) {
    $pageSTATUS = 'data-parent="news" data-child="index"';
    $namespace = 'news';
  }
  elseif(is_category() || is_tag()) {
    $pageSTATUS = 'data-parent="news" data-child="index"';
    $namespace = 'news';
  }
  elseif(is_singular('post')) {
    $pageSTATUS = 'data-parent="news" data-child="detail"';
    $namespace = 'news';
  }
  elseif(is_page()) {
    $parent_id = get_the_ID();
    $slugs = [];

    while ($parent_id) {
      $parent = get_post($parent_id);
      $slugs[] = get_post_field('post_name', $parent);
      $parent_id = $parent->post_parent; // 親がいれば更新
    }

    // **正しい順番で並べる → 親 | 子 | 孫**
    $slugs = array_reverse($slugs);

    // スラッグを個別の `data` 属性にセット
    $data_attributes = [];
    $slug_count = count($slugs);

    // 親ページのときはdata-childにindexを追加
    if ($slug_count === 1) {
      $data_attributes[] = 'data-child="index"';
    }

    if ($slug_count >= 1) $data_attributes[] = 'data-parent="'. esc_attr($slugs[0]) .'"';
    if ($slug_count >= 2) $data_attributes[] = 'data-child="'. esc_attr($slugs[1]) .'"';
    if ($slug_count >= 3) $data_attributes[] = 'data-grandchild="'. esc_attr($slugs[2]) .'"';

    // 各 `data-*` 属性を結合
    $pageSTATUS = implode(' ', $data_attributes);
  }

  // title設定
  if (is_front_page() ) {
    $pageTITLE = $siteNAME . $defaultTile;
  }
  elseif (is_home() ) {
    // 投稿ページが固定ページの場合はそのタイトルを取得
    $post_page_id = get_option('page_for_posts');
    if ($post_page_id) {
      $pageTITLE = get_the_title($post_page_id) . ' | ' . $siteNAME . $defaultTile;
    } else {
      $pageTITLE = $siteNAME . $defaultTile;
    }
  }
  elseif (is_category() || is_tag()) {
    // NEWSカテゴリー一覧ページ
    $post_page_id = get_option('page_for_posts');
    $news_title = $post_page_id ? get_the_title($post_page_id) : '';
    $pageTITLE = single_cat_title('', false) . ' | ' . $news_title . ' | ' . $siteNAME . $defaultTile;
  }
  elseif(is_singular('post')) {
    // 投稿ページ（NEWS）の固定ページIDを取得
    $post_page_id = get_option('page_for_posts');
    $news_title = $post_page_id ? get_the_title($post_page_id) : '';
    $pageTITLE = get_the_title() . ' | ' . $news_title . ' | ' . $siteNAME . $defaultTile;
  }
  elseif (is_page()) {
    global $post;

    $titles = [get_the_title()]; // 現在のページのタイトルを追加
    $parent_id = $post->post_parent;

    while ($parent_id) {
        $parent = get_post($parent_id);
        $titles[] = get_the_title($parent);
        $parent_id = $parent->post_parent; // さらに上の親がいるかチェック
    }

    $pageTITLE = implode(' | ', $titles) . ' | ' . $siteNAME . $defaultTile;
  }
  elseif ( is_singular() ) {
    $post_type = get_post_type();

    // report のときだけ ACFのフィールドを使用
    if ( $post_type === 'report' ) {
      $acf_title = get_field('report_title_detail'); // ACFのフィールド名をここで指定
      $title = $acf_title
        ? strip_tags(str_replace(
            ['&lt;br&gt;', '&lt;br>', '<br>', "\n", "\r"], '', $acf_title)
          )
        : strip_tags(get_the_title());
    } else {
      $title = strip_tags(get_the_title());
    }

    // CPTの場合のみ投稿タイプ名を追加
    if ( !in_array( $post_type, ['post', 'page'] ) ) {
      $post_type_obj = get_post_type_object( $post_type );
      $post_type_label = $post_type_obj ? $post_type_obj->labels->singular_name : '';
      $pageTITLE = "{$title} | {$post_type_label} | {$siteNAME}{$defaultTile}";
    } else {
      $pageTITLE = "{$title} | {$siteNAME}{$defaultTile}";
    }
  }
  elseif (is_category()) {
    $pageTITLE = single_cat_title('' , $display = false) . ' | ' . $siteNAME . $defaultTile;
  }
  elseif (is_tag()) {
    $pageTITLE = single_tag_title('' , $display = false) . ' | ' . $siteNAME . $defaultTile;
  }
  elseif (is_tax()) {
    $pageTITLE = single_term_title('' , $display = false) . ' | ' . $siteNAME . $defaultTile;
  }
  elseif (is_month()) {
    $pageTITLE = get_the_time('Y-m') . ' | ' . $siteNAME . $defaultTile;
  }
  elseif (is_year()) {
    $pageTITLE = get_the_time('Y') . ' | ' . $siteNAME . $defaultTile;
  }
  elseif (is_search()) {
    $pageTITLE = '『' . get_search_query() . '』を検索 | ' . $siteNAME . $defaultTile;
  }
  elseif (is_author()) {
    $pageTITLE = get_the_author() . ' | ' . $siteNAME . $defaultTile;
  }
  elseif (is_archive()) {
    $pageTITLE = strip_tags(get_the_archive_title('' , $display = false)) . ' | ' . $siteNAME . $defaultTile;
  }


  $ogDESCRIPTION = $siteDESCRIPTION;

  if(!is_front_page()) {
    $isUnder = ' class="under"';
  }
  else {
    $isUnder = '';
  }
?>
<!DOCTYPE html>
<html lang="<?php bloginfo('language'); ?>">

  <head>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-QD6W8VTH8P"></script>
    <script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
      dataLayer.push(arguments);
    }
    gtag('js', new Date());

    gtag('config', 'G-QD6W8VTH8P');
    </script>


    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title><?php echo esc_html($pageTITLE); ?></title>

    <meta name="description" content="<?php echo $siteDESCRIPTION; ?>">
    <meta name="viewport" content="width=device-width,initial-scale=1">

    <meta property="og:site_name" content="<?php echo $siteNAME; ?>">
    <?php
if (is_singular('page') && !is_front_page()) {
  $permalink = get_permalink();
  $og_image = "{$path}/assets/img/ogp.jpg";

  if (has_post_thumbnail()) {
    $thumb = get_the_post_thumbnail_url(get_the_ID(), 'full');
    if ($thumb) {
      $og_image = esc_url($thumb);
    }
  }
echo <<< EOD
<meta property="og:title" content="{$pageTITLE}">
    <meta property="og:description" content="{$siteDESCRIPTION}">
    <meta property="og:url" content="{$permalink}">
    <meta property="og:type" content="article">
    <meta property="og:image" content="{$og_image}">
    <meta name="twitter:title" content="{$pageTITLE}">
    <meta name="twitter:description" content="{$siteDESCRIPTION}">
    <meta name="twitter:image:src" content="{$og_image}">
EOD;
}
elseif (is_singular('post')) {
  $permalink = get_permalink();
  $og_image = "{$path}/assets/img/ogp.jpg";
  $siteDESCRIPTION = get_the_excerpt(); // 抜粋をdescriptionに

  if (has_post_thumbnail()) {
    $thumb = get_the_post_thumbnail_url(get_the_ID(), 'full');
    if ($thumb) {
      $og_image = esc_url($thumb);
    }
  }
echo <<< EOD
<meta property="og:title" content="{$pageTITLE}">
    <meta property="og:description" content="{$siteDESCRIPTION}">
    <meta property="og:url" content="{$permalink}">
    <meta property="og:type" content="article">
    <meta property="og:image" content="{$og_image}">
    <meta name="twitter:title" content="{$pageTITLE}">
    <meta name="twitter:description" content="{$siteDESCRIPTION}">
    <meta name="twitter:image:src" content="{$og_image}">
EOD;
}
elseif(is_page()){
  $permalink = get_permalink();
echo <<< EOD
<meta property="og:title" content="{$pageTITLE}">
    <meta property="og:description" content="{$ogDESCRIPTION}">
    <meta property="og:url" content="{$permalink}">
    <meta property="og:type" content="website">
    <meta property="og:image" content="{$path}/assets/img/ogp.jpg">
    <meta name="twitter:title" content="{$pageTITLE}">
    <meta name="twitter:description" content="{$ogDESCRIPTION}">
    <meta name="twitter:image:src" content="{$path}/assets/img/ogp.jpg">
EOD;
}
else {
echo <<< EOD
<meta property="og:title" content="{$pageTITLE}">
    <meta property="og:description" content="{$siteDESCRIPTION}">
    <meta property="og:url" content="{$siteURL}">
    <meta property="og:type" content="website">
    <meta property="og:image" content="{$path}/assets/img/ogp.png">
    <meta name="twitter:title" content="{$pageTITLE}">
    <meta name="twitter:description" content="{$siteDESCRIPTION}">
    <meta name="twitter:image:src" content="{$path}/assets/img/ogp.jpg">
EOD;
}
?>

    <meta name="twitter:card" content="summary_large_image">
    <?php
      $site_icon = get_site_icon_url(512, $path . '/assets/img/favicon.png');
      if ($site_icon):
    ?>

    <link rel="icon" type="image/png" href="<?= esc_url($site_icon); ?>">
    <?php endif; ?>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preload" as="style" fetchpriority="high" href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Zen+Kaku+Gothic+New:wght@400;700&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Zen+Kaku+Gothic+New:wght@400;700&display=swap" media="print" onload='this.media="all"'>

    <link rel="stylesheet" href="<?php echo $path; ?>/assets/css/main.css" media="all">

    <?php wp_head(); ?>

  </head>

  <body <?php echo $pageSTATUS; ?>>
    <div id="loading"></div>

    <div id="wrapper">
      <div class="page_bg"></div>

      <?php echo get_component_with_indent('component/global-header', 6); ?>

      <main id="container" <?php echo $isUnder; ?>>