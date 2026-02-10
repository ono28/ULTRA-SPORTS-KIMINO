<?php
  /*
  Template Name: FAQ
  */

  global $path, $siteURL, $homeURL;
  get_header();
  ob_start();
?>

<header class="page_header">
  <h1 class="page_title">
    <span class="en">FAQ</span>
    <span class="ja">よくある質問</span>
  </h1>
</header>

<div class="page_container">
  <div class="faq_container">
    <?php if(have_rows('faq_section')): ?>
    <?php while(have_rows('faq_section')): the_row(); ?>

    <section class="faq_section">
      <h2 class="faq_title"><?php the_sub_field('faq_section_title'); ?></h2>
      <div class="faq_lists">
        <?php if(have_rows('faq')): ?>
        <?php while(have_rows('faq')): the_row(); ?>

        <?php if( have_rows('faq_qa') ): ?>
        <?php while( have_rows('faq_qa') ): the_row(); ?>

        <dl class="faq_unit acc">
          <dt>
            <button class="acc_trigger"><?php the_sub_field('faq_qa_question'); ?></button>
          </dt>
          <dd class="acc_body">
            <?php the_sub_field('faq_qa_answer'); ?>
          </dd>
        </dl>
        <?php endwhile; ?>
        <?php endif; ?>

        <?php endwhile; ?>
        <?php endif; ?>

      </div>
    </section>
    <?php endwhile; ?>
    <?php endif; ?>

  </div>
</div>

<?php $content = ob_get_clean(); echo preg_replace('/^/m', str_repeat(' ', 8), $content); ?>
<?php get_footer(); ?>