<?php get_header(); ?>
<div class="bh-container" style="padding:32px 20px;">
  <?php while (have_posts()): the_post(); ?>
    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
      <h1><?php the_title(); ?></h1>
      <?php if (has_post_thumbnail()) the_post_thumbnail('large'); ?>
      <div><?php the_content(); ?></div>
    </article>
  <?php endwhile; ?>
</div>
<?php get_footer(); ?>
