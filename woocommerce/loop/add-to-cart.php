<?php defined('ABSPATH') || exit;
global $product;
echo '<button class="bh-btn bh-btn--green bh-add-to-cart" data-pid="'.esc_attr($product->get_id()).'">
  <i class="fas fa-cart-plus"></i> '.esc_html__('Add to Cart','bazaarhub').'
</button>';
