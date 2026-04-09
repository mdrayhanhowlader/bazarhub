<?php
/**
 * Product loop sale/badge flash — BazaarHub custom
 */
defined('ABSPATH') || exit;
global $post, $product;

if ( ! is_a($product, 'WC_Product') ) return;

$is_sale     = $product->is_on_sale();
$is_featured = $product->is_featured();

// Detect "new" — added within last 30 days
$date_created = $product->get_date_created();
$is_new = false;
if ($date_created) {
    $days_old = (time() - $date_created->getTimestamp()) / DAY_IN_SECONDS;
    $is_new   = ($days_old <= 30);
}

// Determine badge type (priority: hot > flash > featured > new)
if ($is_sale && $is_featured) {
    $type  = 'hot';
    $icon  = 'fas fa-fire';
    $label = __('Hot Deal','bazaarhub');
} elseif ($is_sale) {
    $type  = 'flash';
    $icon  = 'fas fa-bolt';
    $label = __('Flash Sale','bazaarhub');
} elseif ($is_featured) {
    $type  = 'featured';
    $icon  = 'fas fa-star';
    $label = __('Featured','bazaarhub');
} elseif ($is_new) {
    $type  = 'new';
    $icon  = 'fas fa-certificate';
    $label = __('New Arrival','bazaarhub');
} else {
    return; // no badge
}

// Discount percentage (only for sale)
$discount_html = '';
if ($is_sale) {
    $regular = (float) $product->get_regular_price();
    $sale    = (float) $product->get_price();
    if ($regular > 0 && $sale < $regular) {
        $pct = round((($regular - $sale) / $regular) * 100);
        $discount_html = '<span class="bh-pbadge__pct">-' . $pct . '%</span>';
    }
}
?>
<div class="bh-pbadge bh-pbadge--<?php echo esc_attr($type); ?>">
  <span class="bh-pbadge__shimmer"></span>
  <i class="<?php echo esc_attr($icon); ?> bh-pbadge__icon"></i>
  <span class="bh-pbadge__label"><?php echo esc_html($label); ?></span>
  <?php echo $discount_html; ?>
</div>
