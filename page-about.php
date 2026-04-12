<?php
/**
 * Template Name: About Us
 */
get_header(); ?>
<div class="bh-page-wrap">
  <div class="bh-container">
    <div class="bh-page-inner">

      <div class="bh-page-hero">
        <span class="bh-page-hero__icon"><i class="fas fa-store"></i></span>
        <h1 class="bh-page-hero__title"><?php _e('About Us','bazaarhub'); ?></h1>
        <p class="bh-page-hero__sub"><?php _e('Your trusted online shopping destination in Bangladesh.','bazaarhub'); ?></p>
      </div>

      <div class="bh-info-cards">
        <div class="bh-info-card">
          <i class="fas fa-shield-alt"></i>
          <h4><?php _e('100% Authentic','bazaarhub'); ?></h4>
          <p><?php _e('All products are genuine and quality-checked.','bazaarhub'); ?></p>
        </div>
        <div class="bh-info-card">
          <i class="fas fa-truck"></i>
          <h4><?php _e('Fast Delivery','bazaarhub'); ?></h4>
          <p><?php _e('Delivered to your doorstep across Bangladesh.','bazaarhub'); ?></p>
        </div>
        <div class="bh-info-card">
          <i class="fas fa-headset"></i>
          <h4><?php _e('24/7 Support','bazaarhub'); ?></h4>
          <p><?php _e('Our support team is always ready to help you.','bazaarhub'); ?></p>
        </div>
      </div>

      <div class="bh-page-body-content">
        <?php if (have_posts()): while (have_posts()): the_post();
          $content = get_the_content();
          if ($content): the_content();
          else: ?>
          <h2><?php _e('Who We Are','bazaarhub'); ?></h2>
          <p><?php _e('Modhu Bazar Shop is one of Bangladesh\'s trusted online shopping platforms. We are committed to bringing you the best quality products at the most competitive prices — delivered right to your doorstep.','bazaarhub'); ?></p>

          <h2><?php _e('Our Mission','bazaarhub'); ?></h2>
          <p><?php _e('Our mission is to make online shopping simple, affordable, and enjoyable for everyone. We strive to offer a wide range of products — from electronics and fashion to home essentials and groceries — all in one place.','bazaarhub'); ?></p>

          <h2><?php _e('Why Choose Us?','bazaarhub'); ?></h2>
          <ul>
            <li><?php _e('Wide selection of authentic products','bazaarhub'); ?></li>
            <li><?php _e('Competitive prices and frequent offers','bazaarhub'); ?></li>
            <li><?php _e('Secure and easy payment methods','bazaarhub'); ?></li>
            <li><?php _e('Fast delivery across Bangladesh','bazaarhub'); ?></li>
            <li><?php _e('Friendly customer support team','bazaarhub'); ?></li>
          </ul>

          <h2><?php _e('Contact Us','bazaarhub'); ?></h2>
          <?php $email = get_theme_mod('contact_email','support@modhubazarshop.com'); ?>
          <p><?php _e('Have questions? Reach us at','bazaarhub'); ?> <a href="mailto:<?php echo esc_attr($email); ?>"><?php echo esc_html($email); ?></a> <?php _e('or call us at','bazaarhub'); ?> <?php echo esc_html(get_theme_mod('top_bar_phone','01700-000000')); ?>.</p>
          <?php endif; endwhile; endif; ?>
      </div>

    </div>
  </div>
</div>
<?php get_footer(); ?>
