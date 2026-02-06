<?php
  global $path, $siteURL, $homeURL;
  $locale = get_locale();
  ob_start();

  $homeTitle = 'HOME';
  if($locale == 'en_US') {
    $homeTitle = 'Home';
  }

  $newsTitle = 'お知らせ';
  if($locale == 'en_US') {
    $newsTitle = 'News';
  }

  global $post;

  if (is_front_page()) return; // フロントページでは表示しない

  $breadcrumbs = [];

  // ホームリンク
  $breadcrumbs[] = '<li><a href="' . esc_url(home_url('/')) . '">'. $homeTitle .'</a></li>';

  // ニューストップ（/news/）
  if (is_home() || is_post_type_archive('news')) {
    $breadcrumbs[] = '<li class="current">'. $newsTitle .'</li>';
  }

  // 投稿（news）の場合
  elseif (is_singular('news')) {
    // お知らせへのリンク
    $breadcrumbs[] = '<li><a href="' . esc_url(home_url('/news/')) . '">'. $newsTitle .'</a></li>';

    // 記事タイトル（カレント）
    $breadcrumbs[] = '<li class="current">' . esc_html(get_the_title()) . '</li>';
  }

  // カテゴリーアーカイブの場合
  elseif (is_category()) {
    $category = get_queried_object();

    // ニュースカテゴリー用のパンくず
    $breadcrumbs[] = '<li><a href="' . esc_url(home_url('/news/')) . '">'. $newsTitle .'</a></li>';
    $breadcrumbs[] = '<li class="current">' . esc_html($category->name) . '</li>';
  }

  // 固定ページ・カスタム投稿タイプ（従来の処理を維持）
  elseif (is_singular()) {
    $post_type = get_post_type_object(get_post_type());

    // カスタム投稿タイプの場合（投稿・固定ページ以外）
    if ($post_type && get_post_type() != 'post' && get_post_type() != 'page') {
      $breadcrumbs[] = '<li><a href="' . get_post_type_archive_link($post_type->name) . '">' . esc_html($post_type->label) . '</a></li>';
    }

    // 階層構造のパンくずリスト
    $parent_id = $post->post_parent;
    $page_crumbs = [];

    while ($parent_id) {
      $parent = get_post($parent_id);
      $page_crumbs[] = '<li><a href="' . get_permalink($parent) . '">' . esc_html(get_the_title($parent)) . '</a></li>';
      $parent_id = $parent->post_parent; // 親ページを取得
    }

    // 「孫 → 子 → 親」の順番にする
    $breadcrumbs = array_merge($breadcrumbs, array_reverse($page_crumbs));

    // 現在のページ（カレント）
    $breadcrumbs[] = '<li class="current">' . esc_html(get_the_title()) . '</li>';
  }

  $content = implode("\n", $breadcrumbs);
  $indentedContent = preg_replace('/^/m', str_repeat(' ', 2), $content);

  // 最初の行だけインデントなし
  $indentedContent = preg_replace('/^\s+/', '', $indentedContent);
?>

<ul class="pankuzu">
  <?php echo $indentedContent; ?>

</ul>

<?php $content = ob_get_clean(); echo preg_replace('/^/m', str_repeat(' ', 8), $content); ?>