<?php
  /*
  Template Name: ACCESS
  */

  global $path, $siteURL, $homeURL;
  get_header();
  ob_start();
?>

<header class="page_header">
  <h1 class="page_title">
    <span class="en">ACCESS</span>
    <span class="ja">アクセス</span>
  </h1>
</header>

<div class="page_container">
  <br><br><br><br><br><br><br><br>
  <br><br><br><br><br><br><br><br>
  <br><br><br><br><br><br><br><br>
  <br><br><br><br><br><br><br><br>
  <br><br><br><br><br><br><br><br>
</div>

<?php $content = ob_get_clean(); echo preg_replace('/^/m', str_repeat(' ', 8), $content); ?>
<?php get_footer(); ?>