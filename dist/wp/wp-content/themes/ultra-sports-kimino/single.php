<?php
  global $path, $siteURL;

  get_header();

  $post_type = 'post';
  $prevpost = get_adjacent_post(false, '', true); //前の記事
  $nextpost = get_adjacent_post(false, '', false); //次の記事

  ob_start();
?>

<header class="page_header">
  <h1 class="page_title">
    <span class="en">NEWS</span>
    <span class="ja">お知らせ</span>
  </h1>
</header>

<div class="page_container">
  <header class="post_header" data-target data-slideup>
    <time class="date" datetime="<?php the_time('Y-m-d'); ?>"><?php the_time('Y.m.d'); ?></time>
    <h1 class="title"><?php the_title(); ?></h1>
  </header>

  <article class="post_contents" data-target data-slideup>
    <?php the_content(); ?>

  </article>

  <footer class="post_footer">
    <nav class="post_navi" data-target data-slideup>
      <?php if (get_previous_post()):?><a href="<?php echo get_permalink($prevpost->ID); ?>" class="btn left" aria-label="前の記事">Prev</a><?php endif; ?>

      <a class="btn_center" href="<?php echo $siteURL; ?>/news/">Back to List</a>
      <?php if (get_next_post()):?><a href="<?php echo get_permalink($nextpost->ID); ?>" class="btn right" aria-label="次の記事">Next</a><?php endif; ?>

    </nav>
  </footer>
</div>

<?php $content = ob_get_clean(); echo preg_replace('/^/m', str_repeat(' ', 8), $content); ?>
<?php get_template_part('component/404'); ?>
<?php get_footer(); ?>