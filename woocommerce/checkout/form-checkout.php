<?php
/**
 * Checkout Form — BazaarHub Custom
 */
defined('ABSPATH') || exit;

do_action('woocommerce_before_checkout_form', $checkout);

if (!$checkout->is_registration_enabled() && $checkout->is_registration_required() && !is_user_logged_in()) {
    echo esc_html(apply_filters('woocommerce_checkout_must_be_logged_in_message', __('You must be logged in to checkout.', 'woocommerce')));
    return;
}
?>

<!-- Checkout Hero -->
<div class="bh-checkout-hero">
  <div class="bh-checkout-hero__steps">
    <span class="bh-checkout-step bh-checkout-step--done">
      <i class="fas fa-shopping-cart"></i> <?php _e('Cart','bazaarhub'); ?>
    </span>
    <span class="bh-checkout-step__line"></span>
    <span class="bh-checkout-step bh-checkout-step--active">
      <i class="fas fa-credit-card"></i> <?php _e('Checkout','bazaarhub'); ?>
    </span>
    <span class="bh-checkout-step__line"></span>
    <span class="bh-checkout-step bh-checkout-step--pending">
      <i class="fas fa-check-circle"></i> <?php _e('Confirmed','bazaarhub'); ?>
    </span>
  </div>
</div>

<form name="checkout" method="post" class="checkout woocommerce-checkout bh-checkout-form"
  action="<?php echo esc_url(wc_get_checkout_url()); ?>"
  enctype="multipart/form-data"
  aria-label="<?php esc_attr_e('Checkout','woocommerce'); ?>">

  <div class="bh-checkout-grid">

    <!-- ── LEFT: Customer Details ── -->
    <div class="bh-checkout-left">

      <?php do_action('woocommerce_checkout_before_customer_details'); ?>

      <!-- Coupon -->
      <?php if (wc_coupons_enabled()): ?>
      <div class="bh-checkout-section">
        <div class="bh-checkout-section__head">
          <i class="fas fa-tag"></i>
          <h3><?php _e('Have a Coupon?','bazaarhub'); ?></h3>
        </div>
        <?php do_action('woocommerce_before_checkout_form', $checkout); ?>
      </div>
      <?php endif; ?>

      <!-- Billing -->
      <div class="bh-checkout-section">
        <div class="bh-checkout-section__head">
          <i class="fas fa-map-marker-alt"></i>
          <h3><?php _e('Billing Details','bazaarhub'); ?></h3>
        </div>
        <?php do_action('woocommerce_checkout_billing'); ?>
      </div>

      <!-- Shipping -->
      <?php if (WC()->cart->needs_shipping() && WC()->cart->show_shipping()): ?>
      <div class="bh-checkout-section">
        <div class="bh-checkout-section__head">
          <i class="fas fa-truck"></i>
          <h3><?php _e('Shipping Details','bazaarhub'); ?></h3>
        </div>
        <?php do_action('woocommerce_checkout_shipping'); ?>
      </div>
      <?php endif; ?>

      <!-- Additional Info -->
      <div class="bh-checkout-section">
        <div class="bh-checkout-section__head">
          <i class="fas fa-sticky-note"></i>
          <h3><?php _e('Additional Information','bazaarhub'); ?></h3>
        </div>
        <?php do_action('woocommerce_checkout_after_customer_details'); ?>
      </div>

    </div>

    <!-- ── RIGHT: Order Summary ── -->
    <div class="bh-checkout-right">
      <div class="bh-checkout-summary">

        <div class="bh-checkout-section__head">
          <i class="fas fa-receipt"></i>
          <h3><?php _e('Your Order','bazaarhub'); ?></h3>
        </div>

        <?php do_action('woocommerce_checkout_before_order_review_heading'); ?>
        <?php do_action('woocommerce_checkout_before_order_review'); ?>

        <div id="order_review" class="woocommerce-checkout-review-order">
          <?php do_action('woocommerce_checkout_order_review'); ?>
        </div>

        <?php do_action('woocommerce_checkout_after_order_review'); ?>

        <!-- Trust badges -->
        <div class="bh-checkout-trust">
          <span><i class="fas fa-shield-alt"></i> <?php _e('Secure Payment','bazaarhub'); ?></span>
          <span><i class="fas fa-truck"></i> <?php _e('Fast Delivery','bazaarhub'); ?></span>
          <span><i class="fas fa-undo"></i> <?php _e('Easy Returns','bazaarhub'); ?></span>
        </div>

      </div>
    </div>

  </div><!-- .bh-checkout-grid -->

</form>

<?php do_action('woocommerce_after_checkout_form', $checkout); ?>
