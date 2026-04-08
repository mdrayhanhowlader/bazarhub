<?php
defined('ABSPATH') || exit;

function bazaarhub_get_compare_list() {
    if(!session_id()) session_start();
    return isset($_SESSION['bh_compare']) ? (array)$_SESSION['bh_compare'] : [];
}
function bazaarhub_compare_count() { return count(bazaarhub_get_compare_list()); }
function bazaarhub_compare_url() {
    $pid = get_option('bazaarhub_compare_page_id');
    return $pid ? get_permalink($pid) : home_url('/compare');
}

// Create compare page on activation
function bazaarhub_create_compare_page() {
    if(!get_option('bazaarhub_compare_page_id')) {
        $id = wp_insert_post(['post_title'=>'Compare Products','post_name'=>'compare',
            'post_content'=>'[bazaarhub_compare]','post_status'=>'publish','post_type'=>'page']);
        if($id) update_option('bazaarhub_compare_page_id', $id);
    }
}
add_action('after_switch_theme','bazaarhub_create_compare_page');

// AJAX toggle
function bazaarhub_toggle_compare() {
    if(!session_id()) session_start();
    check_ajax_referer('bazaarhub_nonce','nonce');
    $pid = absint($_POST['product_id'] ?? 0);
    $list = bazaarhub_get_compare_list();
    if(in_array($pid, $list)) {
        $list = array_values(array_diff($list, [$pid]));
        $action = 'removed';
    } else {
        if(count($list) >= 4) { wp_send_json_error(['message'=>__('Max 4 products to compare.','bazaarhub')]); }
        $list[] = $pid; $action = 'added';
    }
    $_SESSION['bh_compare'] = $list;
    wp_send_json_success(['action'=>$action,'count'=>count($list)]);
}
add_action('wp_ajax_bazaarhub_toggle_compare','bazaarhub_toggle_compare');
add_action('wp_ajax_nopriv_bazaarhub_toggle_compare','bazaarhub_toggle_compare');

// Compare shortcode
function bazaarhub_compare_shortcode() {
    $list = bazaarhub_get_compare_list();
    ob_start();
    if(empty($list)) {
        echo '<div class="bh-compare-empty"><i class="fas fa-exchange-alt"></i><p>'.__('No products to compare.','bazaarhub').'</p><a href="'.get_permalink(wc_get_page_id('shop')).'" class="bh-btn bh-btn--green">'.__('Continue Shopping','bazaarhub').'</a></div>';
        return ob_get_clean();
    }
    $fields = ['price','rating','availability','weight','dimensions','sku'];
    echo '<div class="bh-compare-wrap"><div class="bh-compare-table-wrap"><table class="bh-compare-table">';
    // Header row
    echo '<thead><tr><th class="bh-compare-label">'.__('Product','bazaarhub').'</th>';
    foreach($list as $pid) {
        $p = wc_get_product($pid);
        if(!$p) continue;
        echo '<th><div class="bh-compare-product">';
        echo '<a href="'.get_permalink($pid).'" class="bh-compare-product__img">'.get_the_post_thumbnail($pid,'thumbnail').'</a>';
        echo '<a href="'.get_permalink($pid).'" class="bh-compare-product__name">'.$p->get_name().'</a>';
        echo '<button class="bh-compare-remove" data-pid="'.$pid.'"><i class="fas fa-times"></i></button>';
        echo '</div></th>';
    }
    echo '</tr></thead><tbody>';
    // Price
    echo '<tr><td class="bh-compare-label">'.__('Price','bazaarhub').'</td>';
    foreach($list as $pid) { $p=wc_get_product($pid); echo '<td>'.($p?$p->get_price_html():'—').'</td>'; }
    echo '</tr>';
    // Rating
    echo '<tr><td class="bh-compare-label">'.__('Rating','bazaarhub').'</td>';
    foreach($list as $pid) { $p=wc_get_product($pid); echo '<td>'.($p?wc_get_rating_html($p->get_average_rating()).'('.$p->get_review_count().')':'—').'</td>'; }
    echo '</tr>';
    // Stock
    echo '<tr><td class="bh-compare-label">'.__('Availability','bazaarhub').'</td>';
    foreach($list as $pid) { $p=wc_get_product($pid); echo '<td>'.($p?($p->is_in_stock()?'<span class="bh-in-stock">In Stock</span>':'<span class="bh-out-stock">Out of Stock</span>'):'—').'</td>'; }
    echo '</tr>';
    // SKU
    echo '<tr><td class="bh-compare-label">'.__('SKU','bazaarhub').'</td>';
    foreach($list as $pid) { $p=wc_get_product($pid); echo '<td>'.($p&&$p->get_sku()?$p->get_sku():'—').'</td>'; }
    echo '</tr>';
    // Add to Cart
    echo '<tr><td class="bh-compare-label">'.__('Action','bazaarhub').'</td>';
    foreach($list as $pid) { echo '<td><button class="bh-btn bh-btn--green bh-btn--sm bh-add-to-cart" data-pid="'.$pid.'"><i class="fas fa-cart-plus"></i> Add to Cart</button></td>'; }
    echo '</tr>';
    echo '</tbody></table></div></div>';
    return ob_get_clean();
}
add_shortcode('bazaarhub_compare','bazaarhub_compare_shortcode');

// Session start early
add_action('init', function(){ if(!session_id()) session_start(); }, 1);
