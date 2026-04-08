<?php
if ( ! defined('ABSPATH') ) exit;

function bazaarhub_get_option( $key, $default = '' ) {
    return get_theme_mod( $key, $default );
}

function bazaarhub_top_bar_text() {
    return bazaarhub_get_option('top_bar_text', '🚚 Free Delivery on Orders Over ৳500 | Call: 01700-000000');
}

function bazaarhub_get_cart_count() {
    if ( class_exists('WooCommerce') ) return WC()->cart->get_cart_contents_count();
    return 0;
}

function bazaarhub_get_cart_total() {
    if ( class_exists('WooCommerce') ) return WC()->cart->get_cart_total();
    return '';
}

function bazaarhub_get_wishlist_count() {
    $wishlist = get_user_meta( get_current_user_id(), 'bazaarhub_wishlist', true );
    return is_array($wishlist) ? count($wishlist) : 0;
}

function bazaarhub_is_in_wishlist( $product_id ) {
    $wishlist = get_user_meta( get_current_user_id(), 'bazaarhub_wishlist', true );
    return is_array($wishlist) && in_array($product_id, $wishlist);
}

function bazaarhub_product_badge( $product ) {
    $output = '';
    if ( $product->is_on_sale() ) {
        $regular = (float) $product->get_regular_price();
        $sale    = (float) $product->get_sale_price();
        if ( $regular > 0 ) {
            $pct = round( ( ($regular - $sale) / $regular ) * 100 );
            $output .= '<span class="bh-badge bh-badge--sale">-' . $pct . '%</span>';
        }
    }
    if ( $product->is_featured() )  $output .= '<span class="bh-badge bh-badge--hot">HOT</span>';
    if ( $product->is_in_stock() && $product->get_stock_quantity() && $product->get_stock_quantity() < 5 )
        $output .= '<span class="bh-badge bh-badge--low">Low Stock</span>';
    return $output;
}

function bazaarhub_render_stars( $rating ) {
    $output = '<span class="bh-stars">';
    for ( $i = 1; $i <= 5; $i++ ) {
        if ( $i <= $rating ) $output .= '<i class="fas fa-star"></i>';
        elseif ( $i - 0.5 <= $rating ) $output .= '<i class="fas fa-star-half-alt"></i>';
        else $output .= '<i class="far fa-star"></i>';
    }
    $output .= '</span>';
    return $output;
}
