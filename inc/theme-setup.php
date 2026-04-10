<?php
/**
 * BazaarHub — One-Click Theme Setup
 *
 * Runs on theme activation (after_switch_theme).
 * Creates all required pages, sets homepage, configures WooCommerce pages,
 * sets permalink structure, and applies default customizer settings.
 *
 * Safe to run multiple times — skips pages that already exist.
 */
defined('ABSPATH') || exit;

/* ═══════════════════════════════════════════════════════
   MAIN ENTRY — runs once on theme switch
   ═══════════════════════════════════════════════════════ */
add_action('after_switch_theme', 'bazaarhub_run_setup', 10);

function bazaarhub_run_setup() {
    // Guard: only run once per theme activation
    if (get_option('bazaarhub_setup_done') === BAZAARHUB_VERSION) return;

    bazaarhub_setup_pages();
    bazaarhub_setup_homepage();
    bazaarhub_setup_woocommerce_pages();
    bazaarhub_setup_permalinks();
    bazaarhub_setup_nav_menus();
    bazaarhub_setup_customizer_defaults();
    bazaarhub_setup_sidebars();

    update_option('bazaarhub_setup_done', BAZAARHUB_VERSION);

    // Flush rewrite rules last
    flush_rewrite_rules();

    // Show admin notice
    set_transient('bazaarhub_setup_notice', 1, 30);
}

/* ═══════════════════════════════════════════════════════
   1. CREATE ALL PAGES
   ═══════════════════════════════════════════════════════ */
function bazaarhub_setup_pages() {
    $pages = [

        // ── Main pages ──────────────────────────────────
        [
            'title'    => 'Home',
            'slug'     => 'home',
            'template' => 'front-page.php',
            'content'  => '',
            'option'   => '', // set as front page separately
        ],

        // ── Info pages ───────────────────────────────────
        [
            'title'    => 'About Us',
            'slug'     => 'about-us',
            'template' => 'page-about.php',
            'content'  => '',
        ],
        [
            'title'    => 'Contact Us',
            'slug'     => 'contact-us',
            'template' => 'page-contact.php',
            'content'  => '',
        ],
        [
            'title'    => 'Privacy Policy',
            'slug'     => 'privacy-policy',
            'template' => 'page-privacy.php',
            'content'  => '',
        ],
        [
            'title'    => 'Terms & Conditions',
            'slug'     => 'terms-conditions',
            'template' => 'page-terms.php',
            'content'  => '',
        ],
        [
            'title'    => 'Return Policy',
            'slug'     => 'return-policy',
            'template' => 'page-return.php',
            'content'  => '',
        ],

        // ── WooCommerce pages (shortcode content) ────────
        [
            'title'    => 'Shop',
            'slug'     => 'shop',
            'template' => '',
            'content'  => '',
            'wc_key'   => 'shop',
        ],
        [
            'title'    => 'Cart',
            'slug'     => 'cart',
            'template' => '',
            'content'  => '<!-- wp:shortcode -->[woocommerce_cart]<!-- /wp:shortcode -->',
            'wc_key'   => 'cart',
        ],
        [
            'title'    => 'Checkout',
            'slug'     => 'checkout',
            'template' => '',
            'content'  => '<!-- wp:shortcode -->[woocommerce_checkout]<!-- /wp:shortcode -->',
            'wc_key'   => 'checkout',
        ],
        [
            'title'    => 'My Account',
            'slug'     => 'my-account',
            'template' => '',
            'content'  => '<!-- wp:shortcode -->[woocommerce_my_account]<!-- /wp:shortcode -->',
            'wc_key'   => 'myaccount',
        ],
        [
            'title'    => 'Order Tracking',
            'slug'     => 'order-tracking',
            'template' => '',
            'content'  => '<!-- wp:shortcode -->[woocommerce_order_tracking]<!-- /wp:shortcode -->',
        ],

        // ── Utility pages ────────────────────────────────
        [
            'title'    => 'Wishlist',
            'slug'     => 'wishlist',
            'template' => '',
            'content'  => '<!-- wp:shortcode -->[bazaarhub_wishlist]<!-- /wp:shortcode -->',
            'option'   => 'bazaarhub_wishlist_page_id',
        ],
        [
            'title'    => 'Compare Products',
            'slug'     => 'compare',
            'template' => '',
            'content'  => '<!-- wp:shortcode -->[bazaarhub_compare]<!-- /wp:shortcode -->',
            'option'   => 'bazaarhub_compare_page_id',
        ],

    ];

    $created = [];

    foreach ($pages as $page) {
        $existing = get_page_by_path($page['slug']);

        if (!$existing) {
            $id = wp_insert_post([
                'post_title'   => $page['title'],
                'post_name'    => $page['slug'],
                'post_status'  => 'publish',
                'post_type'    => 'page',
                'post_content' => $page['content'] ?? '',
            ]);

            if ($id && !is_wp_error($id)) {
                if (!empty($page['template'])) {
                    update_post_meta($id, '_wp_page_template', $page['template']);
                }
                $created[$page['slug']] = $id;

                // Save to theme option
                if (!empty($page['option'])) {
                    update_option($page['option'], $id);
                }
                // Save WooCommerce page ID
                if (!empty($page['wc_key'])) {
                    update_option('woocommerce_' . $page['wc_key'] . '_page_id', $id);
                }
            }
        } else {
            $id = $existing->ID;
            $created[$page['slug']] = $id;

            // Fix template if wrong
            if (!empty($page['template'])) {
                $curr = get_post_meta($id, '_wp_page_template', true);
                if ($curr !== $page['template']) {
                    update_post_meta($id, '_wp_page_template', $page['template']);
                }
            }
            // Ensure WC option points here
            if (!empty($page['wc_key'])) {
                $opt = 'woocommerce_' . $page['wc_key'] . '_page_id';
                if ((int) get_option($opt) !== $id) {
                    update_option($opt, $id);
                }
            }
            if (!empty($page['option'])) {
                if ((int) get_option($page['option']) !== $id) {
                    update_option($page['option'], $id);
                }
            }
        }
    }

    // Store page ID map for other setup functions
    update_option('bazaarhub_page_ids', $created);
}

