<?php
  // NEWS一覧

  global $path, $siteURL, $homeURL;
  get_header();
  ob_start();
?>

<header class="page_header">
  <h1 class="page_title">
    <span class="en">NEWS</span>
    <span class="ja">お知らせ</span>
  </h1>
</header>

<div class="page_container">
  <ul class="categories" data-target data-slideup>
    <li><a href="<?php echo $siteURL; ?>/news/">All</a></li>
    <?php
      $terms = get_terms([
        'taxonomy'   => 'category',
        'hide_empty' => true,
        'parent'     => 0, // 親カテゴリーのみ取得
      ]);

      if (!empty($terms) && !is_wp_error($terms)) {
        $queried_object = get_queried_object(); // 現在のアーカイブページのオブジェクト
        $current_slug = '';

        if ($queried_object instanceof WP_Term) {
          $current_slug = $queried_object->slug; // カテゴリーアーカイブの場合
        }

        foreach ($terms as $term) {
          $q = new WP_Query([
            'post_type'        => 'post',
            'posts_per_page'   => 1,
            'tax_query'        => [
              [
                'taxonomy' => 'category',
                'terms'    => $term->term_id,
                'include_children' => false,
              ],
            ],
            'lang'             => $locale,
            'fields'           => 'ids', // ← IDだけ
          ]);

          if (empty($q->posts)) {
            continue;
          }

          $current_class = ($current_slug === $term->slug) ? 'active' : '';
          echo '<li><a href="' . esc_url(get_term_link($term)) . '" class="' . esc_attr($current_class) . '">' . esc_html($term->name) . '</a></li>' . "\n";
        }
        wp_reset_postdata();
      }
    ?>

  </ul>

  <?php if(have_posts()): ?>

  <ul class="news_lists" data-target data-slideup>
    <?php
      while(have_posts()): the_post();
      $terms = get_the_terms($post->ID, 'category');
    ?>

    <li class="post">
      <a href="<?php the_permalink(); ?>">
        <div class="date" datetime="<?php the_time('Y-m-d'); ?>"><?php the_time('Y.m.d'); ?></div>
        <?php if($terms): ?>

        <div class="tags">
          <?php
            foreach( $terms as $term ) {
              echo '<span>'.$term->name.'</span>';
            }
          ?>

        </div>
        <?php endif; ?>

        <div class="title"><?php the_title(); ?></div>
      </a>
    </li>
    <?php endwhile; ?>

  </ul>
  <?php else: ?>

  <p class="nopost" data-target data-slideup>記事はありません。</p>
  <?php endif; ?>

  <?php if ( $wp_query->max_num_pages > 1 ): ?>
  <div class="pagenate" data-target data-slideup>
    <?php wp_pagenavi(); ?>
  </div>
  <?php endif; ?>

</div>

<?php $content = ob_get_clean(); echo preg_replace('/^/m', str_repeat(' ', 8), $content); ?>
<?php get_footer(); ?>