<?php
  global $path, $siteURL, $homeURL, $post;

  function echo_with_indent($html, $indent = 0) {
    $html = rtrim($html); // 末尾の空行防止
    $lines = explode("\n", str_replace(["\r\n", "\r"], "\n", $html));
    $space = str_repeat(' ', $indent);
    foreach ($lines as $line) {
      echo $space . $line . "\n";
    }
  }

  $outputJS = '';

  // if(is_front_page() || is_home()) {
  //   $outputJS .= '<script type="module" src="'. $path .'/assets/js/app/top.js"></script>';
  // }
  // elseif(is_page('contact')) {
  //   $outputJS .= '<script type="module" src="'. $path .'/assets/js/app/contact.js"></script>';
  // }
  // elseif(is_page('recruit')) {
  //   $outputJS .= '<script type="module" src="'. $path .'/assets/js/app/recruit.js"></script>';
  // }
?>
<?php //echo_with_indent('<a href="#wrapper" class="btn_pagetop" aria-label="Page Top"></a>', 8); ?>
<?php echo_with_indent('</main>', 6); ?>

<?php
  $footer = get_component_with_indent('component/global-footer', 6);
  $footer = preg_replace('/^(?!\s)/', str_repeat(' ', 6), $footer, 1);
  echo $footer;
?>
<?php echo_with_indent('</div>', 4); ?>

<?php echo_with_indent('<script type="module" src="' . $path . '/assets/js/app/main.js"></script>', 4); ?>
<?php echo_with_indent($outputJS, 4); ?>
<?php wp_footer(); ?>
<?php echo_with_indent('</body>', 2); ?>

</html>