/* ═══════════════════════════════════════════════════════
   2. SET HOMEPAGE
   ═══════════════════════════════════════════════════════ */
function bazaarhub_setup_homepage() {
    // Only set if still showing "latest posts" (default WP)
    if (get_option('show_on_front') === 'posts') {
        $home = get_page_by_path('home');
        if ($home) {
            update_option('show_on_front', 'page');
            update_option('page_on_front', $home->ID);
        }
    }
}

/* ═══════════════════════════════════════════════════════
   3. WOOCOMMERCE DEFAULT SETTINGS
   ═══════════════════════════════════════════════════════ */
function bazaarhub_setup_woocommerce_pages() {
    if (!class_exists('WooCommerce')) return;

    // Currency = BDT
    if (!get_option('woocommerce_currency')) {
        update_option('woocommerce_currency', 'BDT');
    }
    // Currency symbol position
    update_option('woocommerce_currency_pos', 'right');

    // Default country = Bangladesh
    if (!get_option('woocommerce_default_country')) {
        update_option('woocommerce_default_country', 'BD');
    }

    // Products per page
    if (!get_option('woocommerce_catalog_columns')) {
        update_option('woocommerce_catalog_columns', 4);
    }
    if (!get_option('woocommerce_catalog_rows')) {
        update_option('woocommerce_catalog_rows', 4);
    }

    // Hide products already in cart from loop
    update_option('woocommerce_cart_redirect_after_add', 'no');

    // Enable reviews
    update_option('woocommerce_enable_reviews', 'yes');

    // Guest checkout
    update_option('woocommerce_enable_guest_checkout', 'yes');
}

/* ═══════════════════════════════════════════════════════
   4. PERMALINK STRUCTURE
   ═══════════════════════════════════════════════════════ */
function bazaarhub_setup_permalinks() {
    global $wp_rewrite;
    // Only change if using ugly default
    $current = get_option('permalink_structure');
    if (empty($current) || $current === '/?p=%postname%') {
        $wp_rewrite->set_permalink_structure('/%postname%/');
    }
    // WooCommerce permalinks
    if (!get_option('woocommerce_permalinks')) {
        update_option('woocommerce_permalinks', [
            'product_base'           => '/product',
            'category_base'          => '/product-category',
            'tag_base'               => '/product-tag',
            'attribute_base'         => '',
            'use_verbose_page_rules' => false,
        ]);
    }
}

