<?php
/**
 * Newsletter Popup
 * Server-side: don't even render HTML if dismissed today or permanently.
 * Client-side: main.js handles delay, show, and dismiss logic.
 */
if ( isset($_COOKIE['bh_popup_dismissed']) ) return;
?>
<div class="bh-popup-overlay hidden" id="bh-popup-overlay">
  <div class="bh-popup" id="bh-popup" role="dialog" aria-modal="true" aria-labelledby="bh-popup-heading">
    <button class="bh-popup__close" id="bh-popup-close" aria-label="<?php esc_attr_e('Close popup','bazaarhub'); ?>">
      <i class="fas fa-times"></i>
    </button>

    <div class="bh-popup__left">
      <div class="bh-popup__img-wrap">
        <?php $img = get_theme_mod('popup_image',''); ?>
        <?php if ($img): ?>
          <img src="<?php echo esc_url($img); ?>" alt="<?php esc_attr_e('Newsletter offer','bazaarhub'); ?>">
        <?php else: ?>
          <div class="bh-popup__img-placeholder">
            <i class="fas fa-leaf"></i>
            <span><?php _e('Fresh &amp; Organic','bazaarhub'); ?></span>
          </div>
        <?php endif; ?>
      </div>
    </div>

    <div class="bh-popup__right">
      <span class="bh-popup__tag"><?php _e('EXCLUSIVE OFFER','bazaarhub'); ?></span>
      <h2 class="bh-popup__title" id="bh-popup-heading">
        <?php _e('Want Access to','bazaarhub'); ?><br>
        <strong><?php _e('Discounts &amp; Deals?','bazaarhub'); ?></strong>
      </h2>
      <p class="bh-popup__sub">
        <?php _e('Subscribe today and get','bazaarhub'); ?> <strong><?php _e('25% OFF','bazaarhub'); ?></strong> <?php _e('your first order. Limited time offer.','bazaarhub'); ?>
      </p>
      <form class="bh-popup__form" id="bh-popup-form" novalidate>
        <input type="email" name="email" placeholder="<?php esc_attr_e('Your Email Address','bazaarhub'); ?>" required class="bh-popup__input" autocomplete="email">
        <button type="submit" class="bh-popup__btn"><?php _e('Subscribe','bazaarhub'); ?> <i class="fas fa-arrow-right"></i></button>
      </form>
      <p class="bh-popup__note"><?php _e('* Free delivery on first order. No spam, unsubscribe anytime.','bazaarhub'); ?></p>
      <label class="bh-popup__skip">
        <input type="checkbox" id="bh-popup-skip">
        <?php _e("Don't show this again",'bazaarhub'); ?>
      </label>
    </div>

  </div>
</div>
