<?php
  global $path, $siteURL, $homeURL;
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