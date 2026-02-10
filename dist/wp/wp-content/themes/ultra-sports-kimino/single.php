<?php
  global $path, $siteURL;

  get_header();

  $post_type = 'post';
  $prevpost = get_adjacent_post(false, '', true); //前の記事
  $nextpost = get_adjacent_post(false, '', false); //次の記事

  $category = get_the_category();

  $current_url = get_permalink();
  $share_text = get_the_title() . '';

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

  <div class="post_container">
    <header class="post_header" data-target data-slideup>
      <time class="date" datetime="<?php the_time('Y-m-d'); ?>"><?php the_time('Y.m.d'); ?></time>
      <?php if($category): ?>

      <div class="tags">
        <?php
          foreach( $category as $cat ) {
            echo '<span>'.$cat->name.'</span>';
          }
        ?>

      </div>
      <?php endif; ?>

      <h1 class="title"><?php the_title(); ?></h1>
    </header>

    <article class="post_contents" data-target data-slideup>
      <?php the_content(); ?>

    </article>

    <section class="share" data-target data-slideup>
      <dl>
        <dt>この記事をシェアする</dt>
        <dd>
          <a href="https://www.facebook.com/sharer.php?u=<?php echo $current_url; ?>" target="_blank" class="fb"></a>
          <a href="https://twitter.com/intent/tweet?url=<?php echo $current_url; ?>&text=<?php echo $share_text; ?>" target="_blank" class="x"></a>
        </dd>
      </dl>
    </section>
  </div>
</div>

<?php $content = ob_get_clean(); echo preg_replace('/^/m', str_repeat(' ', 8), $content); ?>
<?php get_template_part('component/404'); ?>
<?php get_footer(); ?>