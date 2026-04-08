<?php get_header(); ?>
<div class="bh-page-wrap">
  <div class="bh-container">
    <div class="bh-page-inner">
      <?php while (have_posts()): the_post(); ?>
        <div class="bh-page-hero">
          <h1 class="bh-page-hero__title"><?php the_title(); ?></h1>
          <p class="bh-page-hero__sub"><?php echo esc_html(get_bloginfo('name')); ?> &mdash; <?php the_title(); ?></p>
        </div>
        <div class="bh-page-body-content">
          <?php the_content(); ?>
        </div>
      <?php endwhile; ?>
    </div>
  </div>
</div>
<?php get_footer(); ?>
