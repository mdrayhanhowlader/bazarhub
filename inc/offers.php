<?php
/**
 * BazaarHub — Custom Offers System
 *
 * Dashboard → Offers → Create New Offer
 * Set title, start/end date, badge, icon, subtitle, product count.
 * Assign products to an offer from the product edit screen.
 * Active offers + upcoming offers both show on homepage with countdown.
 */
defined('ABSPATH') || exit;

/* ══════════════════════════════════════════
   1. Register Custom Post Type: bh_offer
   ══════════════════════════════════════════ */
add_action('init', function() {
    register_post_type('bh_offer', [
        'labels' => [
            'name'               => __('Offers','bazaarhub'),
            'singular_name'      => __('Offer','bazaarhub'),
            'add_new'            => __('Create New Offer','bazaarhub'),
            'add_new_item'       => __('Create New Offer','bazaarhub'),
            'edit_item'          => __('Edit Offer','bazaarhub'),
            'all_items'          => __('All Offers','bazaarhub'),
            'search_items'       => __('Search Offers','bazaarhub'),
            'not_found'          => __('No offers found.','bazaarhub'),
            'not_found_in_trash' => __('No offers found in trash.','bazaarhub'),
        ],
        'public'              => false,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'menu_icon'           => 'dashicons-tag',
        'menu_position'       => 26,
        'supports'            => ['title'],
        'rewrite'             => false,
        'capability_type'     => 'post',
    ]);
});

/* ══════════════════════════════════════════
   2. Offer Settings Meta Box
   ══════════════════════════════════════════ */
add_action('add_meta_boxes', function() {
    add_meta_box(
        'bh_offer_settings',
        __('🎯 Offer Settings','bazaarhub'),
        'bh_offer_meta_callback',
        'bh_offer',
        'normal',
        'high'
    );
});

