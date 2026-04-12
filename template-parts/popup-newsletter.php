<?php
/**
 * Newsletter Popup
 * Controlled via Appearance → Customize → Newsletter Popup
 */

// Don't render if disabled in Customizer
if ( ! get_theme_mod('popup_enable', 1) ) return;

// Don't render if already dismissed today
if ( isset($_COOKIE['bh_popup_dismissed']) ) return;

$delay    = (int) get_theme_mod('popup_delay',    3);
$tag      = get_theme_mod('popup_tag',      'EXCLUSIVE OFFER');
$title    = get_theme_mod('popup_title',    'Want Access to Discounts & Deals?');
$discount = get_theme_mod('popup_discount', '25% OFF');
$btn_text = get_theme_mod('popup_btn',      'Subscribe');
$note     = get_theme_mod('popup_note',     '* Free delivery on first order. No spam, unsubscribe anytime.');
$img              = get_theme_mod('popup_image',            '');
$placeholder_icon = get_theme_mod('popup_placeholder_icon', 'fas fa-leaf');
$placeholder_text = get_theme_mod('popup_placeholder_text', 'Fresh &amp; Organic');
?>
<div class="bh-popup-overlay hidden" id="bh-popup-overlay" data-delay="<?php echo esc_attr($delay); ?>">
  <div class="bh-popup" id="bh-popup" role="dialog" aria-modal="true" aria-labelledby="bh-popup-heading">
    <button class="bh-popup__close" id="bh-popup-close" aria-label="<?php esc_attr_e('Close popup','bazaarhub'); ?>">
      <i class="fas fa-times"></i>
    </button>

    <div class="bh-popup__left">
      <div class="bh-popup__img-wrap">
        <?php if ($img): ?>
          <img src="<?php echo esc_url($img); ?>" alt="<?php esc_attr_e('Newsletter offer','bazaarhub'); ?>">
        <?php else: ?>
          <div class="bh-popup__img-placeholder">
            <i class="<?php echo esc_attr($placeholder_icon); ?>"></i>
            <span><?php echo esc_html($placeholder_text); ?></span>
          </div>
        <?php endif; ?>
      </div>
    </div>

    <div class="bh-popup__right">
      <?php if ($tag): ?>
      <span class="bh-popup__tag"><?php echo esc_html($tag); ?></span>
      <?php endif; ?>
      <h2 class="bh-popup__title" id="bh-popup-heading">
        <?php echo esc_html($title); ?>
      </h2>
      <p class="bh-popup__sub">
        <?php _e('Subscribe today and get','bazaarhub'); ?> <strong><?php echo esc_html($discount); ?></strong> <?php _e('your first order. Limited time offer.','bazaarhub'); ?>
      </p>
      <form class="bh-popup__form" id="bh-popup-form" novalidate>
        <input type="email" name="email" placeholder="<?php esc_attr_e('Your Email Address','bazaarhub'); ?>" required class="bh-popup__input" autocomplete="email">
        <button type="submit" class="bh-popup__btn"><?php echo esc_html($btn_text); ?> <i class="fas fa-arrow-right"></i></button>
      </form>
      <?php if ($note): ?>
      <p class="bh-popup__note"><?php echo esc_html($note); ?></p>
      <?php endif; ?>
      <label class="bh-popup__skip">
        <input type="checkbox" id="bh-popup-skip">
        <?php _e("Don't show this again",'bazaarhub'); ?>
      </label>
    </div>

  </div>
</div>
