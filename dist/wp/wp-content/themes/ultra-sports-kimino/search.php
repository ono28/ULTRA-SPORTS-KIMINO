<?php
  global $path, $siteURL, $homeURL, $wp_query;

  get_header();
  ob_start();
?>

<div class="center_container">
  <header class="page_header">
    <h1 class="page_title" data-target data-slideup>
      <span class="ja"># <?php echo esc_attr(get_search_query()); ?></span>
    </h1>
  </header>

  <?php if ( have_posts() ) : ?>

  <section class="index_contents">
    <div class="grid_list grid_3">
      <?php if(have_posts()): while(have_posts()): the_post(); ?>

      <?= get_component_with_indent('component/thumb_study', 6) ?>
      <?php endwhile; endif; ?>

    </div>
  </section>
  <?php else : ?>

  <section class="no_result" data-target data-slideup>
    <p>該当の記事は見つかりませんでした。</p>
  </section>
  <?php endif; ?>

</div>


<?php $content = ob_get_clean(); echo preg_replace('/^/m', str_repeat(' ', 8), $content); ?>
<?php get_template_part('component/404'); ?>
<?php get_footer(); ?>