/* ═══════════════════════════════════════════════════════
   5. NAV MENUS — create locations if empty
   ═══════════════════════════════════════════════════════ */
function bazaarhub_setup_nav_menus() {
    $locations = get_registered_nav_menus();

    foreach ($locations as $location => $description) {
        $assigned = get_nav_menu_locations();
        if (!empty($assigned[$location])) continue; // already assigned

        // Create a menu named after the location
        $menu_name = 'BazaarHub ' . ucwords(str_replace('-', ' ', $location));
        $menu_exists = wp_get_nav_menu_object($menu_name);

        if (!$menu_exists) {
            $menu_id = wp_create_nav_menu($menu_name);
        } else {
            $menu_id = $menu_exists->term_id;
        }

        if (!is_wp_error($menu_id)) {
            // Add default items to primary menu
            if ($location === 'primary' || $location === 'main-menu') {
                $default_pages = ['Home' => 'home', 'Shop' => 'shop', 'About Us' => 'about-us', 'Contact Us' => 'contact-us'];
                foreach ($default_pages as $label => $slug) {
                    $page = get_page_by_path($slug);
                    if ($page) {
                        wp_nav_menu_item_db_id(0); // reset
                        wp_update_nav_menu_item($menu_id, 0, [
                            'menu-item-title'     => $label,
                            'menu-item-object'    => 'page',
                            'menu-item-object-id' => $page->ID,
                            'menu-item-type'      => 'post_type',
                            'menu-item-status'    => 'publish',
                        ]);
                    }
                }
            }

            // Assign to location
            $current = get_nav_menu_locations();
            $current[$location] = $menu_id;
            set_theme_mod('nav_menu_locations', $current);
        }
    }
}

/* ═══════════════════════════════════════════════════════
   6. CUSTOMIZER DEFAULTS
   ═══════════════════════════════════════════════════════ */
function bazaarhub_setup_customizer_defaults() {
    $defaults = [
        // General
        'site_tagline_text'  => 'আপনার বিশ্বস্ত অনলাইন শপিং গন্তব্য',
        'topbar_text'        => '🚚 Free Delivery on Orders Over ৳500 | Call: 01700-000000',
        'topbar_phone'       => '01700-000000',

        // Footer
        'footer_copyright'   => '&copy; ' . date('Y') . ' Modhu Bazar Shop. All rights reserved.',
        'footer_about'       => 'আপনার বিশ্বস্ত অনলাইন শপিং গন্তব্য। সেরা মানের পণ্য, সেরা দামে পৌঁছে দিচ্ছি।',
        'footer_email'       => 'support@modhubazarshop.com',
        'footer_address'     => 'Dhaka, Bangladesh',
        'footer_hours'       => 'Sat–Thu: 9AM – 9PM',

        // Deals section
        'deals_title'        => 'Hot Deals Today',
        'deals_count'        => '8',
        'deals_timer_end'    => '',

        // Colors
        'primary_color'      => '#43a047',
        'secondary_color'    => '#ff6f00',
    ];

    foreach ($defaults as $key => $value) {
        // Only set if not already configured by user
        if (get_theme_mod($key) === '' || get_theme_mod($key) === false) {
            set_theme_mod($key, $value);
        }
    }
}

/* ═══════════════════════════════════════════════════════
   7. SIDEBAR — add default WC price filter widget
   ═══════════════════════════════════════════════════════ */
function bazaarhub_setup_sidebars() {
    // Register default widgets in shop sidebar if empty
    $sidebars = get_option('sidebars_widgets', []);
    if (empty($sidebars['shop-sidebar'])) {
        $sidebars['shop-sidebar'] = [];
        update_option('sidebars_widgets', $sidebars);
    }
}

/* ═══════════════════════════════════════════════════════
   8. ADMIN NOTICE — shown after setup completes
   ═══════════════════════════════════════════════════════ */