function bh_offer_meta_callback($post) {
    wp_nonce_field('bh_offer_save', 'bh_offer_nonce');

    $start       = get_post_meta($post->ID, '_offer_start',       true);
    $end         = get_post_meta($post->ID, '_offer_end',         true);
    $badge       = get_post_meta($post->ID, '_offer_badge',       true) ?: 'Flash Sale';
    $badge_color = get_post_meta($post->ID, '_offer_badge_color', true) ?: 'red';
    $subtitle    = get_post_meta($post->ID, '_offer_subtitle',    true) ?: '';
    $count       = get_post_meta($post->ID, '_offer_count',       true) ?: 8;
    $view_url    = get_post_meta($post->ID, '_offer_view_url',    true) ?: '';
    $icon        = get_post_meta($post->ID, '_offer_icon',        true) ?: 'fas fa-fire';

    $now    = time();
    $s_val  = $start ? date('Y-m-d\TH:i', (int)$start) : '';
    $e_val  = $end   ? date('Y-m-d\TH:i', (int)$end)   : '';

    // Status indicator
    if ($start && $end) {
        if ($now < (int)$start) {
            $status = '<span style="background:#1565c0;color:#fff;padding:3px 10px;border-radius:4px;font-size:12px">⏳ Upcoming</span>';
        } elseif ($now <= (int)$end) {
            $status = '<span style="background:#2e7d32;color:#fff;padding:3px 10px;border-radius:4px;font-size:12px">🟢 Active Now</span>';
        } else {
            $status = '<span style="background:#999;color:#fff;padding:3px 10px;border-radius:4px;font-size:12px">✗ Expired</span>';
        }
    } else {
        $status = '<span style="background:#e65100;color:#fff;padding:3px 10px;border-radius:4px;font-size:12px">⚠ Set dates below</span>';
    }
    ?>
    <style>
    .bh-om-grid{display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-top:4px}
    .bh-om-grid label{display:block;font-weight:600;font-size:12px;margin-bottom:3px;color:#333}
    .bh-om-grid input[type=text],.bh-om-grid input[type=url],.bh-om-grid input[type=number],.bh-om-grid input[type=datetime-local],.bh-om-grid select{width:100%;padding:7px 10px;border:1.5px solid #ddd;border-radius:5px;font-size:13px;transition:border .2s}
    .bh-om-grid input:focus,.bh-om-grid select:focus{border-color:#2e7d32;outline:none}
    .bh-om-full{grid-column:span 2}
    .bh-om-info{margin:12px 0 0;padding:10px 14px;background:#f1f8e9;border-left:3px solid #66bb6a;font-size:12px;color:#555;border-radius:0 5px 5px 0;line-height:1.7}
    .bh-om-status{margin-bottom:12px}
    </style>

    <div class="bh-om-status"><?php echo $status; ?></div>

    <div class="bh-om-grid">
      <div>
        <label><?php _e('Start Date & Time','bazaarhub'); ?></label>
        <input type="datetime-local" name="offer_start" value="<?php echo esc_attr($s_val); ?>">
        <small style="color:#888;font-size:11px"><?php _e('Offer becomes Upcoming from this date','bazaarhub'); ?></small>
      </div>
      <div>
        <label><?php _e('End Date & Time','bazaarhub'); ?></label>
        <input type="datetime-local" name="offer_end" value="<?php echo esc_attr($e_val); ?>">
        <small style="color:#888;font-size:11px"><?php _e('Offer hides automatically after this','bazaarhub'); ?></small>
      </div>
      <div>
        <label><?php _e('Badge Text','bazaarhub'); ?></label>
        <input type="text" name="offer_badge" value="<?php echo esc_attr($badge); ?>" placeholder="Flash Sale">
      </div>
      <div>
        <label><?php _e('Badge Color / Theme','bazaarhub'); ?></label>
        <select name="offer_badge_color">
          <?php
          $colors = [
              'red'    => '🔴 Red — Flash Sale',
              'orange' => '🟠 Orange — Hot Deal',
              'blue'   => '🔵 Blue — Special Deal',
              'green'  => '🟢 Green — Fresh Deal',
              'purple' => '🟣 Purple — Mega Sale',
          ];
          foreach ($colors as $val => $lbl):
          ?>
          <option value="<?php echo esc_attr($val); ?>" <?php selected($badge_color, $val); ?>><?php echo esc_html($lbl); ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div>
        <label><?php _e('Icon (Font Awesome class)','bazaarhub'); ?></label>
        <input type="text" name="offer_icon" value="<?php echo esc_attr($icon); ?>" placeholder="fas fa-fire">
        <small style="color:#888;font-size:11px"><?php _e('e.g. fas fa-bolt, fas fa-star, fas fa-gift','bazaarhub'); ?></small>
      </div>
      <div>
        <label><?php _e('Products to Show','bazaarhub'); ?></label>
        <input type="number" name="offer_count" value="<?php echo esc_attr($count); ?>" min="1" max="24">
      </div>
      <div class="bh-om-full">
        <label><?php _e('Subtitle / Description','bazaarhub'); ?></label>
        <input type="text" name="offer_subtitle" value="<?php echo esc_attr($subtitle); ?>" placeholder="<?php esc_attr_e('Limited time — grab before they\'re gone!','bazaarhub'); ?>">
      </div>
      <div class="bh-om-full">
        <label><?php _e('View All URL (optional)','bazaarhub'); ?></label>
        <input type="url" name="offer_view_url" value="<?php echo esc_attr($view_url); ?>" placeholder="https://">
        <small style="color:#888;font-size:11px"><?php _e('Leave empty to link to Shop page','bazaarhub'); ?></small>
      </div>
    </div>

    <div class="bh-om-info">
      <strong>📋 How to use:</strong><br>
      1. Set Title → Set Start & End dates → Choose badge color → Save &amp; <strong>Publish</strong><br>
      2. Go to each <strong>Product → Edit</strong> → find "<strong>🎯 Assign to Offers</strong>" box → tick this offer<br>
      3. Done! The section appears automatically on homepage.<br>
      • <strong>Before start:</strong> shows as <em>Upcoming</em> with countdown to launch &amp; "Coming Soon" on products<br>
      • <strong>During offer:</strong> shows as <em>Active</em> with countdown to end<br>
      • <strong>After end date:</strong> automatically hidden — no action needed
    </div>
    <?php
}

/* ══════════════════════════════════════════
   3. Save Offer Meta
   ══════════════════════════════════════════ */
add_action('save_post_bh_offer', function($post_id) {
    if (!isset($_POST['bh_offer_nonce'])) return;
    if (!wp_verify_nonce($_POST['bh_offer_nonce'], 'bh_offer_save')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    $start = !empty($_POST['offer_start']) ? strtotime(sanitize_text_field(wp_unslash($_POST['offer_start']))) : 0;
    $end   = !empty($_POST['offer_end'])   ? strtotime(sanitize_text_field(wp_unslash($_POST['offer_end'])))   : 0;

    update_post_meta($post_id, '_offer_start',       (int)$start);
    update_post_meta($post_id, '_offer_end',         (int)$end);
    update_post_meta($post_id, '_offer_badge',       sanitize_text_field(wp_unslash($_POST['offer_badge']       ?? '')));
    update_post_meta($post_id, '_offer_badge_color', sanitize_text_field(wp_unslash($_POST['offer_badge_color'] ?? 'red')));
    update_post_meta($post_id, '_offer_subtitle',    sanitize_text_field(wp_unslash($_POST['offer_subtitle']    ?? '')));
    update_post_meta($post_id, '_offer_count',       absint($_POST['offer_count'] ?? 8));
    update_post_meta($post_id, '_offer_view_url',    esc_url_raw(wp_unslash($_POST['offer_view_url'] ?? '')));
    update_post_meta($post_id, '_offer_icon',        sanitize_text_field(wp_unslash($_POST['offer_icon'] ?? 'fas fa-fire')));
});

/* ══════════════════════════════════════════
   4. "Assign to Offers" Meta Box on Product
   ══════════════════════════════════════════ */
add_action('add_meta_boxes', function() {
    add_meta_box(
        'bh_product_offers',
        __('🎯 Assign to Offers','bazaarhub'),
        'bh_product_offers_callback',
        'product',
        'side',
        'high'
    );
});

function bh_product_offers_callback($post) {
    wp_nonce_field('bh_product_offers_save', 'bh_product_offers_nonce');

    // All non-expired offers
    $offers = get_posts([
        'post_type'   => 'bh_offer',
        'post_status' => 'publish',
        'numberposts' => 50,
        'orderby'     => 'meta_value_num',
        'meta_key'    => '_offer_start',
        'order'       => 'DESC',
        'meta_query'  => [[
            'key'     => '_offer_end',
            'value'   => time(),
            'type'    => 'NUMERIC',
            'compare' => '>',
        ]],
    ]);

    if (empty($offers)) {
        echo '<p style="font-size:12px;color:#888;margin:4px 0;line-height:1.6">';
        echo __('No active or upcoming offers exist yet.','bazaarhub') . '<br>';
        echo '<a href="' . esc_url(admin_url('post-new.php?post_type=bh_offer')) . '" style="color:#2e7d32;font-weight:600">' . __('+ Create an Offer','bazaarhub') . '</a>';
        echo '</p>';
        return;
    }

    $now = time();
    $badge_hex = [
        'red'    => '#e53935',
        'orange' => '#ff6f00',
        'blue'   => '#1565c0',
        'green'  => '#2e7d32',
        'purple' => '#6a1b9a',
    ];

    echo '<div style="display:flex;flex-direction:column;gap:7px;margin-top:4px;">';
    foreach ($offers as $offer) {
        $offer_id    = $offer->ID;
        $start       = (int)get_post_meta($offer_id, '_offer_start', true);
        $end         = (int)get_post_meta($offer_id, '_offer_end',   true);
        $badge       = get_post_meta($offer_id, '_offer_badge',       true) ?: 'Flash Sale';
        $badge_color = get_post_meta($offer_id, '_offer_badge_color', true) ?: 'red';
        $meta_key    = 'bh_offer_' . $offer_id;
        $checked     = get_post_meta($post->ID, $meta_key, true) ? 'checked' : '';
        $color       = $badge_hex[$badge_color] ?? '#e53935';

        if ($start > $now) {
            $status = '⏳ ' . __('Upcoming','bazaarhub') . ' — ' . date_i18n('d M, H:i', $start);
            $sc = '#1565c0';
        } else {
            $status = '🟢 ' . __('Active until','bazaarhub') . ' ' . date_i18n('d M, H:i', $end);
            $sc = '#2e7d32';
        }

        echo '<label style="display:flex;align-items:flex-start;gap:8px;cursor:pointer;padding:8px 10px;border-radius:6px;background:#fafafa;border:1px solid #e0e0e0;">';
        echo '<input type="checkbox" name="' . esc_attr($meta_key) . '" value="1" ' . $checked . ' style="margin-top:3px;accent-color:' . esc_attr($color) . ';width:14px;height:14px;flex-shrink:0">';
        echo '<span style="line-height:1.5">';
        echo '<strong style="color:' . esc_attr($color) . ';font-size:12.5px;display:block">' . esc_html($offer->post_title) . '</strong>';
        echo '<span style="display:inline-block;background:' . esc_attr($color) . ';color:#fff;font-size:10px;font-weight:700;padding:1px 7px;border-radius:3px;margin:2px 0 3px">' . esc_html($badge) . '</span><br>';
        echo '<small style="color:' . esc_attr($sc) . ';font-size:11px">' . esc_html($status) . '</small>';
        echo '</span>';
        echo '</label>';
    }
    echo '</div>';
    echo '<p style="margin:8px 0 0;font-size:11px;color:#888">';
    echo '<a href="' . esc_url(admin_url('edit.php?post_type=bh_offer')) . '">' . __('Manage all offers','bazaarhub') . '</a>';
    echo '</p>';
}

/* ══════════════════════════════════════════
   5. Save Product → Offer Assignments
   ══════════════════════════════════════════ */
add_action('save_post_product', function($post_id) {
    if (!isset($_POST['bh_product_offers_nonce'])) return;
    if (!wp_verify_nonce($_POST['bh_product_offers_nonce'], 'bh_product_offers_save')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    $all_offer_ids = get_posts([
        'post_type'   => 'bh_offer',
        'post_status' => ['publish', 'draft'],
        'numberposts' => -1,
        'fields'      => 'ids',
    ]);

    foreach ($all_offer_ids as $offer_id) {
        $meta_key = 'bh_offer_' . (int)$offer_id;
        if (!empty($_POST[$meta_key])) {
            update_post_meta($post_id, $meta_key, '1');
        } else {
            delete_post_meta($post_id, $meta_key);
        }
    }
}, 20);

/* ══════════════════════════════════════════
   6. Helpers
   ══════════════════════════════════════════ */

/**
 * Get products assigned to a specific offer
 */
function bh_get_offer_products($offer_id, $count = 8) {
    return new WP_Query([
        'post_type'      => 'product',
        'post_status'    => 'publish',
        'posts_per_page' => (int)$count,
        'orderby'        => 'date',
        'order'          => 'DESC',
        'meta_query'     => [[
            'key'     => 'bh_offer_' . (int)$offer_id,
            'value'   => '1',
            'compare' => '=',
        ]],
    ]);
}

/**
 * Get all active + upcoming offers for homepage
 * Active = start <= now <= end
 * Upcoming = start > now AND end > now
 */
function bh_get_homepage_offers() {
    return get_posts([
        'post_type'   => 'bh_offer',
        'post_status' => 'publish',
        'numberposts' => 10,
        'orderby'     => 'meta_value_num',
        'meta_key'    => '_offer_start',
        'order'       => 'ASC',
        'meta_query'  => [[
            'key'     => '_offer_end',
            'value'   => time(),
            'type'    => 'NUMERIC',
            'compare' => '>',
        ]],
    ]);
}

/* ══════════════════════════════════════════
   7. Admin Column: Status in Offer List Table
   ══════════════════════════════════════════ */
add_filter('manage_bh_offer_posts_columns', function($cols) {
    $new = [];
    foreach ($cols as $k => $v) {
        $new[$k] = $v;
        if ($k === 'title') {
            $new['offer_status'] = __('Status','bazaarhub');
            $new['offer_dates']  = __('Dates','bazaarhub');
            $new['offer_prods']  = __('Products','bazaarhub');
        }
    }
    return $new;
});

add_action('manage_bh_offer_posts_custom_column', function($col, $post_id) {
    $now   = time();
    $start = (int)get_post_meta($post_id, '_offer_start', true);
    $end   = (int)get_post_meta($post_id, '_offer_end',   true);
    $badge_color = get_post_meta($post_id, '_offer_badge_color', true) ?: 'red';
    $badge_hex = ['red'=>'#e53935','orange'=>'#ff6f00','blue'=>'#1565c0','green'=>'#2e7d32','purple'=>'#6a1b9a'];
    $color = $badge_hex[$badge_color] ?? '#e53935';

    if ($col === 'offer_status') {
        if (!$start || !$end) {
            echo '<span style="color:#f57c00;font-size:12px">⚠ Dates not set</span>';
        } elseif ($now < $start) {
            echo '<span style="background:#e3f2fd;color:#1565c0;padding:2px 8px;border-radius:4px;font-size:12px;font-weight:700">⏳ Upcoming</span>';
        } elseif ($now <= $end) {
            echo '<span style="background:#e8f5e9;color:#2e7d32;padding:2px 8px;border-radius:4px;font-size:12px;font-weight:700">🟢 Active</span>';
        } else {
            echo '<span style="background:#f5f5f5;color:#999;padding:2px 8px;border-radius:4px;font-size:12px">✗ Expired</span>';
        }
    }

    if ($col === 'offer_dates') {
        if ($start && $end) {
            echo '<span style="font-size:12px;color:#555">';
            echo date_i18n('d M Y, H:i', $start) . '<br>';
            echo '<span style="color:#999">→</span> ' . date_i18n('d M Y, H:i', $end);
            echo '</span>';
        } else {
            echo '<span style="color:#ccc;font-size:12px">—</span>';
        }
    }

    if ($col === 'offer_prods') {
        global $wpdb;
        $cnt = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM {$wpdb->postmeta} WHERE meta_key = %s AND meta_value = '1'",
            'bh_offer_' . $post_id
        ));
        echo '<span style="background:' . esc_attr($color) . ';color:#fff;padding:2px 8px;border-radius:10px;font-size:12px;font-weight:700">' . (int)$cnt . '</span>';
    }
}, 10, 2);
