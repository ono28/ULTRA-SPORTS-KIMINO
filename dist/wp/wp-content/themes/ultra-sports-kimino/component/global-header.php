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
  <a class="logo" href="<?php echo $siteURL; ?>" aria-label="ULTRA SPORTS KIMINO">
    <i class="l_icon"></i>
    <i class="l_text"></i>
  </a>
</header>

<button id="btnMenu">
  <span></span>
  <span></span>
</button>

<nav id="globalNavi">
  <div class="inner">
    <ul class="gnavi">
      <li>
        <a href="<?php echo $siteURL; ?>/about/">ABOUT</a>
      </li>
      <li>
        <a href="<?php echo $siteURL; ?>/sports/">SPORTS</a>
      </li>
      <li>
        <a href="<?php echo $siteURL; ?>/camp-food/">CAMP & FOOD</a>
      </li>
      <li>
        <a href="<?php echo $siteURL; ?>/news/">NEWS</a>
      </li>
      <li>
        <a href="<?php echo $siteURL; ?>/access/">ACCESS</a>
      </li>

      <li data-sp>
        <a href="<?php echo $siteURL; ?>/faq/">FAQ</a>
      </li>
    </ul>

    <?php echo get_component_with_indent('component/sns', 4); ?>

    <ul class="external_links">
      <li>
        <a href="#" target="_blank">
          <span>施設の<br data-pc data-tb>ご予約</span>
        </a>
      </li>
      <li>
        <a href="#" target="_blank">
          <span>宿泊の<br data-pc data-tb>ご予約</span>
        </a>
      </li>
    </ul>
  </div>
</nav>