add_action('admin_notices', function() {
    if (!get_transient('bazaarhub_setup_notice')) return;
    delete_transient('bazaarhub_setup_notice');
    ?>
    <div class="notice notice-success is-dismissible" style="border-left-color:#43a047;padding:16px 20px;">
        <h3 style="margin:0 0 8px;color:#2e7d32;font-size:15px;">
            🎉 BazaarHub Theme — Setup Complete!
        </h3>
        <p style="margin:0 0 6px;">All pages have been created automatically:</p>
        <ul style="margin:4px 0 10px 16px;list-style:disc;color:#444;line-height:1.8;">
            <li><strong>Home, Shop, Cart, Checkout, My Account</strong> — WooCommerce ready</li>
            <li><strong>About Us, Contact Us, Privacy Policy, Terms &amp; Conditions, Return Policy</strong></li>
            <li><strong>Wishlist, Compare, Order Tracking</strong></li>
        </ul>
        <p style="margin:0;">
            <a href="<?php echo esc_url(admin_url('customize.php')); ?>" class="button button-primary" style="background:#43a047;border-color:#2e7d32;">
                🎨 Open Customizer
            </a>
            &nbsp;
            <a href="<?php echo esc_url(admin_url('nav-menus.php')); ?>" class="button">
                🔗 Set Up Menus
            </a>
            &nbsp;
            <a href="<?php echo esc_url(home_url('/')); ?>" class="button" target="_blank">
                👁 Preview Site
            </a>
        </p>
    </div>
    <?php
});

/* ═══════════════════════════════════════════════════════
   9. RE-RUN BUTTON in Appearance menu (for reset)
   ═══════════════════════════════════════════════════════ */
add_action('admin_menu', function() {
    add_theme_page(
        'BazaarHub Setup',
        '🛠 Theme Setup',
        'manage_options',
        'bazaarhub-setup',
        'bazaarhub_setup_page'
    );
});

function bazaarhub_setup_page() {
    // Handle re-run
    if (isset($_POST['bazaarhub_rerun_setup']) && check_admin_referer('bazaarhub_rerun')) {
        delete_option('bazaarhub_setup_done');
        bazaarhub_run_setup();
        echo '<div class="notice notice-success"><p>✅ Setup re-run complete! All missing pages created.</p></div>';
    }
    ?>
    <div class="wrap">
        <h1>🛠 BazaarHub Theme Setup</h1>
        <p>This will create all required pages that don't already exist, and configure default settings.</p>

        <table class="widefat" style="max-width:600px;margin:20px 0;">
            <thead><tr><th>Page</th><th>Status</th><th>URL</th></tr></thead>
            <tbody>
            <?php
            $check_pages = [
                'Home'             => 'home',
                'Shop'             => 'shop',
                'Cart'             => 'cart',
                'Checkout'         => 'checkout',
                'My Account'       => 'my-account',
                'About Us'         => 'about-us',
                'Contact Us'       => 'contact-us',
                'Privacy Policy'   => 'privacy-policy',
                'Terms & Conditions' => 'terms-conditions',
                'Return Policy'    => 'return-policy',
                'Wishlist'         => 'wishlist',
                'Compare Products' => 'compare',
                'Order Tracking'   => 'order-tracking',
            ];
            foreach ($check_pages as $name => $slug):
                $page = get_page_by_path($slug);
                $exists = !empty($page) && $page->post_status === 'publish';
            ?>
            <tr>
                <td><strong><?php echo esc_html($name); ?></strong></td>
                <td><?php echo $exists
                    ? '<span style="color:#2e7d32;font-weight:700;">✅ Created</span>'
                    : '<span style="color:#e53935;font-weight:700;">❌ Missing</span>'; ?>
                </td>
                <td><?php if ($exists): ?>
                    <a href="<?php echo esc_url(get_permalink($page->ID)); ?>" target="_blank">
                        <?php echo esc_html(get_permalink($page->ID)); ?>
                    </a>
                <?php endif; ?></td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

        <form method="post">
            <?php wp_nonce_field('bazaarhub_rerun'); ?>
            <p>
                <button type="submit" name="bazaarhub_rerun_setup" class="button button-primary button-large" style="background:#43a047;border-color:#2e7d32;">
                    🔄 Re-run Setup (create missing pages only)
                </button>
            </p>
            <p class="description">
                Already existing pages will <strong>not</strong> be overwritten or deleted.
            </p>
        </form>
    </div>
    <?php
}
