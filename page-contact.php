<?php
/**
 * Template Name: Contact Us
 */
get_header(); ?>
<div class="bh-page-wrap">
  <div class="bh-container">
    <div class="bh-page-inner">

      <div class="bh-page-hero" style="background:linear-gradient(135deg,#1565c0,#42a5f5)">
        <span class="bh-page-hero__icon"><i class="fas fa-headset"></i></span>
        <h1 class="bh-page-hero__title"><?php _e('Contact Us','bazaarhub'); ?></h1>
        <p class="bh-page-hero__sub"><?php _e('We\'re here to help. Reach out anytime!','bazaarhub'); ?></p>
      </div>

      <div class="bh-info-cards">
        <div class="bh-info-card">
          <i class="fas fa-phone-alt"></i>
          <h4><?php _e('Phone','bazaarhub'); ?></h4>
          <p><?php echo esc_html(get_theme_mod('top_bar_phone','01700-000000')); ?></p>
        </div>
        <div class="bh-info-card">
          <i class="fas fa-envelope"></i>
          <h4><?php _e('Email','bazaarhub'); ?></h4>
          <p>support@modhubazarshop.com</p>
        </div>
        <div class="bh-info-card">
          <i class="fas fa-clock"></i>
          <h4><?php _e('Working Hours','bazaarhub'); ?></h4>
          <p><?php _e('Sat–Thu: 9AM – 9PM','bazaarhub'); ?></p>
        </div>
      </div>

      <?php if (have_posts()): while (have_posts()): the_post();
        $content = get_the_content();
        if ($content): ?>
          <div class="bh-page-body-content"><?php the_content(); ?></div>
        <?php endif; endwhile; endif; ?>

      <div class="bh-contact-form">
        <h3><i class="fas fa-paper-plane" style="color:#43a047;margin-right:8px"></i><?php _e('Send Us a Message','bazaarhub'); ?></h3>
        <?php if (function_exists('wpcf7_enqueue_scripts')): ?>
          <?php echo do_shortcode('[contact-form-7 id="1" title="Contact form 1"]'); ?>
        <?php else: ?>
        <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
          <input type="hidden" name="action" value="bh_contact_form">
          <?php wp_nonce_field('bh_contact','bh_contact_nonce'); ?>
          <div class="bh-form-row">
            <input type="text" name="your_name" placeholder="<?php esc_attr_e('Your Name','bazaarhub'); ?>" required>
            <input type="email" name="your_email" placeholder="<?php esc_attr_e('Your Email','bazaarhub'); ?>" required>
          </div>
          <div style="margin-bottom:14px">
            <input type="text" name="subject" placeholder="<?php esc_attr_e('Subject','bazaarhub'); ?>">
          </div>
          <div style="margin-bottom:14px">
            <textarea name="message" placeholder="<?php esc_attr_e('Your message...','bazaarhub'); ?>" required></textarea>
          </div>
          <button type="submit"><i class="fas fa-paper-plane"></i> <?php _e('Send Message','bazaarhub'); ?></button>
        </form>
        <?php endif; ?>
      </div>

    </div>
  </div>
</div>
<?php get_footer(); ?>
