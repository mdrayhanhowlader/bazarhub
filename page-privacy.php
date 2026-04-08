<?php
/**
 * Template Name: Privacy Policy
 */
get_header(); ?>
<div class="bh-page-wrap">
  <div class="bh-container">
    <div class="bh-page-inner">

      <div class="bh-page-hero" style="background:linear-gradient(135deg,#4a148c,#9c27b0)">
        <span class="bh-page-hero__icon"><i class="fas fa-user-shield"></i></span>
        <h1 class="bh-page-hero__title"><?php _e('Privacy Policy','bazaarhub'); ?></h1>
        <p class="bh-page-hero__sub"><?php _e('Last updated: January 2026','bazaarhub'); ?></p>
      </div>

      <div class="bh-page-body-content">
        <?php if (have_posts()): while (have_posts()): the_post();
          $content = get_the_content();
          if ($content): the_content();
          else: ?>
          <h2><?php _e('Information We Collect','bazaarhub'); ?></h2>
          <p><?php _e('We collect information you provide directly to us, such as when you create an account, place an order, or contact customer support. This includes your name, email address, phone number, shipping address, and payment information.','bazaarhub'); ?></p>

          <h2><?php _e('How We Use Your Information','bazaarhub'); ?></h2>
          <ul>
            <li><?php _e('To process and deliver your orders','bazaarhub'); ?></li>
            <li><?php _e('To send order confirmations and updates','bazaarhub'); ?></li>
            <li><?php _e('To respond to your inquiries and support requests','bazaarhub'); ?></li>
            <li><?php _e('To send promotional offers and newsletters (with your consent)','bazaarhub'); ?></li>
            <li><?php _e('To improve our website and services','bazaarhub'); ?></li>
          </ul>

          <h2><?php _e('Data Security','bazaarhub'); ?></h2>
          <p><?php _e('We implement appropriate technical and organizational security measures to protect your personal information against unauthorized access, alteration, disclosure, or destruction. Your payment information is encrypted using SSL technology.','bazaarhub'); ?></p>

          <h2><?php _e('Cookies','bazaarhub'); ?></h2>
          <p><?php _e('We use cookies to enhance your browsing experience, analyze site traffic, and personalize content. You can control cookie settings through your browser preferences.','bazaarhub'); ?></p>

          <h2><?php _e('Third-Party Sharing','bazaarhub'); ?></h2>
          <p><?php _e('We do not sell or rent your personal information to third parties. We may share your information with trusted service providers who help us operate our business (e.g., delivery partners, payment processors) under strict confidentiality agreements.','bazaarhub'); ?></p>

          <h2><?php _e('Contact','bazaarhub'); ?></h2>
          <p><?php _e('If you have questions about this Privacy Policy, contact us at','bazaarhub'); ?> <a href="mailto:support@modhubazarshop.com">support@modhubazarshop.com</a>.</p>
          <?php endif; endwhile; endif; ?>
      </div>

    </div>
  </div>
</div>
<?php get_footer(); ?>
