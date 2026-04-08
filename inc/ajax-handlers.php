<?php
if ( ! defined('ABSPATH') ) exit;

// ── Wishlist Toggle ──────────────────────────────────────
function bazaarhub_toggle_wishlist() {
    check_ajax_referer('bazaarhub_nonce','nonce');
    if ( ! is_user_logged_in() ) {
        wp_send_json_error(['message'=>__('Please log in to use wishlist.','bazaarhub'),'login_required'=>true]);
    }
    $pid      = absint($_POST['product_id'] ?? 0);
    $uid      = get_current_user_id();
    $wishlist = get_user_meta($uid,'bazaarhub_wishlist',true) ?: [];
    if ( ! is_array($wishlist) ) $wishlist = [];
    if ( in_array($pid,$wishlist) ) {
        $wishlist = array_values(array_diff($wishlist,[$pid]));
        $action   = 'removed';
    } else {
        $wishlist[] = $pid;
        $action     = 'added';
    }
    update_user_meta($uid,'bazaarhub_wishlist',$wishlist);
    wp_send_json_success(['action'=>$action,'count'=>count($wishlist)]);
}
add_action('wp_ajax_bazaarhub_toggle_wishlist',       'bazaarhub_toggle_wishlist');
add_action('wp_ajax_nopriv_bazaarhub_toggle_wishlist','bazaarhub_toggle_wishlist');

// ── Mini Cart Refresh ────────────────────────────────────
function bazaarhub_refresh_mini_cart() {
    check_ajax_referer('bazaarhub_nonce','nonce');
    ob_start();
    woocommerce_mini_cart();
    wp_send_json_success([
        'html'  => ob_get_clean(),
        'count' => WC()->cart->get_cart_contents_count(),
        'total' => WC()->cart->get_cart_total(),
    ]);
}
add_action('wp_ajax_bazaarhub_refresh_mini_cart',       'bazaarhub_refresh_mini_cart');
add_action('wp_ajax_nopriv_bazaarhub_refresh_mini_cart','bazaarhub_refresh_mini_cart');

// ── Quick View ────────────────────────────────────────────
function bazaarhub_quick_view() {
    check_ajax_referer('bazaarhub_nonce','nonce');
    $pid     = absint($_POST['product_id'] ?? 0);
    $product = wc_get_product($pid);
    if ( ! $product ) { wp_send_json_error(); }
    ob_start(); ?>
    <div class="bh-quickview">
        <div class="bh-quickview__gallery">
            <?php echo get_the_post_thumbnail($pid,'bazaarhub-product-card'); ?>
        </div>
        <div class="bh-quickview__info">
            <h2><?php echo esc_html($product->get_name()); ?></h2>
            <div class="bh-quickview__price"><?php echo $product->get_price_html(); ?></div>
            <div class="bh-quickview__rating"><?php echo bazaarhub_render_stars($product->get_average_rating()); ?></div>
            <div class="bh-quickview__desc"><?php echo wp_trim_words($product->get_description(),30); ?></div>
            <?php woocommerce_template_single_add_to_cart(); ?>
            <a href="<?php echo get_permalink($pid); ?>" class="bh-quickview__detail-link"><?php _e('View Full Details','bazaarhub'); ?></a>
        </div>
    </div>
    <?php
    wp_send_json_success(['html'=>ob_get_clean()]);
}
add_action('wp_ajax_bazaarhub_quick_view',       'bazaarhub_quick_view');
add_action('wp_ajax_nopriv_bazaarhub_quick_view','bazaarhub_quick_view');

// ── Product Search ────────────────────────────────────────
function bazaarhub_live_search() {
    check_ajax_referer('bazaarhub_nonce','nonce');
    $term = sanitize_text_field($_POST['term'] ?? '');
    if ( strlen($term) < 2 ) { wp_send_json_success(['html'=>'']); }
    $query = new WP_Query([
        'post_type'      => 'product',
        's'              => $term,
        'posts_per_page' => 6,
        'post_status'    => 'publish',
    ]);
    ob_start();
    if ($query->have_posts()) {
        echo '<ul class="bh-search-results">';
        while ($query->have_posts()) { $query->the_post();
            $p = wc_get_product(get_the_ID());
            echo '<li><a href="'.get_permalink().'">';
            echo get_the_post_thumbnail(get_the_ID(),[50,50]);
            echo '<span class="bh-sr-title">'.get_the_title().'</span>';
            echo '<span class="bh-sr-price">'.$p->get_price_html().'</span>';
            echo '</a></li>';
        }
        echo '</ul>';
        echo '<a href="'.home_url('/?s='.$term.'&post_type=product').'" class="bh-search-all">'.__('See all results','bazaarhub').'</a>';
    } else {
        echo '<p class="bh-search-empty">'.__('No products found.','bazaarhub').'</p>';
    }
    wp_reset_postdata();
    wp_send_json_success(['html'=>ob_get_clean()]);
}
add_action('wp_ajax_bazaarhub_live_search',       'bazaarhub_live_search');
add_action('wp_ajax_nopriv_bazaarhub_live_search','bazaarhub_live_search');
