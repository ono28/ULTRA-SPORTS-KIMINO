<?php
  // PREV NEXTボタンの調整
  add_filter( 'previous_posts_link_attributes', 'add_prev_posts_link_class' );
  function add_prev_posts_link_class() {
    return 'class="prev previouspostslink"';
  }
  add_filter( 'next_posts_link_attributes', 'add_next_posts_link_class' );
  function add_next_posts_link_class() {
    return 'class="next nextpostslink"';
  }

  add_filter( 'previous_post_link', 'add_prev_post_link_class' );
  function add_prev_post_link_class($output) {
    return str_replace('<a href=', '<a class="prev previouspostslink" href=', $output);
  }
  add_filter( 'next_post_link', 'add_next_post_link_class' );
  function add_next_post_link_class($output) {
    return str_replace('<a href=', '<a class="next nextpostslink" href=', $output);
  }