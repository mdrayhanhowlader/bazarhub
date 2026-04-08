<?php
/**
 * Mini-cart — BazaarHub Custom Template
 * Chaldal-style: [img] [name + qty] [remove]
 */
defined('ABSPATH') || exit;

do_action('woocommerce_before_mini_cart');
?>

<ul class="woocommerce-mini-cart cart_list product_list_widget bh-minicart-list">
<?php if (!WC()->cart->is_empty()) :
    foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) :
        $_product   = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
        $product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);

        if (!$_product || !$_product->exists() || $cart_item['quantity'] <= 0) continue;
        if (!apply_filters('woocommerce_widget_cart_item_visible', true, $cart_item, $cart_item_key)) continue;

        $product_name      = apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key);
        $thumbnail         = apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image('woocommerce_thumbnail'), $cart_item, $cart_item_key);
        $product_price     = apply_filters('woocommerce_cart_item_price', WC()->cart->get_product_price($_product), $cart_item, $cart_item_key);
        $product_permalink = apply_filters('woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink($cart_item) : '', $cart_item, $cart_item_key);
        $qty               = $cart_item['quantity'];
        $remove_url        = esc_url(wc_get_cart_remove_url($cart_item_key));
        $item_class        = esc_attr(apply_filters('woocommerce_mini_cart_item_class', 'mini_cart_item', $cart_item, $cart_item_key));
?>
    <li class="woocommerce-mini-cart-item <?php echo $item_class; ?> bh-cart-item">

        <?php /* Product image */ ?>
        <div class="bh-cart-item__img">
            <?php if ($product_permalink) : ?>
                <a href="<?php echo esc_url($product_permalink); ?>" tabindex="-1">
                    <?php echo $thumbnail; ?>
                </a>
            <?php else : ?>
                <?php echo $thumbnail; ?>
            <?php endif; ?>
        </div>

        <?php /* Name + quantity/price */ ?>
        <div class="bh-cart-item__info">
            <?php if ($product_permalink) : ?>
                <a class="bh-cart-item__name" href="<?php echo esc_url($product_permalink); ?>">
                    <?php echo esc_html($product_name); ?>
                </a>
            <?php else : ?>
                <span class="bh-cart-item__name"><?php echo esc_html($product_name); ?></span>
            <?php endif; ?>
            <div class="bh-cart-item__meta">
                <span class="bh-cart-item__qty"><?php echo esc_html($qty); ?> &times;</span>
                <span class="bh-cart-item__price"><?php echo $product_price; ?></span>
            </div>
        </div>

        <?php /* Remove button */ ?>
        <?php echo apply_filters('woocommerce_cart_item_remove_link', sprintf(
            '<a href="%s" class="remove remove_from_cart_button bh-cart-item__remove" aria-label="%s" data-product_id="%s" data-cart_item_key="%s" data-product_sku="%s">&times;</a>',
            $remove_url,
            esc_attr__('Remove this item', 'woocommerce'),
            esc_attr($product_id),
            esc_attr($cart_item_key),
            esc_attr($_product->get_sku())
        ), $cart_item_key); ?>

    </li>
<?php
    endforeach;
else : ?>
    <li class="woocommerce-mini-cart__empty-message bh-cart-empty">
        <i class="fas fa-shopping-cart"></i>
        <span><?php esc_html_e('Your cart is empty.', 'woocommerce'); ?></span>
    </li>
<?php endif;
do_action('woocommerce_mini_cart_contents');
?>
</ul>

<?php if (!WC()->cart->is_empty()) : ?>

<?php /* Subtotal */ ?>
<div class="bh-cart-subtotal">
    <span class="bh-cart-subtotal__label"><?php esc_html_e('Subtotal', 'woocommerce'); ?></span>
    <span class="bh-cart-subtotal__amount"><?php echo WC()->cart->get_cart_subtotal(); ?></span>
</div>

<?php do_action('woocommerce_widget_shopping_cart_before_buttons'); ?>

<div class="bh-cart-actions">
    <?php do_action('woocommerce_widget_shopping_cart_buttons'); ?>
</div>

<?php endif; ?>

<?php do_action('woocommerce_after_mini_cart'); ?>
