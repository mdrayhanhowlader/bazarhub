<?php defined('ABSPATH') || exit;
if(!isset($product)) $product = wc_get_product(get_the_ID());
$stock_max = ($product && $product->managing_stock()) ? $product->get_stock_quantity() : $max_value;
$data_max  = (0 < $stock_max) ? $stock_max : 9999;
?>
<div class="bh-qty-wrap">
  <button type="button" class="bh-qty-btn bh-qty-minus" aria-label="Decrease"><i class="fas fa-minus"></i></button>
  <input type="number"
    id="<?php echo esc_attr($input_id); ?>"
    class="input-qty <?php echo esc_attr(implode(' ',(array)$classes)); ?>"
    step="<?php echo esc_attr($step); ?>"
    min="<?php echo esc_attr($min_value); ?>"
    max="<?php echo esc_attr(0 < $stock_max ? $stock_max : ''); ?>"
    name="<?php echo esc_attr($input_name); ?>"
    value="<?php echo esc_attr($input_value); ?>"
    title="Qty" size="4"
    aria-labelledby="<?php echo !empty($labelledby) ? esc_attr($labelledby) : ''; ?>"
  />
  <button type="button" class="bh-qty-btn bh-qty-plus" aria-label="Increase" data-max="<?php echo esc_attr($data_max); ?>"><i class="fas fa-plus"></i></button>
</div>
