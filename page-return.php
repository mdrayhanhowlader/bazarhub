<?php
/**
 * Template Name: Return Policy
 */
get_header(); ?>
<div class="bh-page-wrap">
  <div class="bh-container">
    <div class="bh-page-inner">

      <div class="bh-page-hero" style="background:linear-gradient(135deg,#c62828,#f44336)">
        <span class="bh-page-hero__icon"><i class="fas fa-undo-alt"></i></span>
        <h1 class="bh-page-hero__title"><?php _e('Return Policy','bazaarhub'); ?></h1>
        <p class="bh-page-hero__sub"><?php _e('Easy returns within 7 days of delivery.','bazaarhub'); ?></p>
      </div>

      <div class="bh-info-cards">
        <div class="bh-info-card">
          <i class="fas fa-calendar-check"></i>
          <h4><?php _e('7-Day Returns','bazaarhub'); ?></h4>
          <p><?php _e('Return eligible items within 7 days.','bazaarhub'); ?></p>
        </div>
        <div class="bh-info-card">
          <i class="fas fa-box-open"></i>
          <h4><?php _e('Original Packaging','bazaarhub'); ?></h4>
          <p><?php _e('Items must be unused in original packaging.','bazaarhub'); ?></p>
        </div>
        <div class="bh-info-card">
          <i class="fas fa-money-bill-wave"></i>
          <h4><?php _e('Full Refund','bazaarhub'); ?></h4>
          <p><?php _e('Refund processed within 5–7 business days.','bazaarhub'); ?></p>
        </div>
      </div>

      <div class="bh-page-body-content">
        <?php if (have_posts()): while (have_posts()): the_post();
          $content = get_the_content();
          if ($content): the_content();
          else: ?>
          <h2><?php _e('Return Eligibility','bazaarhub'); ?></h2>
          <p><?php _e('To be eligible for a return, the item must be unused, in the same condition that you received it, and in its original packaging with all tags and accessories intact.','bazaarhub'); ?></p>

          <h2><?php _e('How to Initiate a Return','bazaarhub'); ?></h2>
          <ul>
            <li><?php _e('Contact us within 7 days of receiving your order.','bazaarhub'); ?></li>
            <li><?php _e('Email us at support@modhubazarshop.com with your order number.','bazaarhub'); ?></li>
            <li><?php _e('Our team will provide a return authorization and pickup details.','bazaarhub'); ?></li>
            <li><?php _e('Once the item is received and inspected, we will process your refund.','bazaarhub'); ?></li>
          </ul>

          <h2><?php _e('Non-Returnable Items','bazaarhub'); ?></h2>
          <ul>
            <li><?php _e('Perishable goods (food, flowers, etc.)','bazaarhub'); ?></li>
            <li><?php _e('Downloadable software or digital products','bazaarhub'); ?></li>
            <li><?php _e('Personal care and hygiene products once opened','bazaarhub'); ?></li>
            <li><?php _e('Items marked as "Final Sale" or "Non-Returnable"','bazaarhub'); ?></li>
          </ul>

          <h2><?php _e('Refund Process','bazaarhub'); ?></h2>
          <p><?php _e('Once your returned item is received and inspected, we will notify you by email. If approved, your refund will be processed to your original payment method within 5–7 business days.','bazaarhub'); ?></p>

          <h2><?php _e('Exchange Policy','bazaarhub'); ?></h2>
          <p><?php _e('We replace items only if they are defective or damaged. If you need to exchange an item for the same product, contact us at','bazaarhub'); ?> <a href="mailto:support@modhubazarshop.com">support@modhubazarshop.com</a>.</p>
          <?php endif; endwhile; endif; ?>
      </div>

    </div>
  </div>
</div>
<?php get_footer(); ?>
