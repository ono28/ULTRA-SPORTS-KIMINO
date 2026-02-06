<?php
  global $path, $siteURL, $homeURL;

  if(!is_front_page()) {
    $isUnder = 'class="under"';
  }
  else {
    $isUnder = '';
  }
?>

<header id="globalHeader">
  <div class="logo" role="img" aria-label="FULLERENE">
    <i class="l_icon"></i>
    <i class="l_text"></i>
  </div>
</header>