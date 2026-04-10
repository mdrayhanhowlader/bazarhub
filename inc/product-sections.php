<?php
/**
 * BazaarHub — Product Homepage Sections
 *
 * Adds a meta box on the WooCommerce product edit screen with checkboxes
 * to assign products to homepage sections: Hot Deal, Special Deal, Today's Pick.
 *
 * Best Sellers = automatic (WC total_sales meta)
 * New Arrivals = automatic (post date, last 30 days)
 * Featured     = WooCommerce built-in "Featured" checkbox (already exists)
 */
defined('ABSPATH') || exit;

/* ── 1. Register the meta box ── */
add_action('add_meta_boxes', function() {
    add_meta_box(
        'bh_product_sections',
        __('🏷️ Homepage Sections','bazaarhub'),
        'bh_product_sections_callback',
        'product',
        'side',
        'high'
    );
});

function bh_product_sections_callback($post) {
    wp_nonce_field('bh_product_sections_save', 'bh_product_sections_nonce');

    $sections = [
        'bh_hot_deal'     => [
            'label' => __('🔥 Hot Deal','bazaarhub'),
            'desc'  => __('Shows in "Hot Deals" section on homepage','bazaarhub'),
            'color' => '#e53935',
        ],
        'bh_special_deal' => [
            'label' => __('⚡ Special Deal','bazaarhub'),
            'desc'  => __('Shows in "Special Deals" offer banners','bazaarhub'),
            'color' => '#1565c0',
        ],
        'bh_todays_pick'  => [
            'label' => __('⭐ Today\'s Pick','bazaarhub'),
            'desc'  => __('Shows in "Today\'s Picks" section','bazaarhub'),
            'color' => '#f57f17',
        ],
    ];

    echo '<div style="display:flex;flex-direction:column;gap:10px;margin-top:4px;">';
    foreach ($sections as $key => $section) {
        $checked = get_post_meta($post->ID, $key, true) ? 'checked' : '';
        echo '<label style="display:flex;align-items:flex-start;gap:8px;cursor:pointer;padding:8px 10px;border-radius:6px;background:#fafafa;border:1px solid #e0e0e0;">';
        echo '<input type="checkbox" name="' . esc_attr($key) . '" value="1" ' . $checked . ' style="margin-top:2px;accent-color:' . esc_attr($section['color']) . ';width:15px;height:15px;">';
        echo '<span><strong style="color:' . esc_attr($section['color']) . ';font-size:13px;">' . esc_html($section['label']) . '</strong><br>';
        echo '<small style="color:#888;font-size:11px;">' . esc_html($section['desc']) . '</small></span>';
        echo '</label>';
    }
    echo '</div>';
    echo '<p style="margin:10px 0 0;padding:8px;background:#e8f5e9;border-radius:6px;font-size:11.5px;color:#2e7d32;line-height:1.5;">';
    echo '<strong>Auto-sections:</strong><br>';
    echo '📈 <em>Best Sellers</em> — most sold products (automatic)<br>';
    echo '🆕 <em>New Arrivals</em> — added in last 30 days (automatic)<br>';
    echo '✅ <em>Featured</em> — use WooCommerce "Featured" checkbox above';
    echo '</p>';
}

/* ── 2. Save meta box values ── */
add_action('save_post_product', function($post_id) {
    if (!isset($_POST['bh_product_sections_nonce'])) return;
    if (!wp_verify_nonce($_POST['bh_product_sections_nonce'], 'bh_product_sections_save')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    $fields = ['bh_hot_deal', 'bh_special_deal', 'bh_todays_pick'];
    foreach ($fields as $field) {
        if (!empty($_POST[$field])) {
            update_post_meta($post_id, $field, '1');
        } else {
            delete_post_meta($post_id, $field);
        }
    }
});

/* ── 3. Helper: get products for a section ── */
function bh_get_section_products($meta_key, $count = 8) {
    return new WP_Query([
        'post_type'      => 'product',
        'post_status'    => 'publish',
        'posts_per_page' => $count,
        'orderby'        => 'date',
        'order'          => 'DESC',
        'meta_query'     => [[
            'key'     => sanitize_key($meta_key),
            'value'   => '1',
            'compare' => '=',
        ]],
    ]);
}
