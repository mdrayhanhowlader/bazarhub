<?php
if ( ! defined('ABSPATH') ) exit;

// Add wishlist page on theme activation
function bazaarhub_create_wishlist_page() {
    if ( ! get_option('bazaarhub_wishlist_page_id') ) {
        $page_id = wp_insert_post([
            'post_title'   => 'My Wishlist',
            'post_name'    => 'wishlist',
            'post_content' => '[bazaarhub_wishlist]',
            'post_status'  => 'publish',
            'post_type'    => 'page',
        ]);
        if ( $page_id ) update_option('bazaarhub_wishlist_page_id', $page_id);
    }
}
add_action( 'after_switch_theme', 'bazaarhub_create_wishlist_page' );

function bazaarhub_wishlist_url() {
    $pid = get_option('bazaarhub_wishlist_page_id');
    return $pid ? get_permalink($pid) : home_url('/wishlist');
}

// Shortcode [bazaarhub_wishlist]
function bazaarhub_wishlist_shortcode() {
    ob_start();
    if ( ! is_user_logged_in() ) {
        echo '<div class="bh-wishlist-login"><p>' . __('Please <a href="'.wp_login_url(get_permalink()).'">log in</a> to view your wishlist.','bazaarhub') . '</p></div>';
        return ob_get_clean();
    }
    $wishlist = get_user_meta(get_current_user_id(), 'bazaarhub_wishlist', true);
    if ( empty($wishlist) || ! is_array($wishlist) ) {
        echo '<div class="bh-wishlist-empty"><i class="far fa-heart"></i><p>'.__('Your wishlist is empty.','bazaarhub').'</p><a href="'.get_permalink(wc_get_page_id('shop')).'" class="bh-btn">'.__('Continue Shopping','bazaarhub').'</a></div>';
        return ob_get_clean();
    }
    echo '<div class="bh-wishlist-grid">';
    foreach ( $wishlist as $pid ) {
        $product = wc_get_product($pid);
        if ( ! $product ) continue;
        echo '<div class="bh-product-card" data-pid="'.$pid.'">';
        echo '<div class="bh-product-card__img"><a href="'.get_permalink($pid).'">'.get_the_post_thumbnail($pid,'bazaarhub-product-card').'</a>';
        echo '<button class="bh-wishlist-btn active" data-pid="'.$pid.'"><i class="fas fa-heart"></i></button></div>';
        echo '<div class="bh-product-card__body">';
        echo '<h4 class="bh-product-card__title"><a href="'.get_permalink($pid).'">'.$product->get_name().'</a></h4>';
        echo '<div class="bh-product-card__price">'.$product->get_price_html().'</div>';
        echo '<button class="bh-btn bh-btn--green bh-add-to-cart" data-pid="'.$pid.'">'.__('Add to Cart','bazaarhub').'</button>';
        echo '</div></div>';
    }
    echo '</div>';
    return ob_get_clean();
}
add_shortcode( 'bazaarhub_wishlist', 'bazaarhub_wishlist_shortcode' );
