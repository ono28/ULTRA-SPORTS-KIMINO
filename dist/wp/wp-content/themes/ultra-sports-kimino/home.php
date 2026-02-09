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
  <?php if(have_posts()): ?>

  <ul class="news_lists">
    <?php  while(have_posts()): the_post(); ?>

    <li class="post" data-target data-slideup>
      <a href="<?php the_permalink(); ?>">
        <span class="date" datetime="<?php the_time('Y-m-d'); ?>"><?php the_time('Y.m.d'); ?></span>
        <span class="title"><?php the_title(); ?></span>
      </a>
    </li>
    <?php endwhile; ?>

  </ul>
  <?php else: ?>

  <?php if($locale == 'ja'): ?>

  <p class="nopost" data-target data-slideup>記事はありません。</p>
  <?php else: ?>

  <p class="nopost" data-target data-slideup>No Post</p>
  <?php endif; ?>

  <?php endif; ?>

</div>

<?php $content = ob_get_clean(); echo preg_replace('/^/m', str_repeat(' ', 8), $content); ?>
<?php get_footer(); ?>