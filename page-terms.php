<?php
/**
 * Template Name: Terms & Conditions
 */
get_header(); ?>
<div class="bh-page-wrap">
  <div class="bh-container">
    <div class="bh-page-inner">

      <div class="bh-page-hero" style="background:linear-gradient(135deg,#e65100,#ff8f00)">
        <span class="bh-page-hero__icon"><i class="fas fa-file-contract"></i></span>
        <h1 class="bh-page-hero__title"><?php _e('Terms & Conditions','bazaarhub'); ?></h1>
        <p class="bh-page-hero__sub"><?php _e('Please read these terms carefully before using our services.','bazaarhub'); ?></p>
      </div>

      <div class="bh-page-body-content">
        <?php if (have_posts()): while (have_posts()): the_post();
          $content = get_the_content();
          if ($content): the_content();
          else: ?>
          <h2><?php _e('Acceptance of Terms','bazaarhub'); ?></h2>
          <p><?php _e('By accessing and using Modhu Bazar Shop, you accept and agree to be bound by these Terms and Conditions. If you do not agree, please do not use our website.','bazaarhub'); ?></p>

          <h2><?php _e('Products and Pricing','bazaarhub'); ?></h2>
          <ul>
            <li><?php _e('All product prices are listed in Bangladeshi Taka (BDT).','bazaarhub'); ?></li>
            <li><?php _e('Prices are subject to change without prior notice.','bazaarhub'); ?></li>
            <li><?php _e('We reserve the right to limit quantities on any product.','bazaarhub'); ?></li>
            <li><?php _e('Product images are for illustrative purposes only.','bazaarhub'); ?></li>
          </ul>

          <h2><?php _e('Orders and Payment','bazaarhub'); ?></h2>
          <p><?php _e('Orders are confirmed only after successful payment. We accept bKash, Nagad, credit/debit cards, and cash on delivery. We reserve the right to refuse or cancel any order for reasons including product unavailability or pricing errors.','bazaarhub'); ?></p>

          <h2><?php _e('Delivery','bazaarhub'); ?></h2>
          <p><?php _e('Delivery timelines are estimates and may vary depending on location and availability. We are not responsible for delays caused by courier services or unforeseen circumstances.','bazaarhub'); ?></p>

          <h2><?php _e('User Accounts','bazaarhub'); ?></h2>
          <p><?php _e('You are responsible for maintaining the confidentiality of your account credentials. You agree to notify us immediately of any unauthorized use of your account.','bazaarhub'); ?></p>

          <h2><?php _e('Limitation of Liability','bazaarhub'); ?></h2>
          <p><?php _e('Modhu Bazar Shop shall not be liable for any indirect, incidental, or consequential damages arising from the use of our services or products.','bazaarhub'); ?></p>

          <h2><?php _e('Contact','bazaarhub'); ?></h2>
          <p><?php _e('For questions about these terms, contact us at','bazaarhub'); ?> <a href="mailto:support@modhubazarshop.com">support@modhubazarshop.com</a>.</p>
          <?php endif; endwhile; endif; ?>
      </div>

    </div>
  </div>
</div>
<?php get_footer(); ?>
