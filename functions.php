<?php
if ( ! defined( 'ABSPATH' ) ) exit;

define( 'BAZAARHUB_VERSION', '1.3.3' );
define( 'BAZAARHUB_DIR', get_template_directory() );
define( 'BAZAARHUB_URI', get_template_directory_uri() );

// ─── Theme Setup ───────────────────────────────────────────
function bazaarhub_setup() {
    load_theme_textdomain( 'bazaarhub', BAZAARHUB_DIR . '/languages' );
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'html5', ['search-form','comment-form','comment-list','gallery','caption','style','script'] );
    add_theme_support( 'woocommerce', [
        'thumbnail_image_width' => 300,
        'single_image_width'    => 600,
        'product_grid'          => ['default_rows' => 4, 'min_rows' => 1, 'default_columns' => 4, 'min_columns' => 2, 'max_columns' => 6],
    ]);
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );
    add_theme_support( 'custom-logo', ['height'=>60,'width'=>200,'flex-height'=>true,'flex-width'=>true] );
    add_theme_support( 'customize-selective-refresh-widgets' );
    add_image_size( 'bazaarhub-product-card', 300, 300, true );
    add_image_size( 'bazaarhub-category-thumb', 150, 150, true );
    add_image_size( 'bazaarhub-banner', 1200, 500, true );

    register_nav_menus([
        'primary'    => __( 'Primary Menu', 'bazaarhub' ),
        'mega-menu'  => __( 'Mega Menu', 'bazaarhub' ),
        'top-bar'    => __( 'Top Bar Menu', 'bazaarhub' ),
        'footer-1'   => __( 'Footer Column 1', 'bazaarhub' ),
        'footer-2'   => __( 'Footer Column 2', 'bazaarhub' ),
        'footer-3'   => __( 'Footer Column 3', 'bazaarhub' ),
    ]);
}
add_action( 'after_setup_theme', 'bazaarhub_setup' );

// ─── Enqueue Scripts & Styles ──────────────────────────────
function bazaarhub_enqueue() {
    // Fonts
    wp_enqueue_style( 'bazaarhub-fonts', 'https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;600;700;800&family=Poppins:wght@400;500;600;700&display=swap', [], null );
    // Swiper
    wp_enqueue_style( 'bh-swiper', BAZAARHUB_URI . '/assets/vendor/swiper.min.css', [], '11.0' );
    wp_enqueue_script( 'bh-swiper', BAZAARHUB_URI . '/assets/vendor/swiper.min.js', [], '11.0.1', true );
    // Font Awesome
    wp_enqueue_style( 'font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css', [], '6.5.0' );
    // Main CSS
    wp_enqueue_style( 'bazaarhub-hero', BAZAARHUB_URI . '/assets/css/hero.css', [], BAZAARHUB_VERSION );
    wp_enqueue_style( 'bazaarhub-navbar', BAZAARHUB_URI . '/assets/css/navbar.css', [], BAZAARHUB_VERSION );
    wp_enqueue_style( 'bazaarhub-footer', BAZAARHUB_URI . '/assets/css/footer.css', [], BAZAARHUB_VERSION );
    wp_enqueue_style( 'bazaarhub-main', BAZAARHUB_URI . '/assets/css/main.css', ['bh-swiper'], BAZAARHUB_VERSION );
    // WooCommerce CSS
    if ( class_exists('WooCommerce') ) {
        wp_enqueue_style( 'bazaarhub-woo', BAZAARHUB_URI . '/assets/css/woocommerce.css', ['bazaarhub-main'], BAZAARHUB_VERSION );
    }
    // Main JS
    wp_enqueue_script( 'bazaarhub-main', BAZAARHUB_URI . '/assets/js/main.js', ['jquery'], BAZAARHUB_VERSION, true );
    wp_localize_script( 'bazaarhub-main', 'bazaarhub_ajax', [
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('bazaarhub_nonce'),
        'cart_url' => wc_get_cart_url(),
        'i18n'     => [
            'added_to_cart'    => __('Added to cart!', 'bazaarhub'),
            'added_to_wishlist'=> __('Added to wishlist!', 'bazaarhub'),
            'removed_wishlist' => __('Removed from wishlist!', 'bazaarhub'),
        ],
    ]);
    if ( is_singular() ) wp_enqueue_script('comment-reply');
}
add_action( 'wp_enqueue_scripts', 'bazaarhub_enqueue' );

// ─── Widget Areas ──────────────────────────────────────────
function bazaarhub_widgets_init() {
    $sidebars = [
        ['name'=>'Shop Sidebar',      'id'=>'shop-sidebar'],
        ['name'=>'Footer Widget 1',   'id'=>'footer-1'],
        ['name'=>'Footer Widget 2',   'id'=>'footer-2'],
        ['name'=>'Footer Widget 3',   'id'=>'footer-3'],
        ['name'=>'Footer Widget 4',   'id'=>'footer-4'],
    ];
    foreach ( $sidebars as $s ) {
        register_sidebar([
            'name'          => $s['name'],
            'id'            => $s['id'],
            'before_widget' => '<div class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3 class="widget-title">',
            'after_title'   => '</h3>',
        ]);
    }
}
add_action( 'widgets_init', 'bazaarhub_widgets_init' );

// ─── WooCommerce Compatibility ────────────────────────────
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content',  'woocommerce_output_content_wrapper_end', 10 );
add_action( 'woocommerce_before_main_content', function() { echo '<div class="bh-woo-wrap"><div class="bh-container"><div class="bh-woo-main">'; }, 10 );
add_action( 'woocommerce_after_main_content',  function() { echo '</div>'; }, 10 );
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );
add_action( 'woocommerce_sidebar', function() {
    echo '<aside class="bh-woo-sidebar">';
    dynamic_sidebar('shop-sidebar');
    echo '</aside></div></div>';
}, 10 );

// ─── Include files ────────────────────────────────────────
require_once BAZAARHUB_DIR . '/inc/customizer.php';
require_once BAZAARHUB_DIR . '/inc/wishlist.php';
require_once BAZAARHUB_DIR . '/inc/mega-menu.php';
require_once BAZAARHUB_DIR . '/inc/ajax-handlers.php';
require_once BAZAARHUB_DIR . '/inc/compare.php';
require_once BAZAARHUB_DIR . '/inc/helpers.php';
require_once BAZAARHUB_DIR . '/inc/product-sections.php';

function bazaarhub_flush_on_activate() {
    bazaarhub_create_compare_page();
    bazaarhub_create_wishlist_page();
    bazaarhub_create_info_pages();
    flush_rewrite_rules();
}
add_action('after_switch_theme','bazaarhub_flush_on_activate',20);
add_action('init','bazaarhub_flush_on_activate',99);

/**
 * Auto-create information pages on theme activation.
 */
function bazaarhub_create_info_pages() {
    $pages = [
        ['title'=>'About Us',          'slug'=>'about-us',          'template'=>'page-about.php'],
        ['title'=>'Contact Us',        'slug'=>'contact-us',        'template'=>'page-contact.php'],
        ['title'=>'Privacy Policy',    'slug'=>'privacy-policy',    'template'=>'page-privacy.php'],
        ['title'=>'Terms & Conditions','slug'=>'terms-conditions',   'template'=>'page-terms.php'],
        ['title'=>'Return Policy',     'slug'=>'return-policy',     'template'=>'page-return.php'],
    ];
    foreach ($pages as $page) {
        $existing = get_page_by_path($page['slug']);
        if (!$existing) {
            $id = wp_insert_post([
                'post_title'   => $page['title'],
                'post_name'    => $page['slug'],
                'post_status'  => 'publish',
                'post_type'    => 'page',
                'post_content' => '',
            ]);
            if ($id && !is_wp_error($id)) {
                update_post_meta($id, '_wp_page_template', $page['template']);
            }
        } else {
            // Ensure correct template is assigned
            $tmpl = get_post_meta($existing->ID, '_wp_page_template', true);
            if ($tmpl !== $page['template']) {
                update_post_meta($existing->ID, '_wp_page_template', $page['template']);
            }
        }
    }
}

/**
 * Admin notice recommending plugins.
 */
function bazaarhub_recommended_plugins_notice() {
    if (!current_user_can('install_plugins')) return;
    if (get_user_meta(get_current_user_id(), 'bazaarhub_plugins_dismissed', true)) return;

    $required = [
        ['name'=>'WooCommerce',           'slug'=>'woocommerce/woocommerce.php',               'install'=>'woocommerce'],
        ['name'=>'WooCommerce PDF Invoices','slug'=>'woocommerce-pdf-invoices-packing-slips/woocommerce-pdf-invoices-packing-slips.php','install'=>'woocommerce-pdf-invoices-packing-slips'],
        ['name'=>'Contact Form 7',        'slug'=>'contact-form-7/wp-contact-form-7.php',      'install'=>'contact-form-7'],
        ['name'=>'Yoast SEO',             'slug'=>'wordpress-seo/wp-seo.php',                  'install'=>'wordpress-seo'],
        ['name'=>'WP Super Cache',        'slug'=>'wp-super-cache/wp-cache.php',               'install'=>'wp-super-cache'],
    ];

    $missing = [];
    foreach ($required as $plugin) {
        if (!is_plugin_active($plugin['slug'])) {
            $missing[] = $plugin;
        }
    }
    if (empty($missing)) return;

    $dismiss_url = wp_nonce_url(add_query_arg('bazaarhub_dismiss_plugins','1'), 'bazaarhub_dismiss');
    echo '<div class="notice notice-info is-dismissible" style="padding:14px 16px;border-left:4px solid #43a047">';
    echo '<p><strong>🛒 Modhu Bazar Shop</strong> &mdash; ' . esc_html__('The following recommended plugins are not active:','bazaarhub') . '</p>';
    echo '<ul style="list-style:disc;padding-left:20px;margin:4px 0 10px">';
    foreach ($missing as $p) {
        $install_url = wp_nonce_url(
            self_admin_url('update.php?action=install-plugin&plugin=' . $p['install']),
            'install-plugin_' . $p['install']
        );
        echo '<li><strong>' . esc_html($p['name']) . '</strong> &mdash; <a href="' . esc_url($install_url) . '">' . esc_html__('Install Now','bazaarhub') . '</a></li>';
    }
    echo '</ul>';
    echo '<a href="' . esc_url($dismiss_url) . '" style="font-size:12px;color:#999">' . esc_html__('Dismiss','bazaarhub') . '</a>';
    echo '</div>';
}
add_action('admin_notices','bazaarhub_recommended_plugins_notice');

function bazaarhub_handle_dismiss_plugins() {
    if (isset($_GET['bazaarhub_dismiss_plugins']) && check_admin_referer('bazaarhub_dismiss')) {
        update_user_meta(get_current_user_id(), 'bazaarhub_plugins_dismissed', 1);
    }
}
add_action('admin_init','bazaarhub_handle_dismiss_plugins');

add_action('wp_ajax_bazaarhub_cat_products', 'bazaarhub_cat_products_ajax');
add_action('wp_ajax_nopriv_bazaarhub_cat_products', 'bazaarhub_cat_products_ajax');
function bazaarhub_cat_products_ajax() {
    check_ajax_referer('bazaarhub_nonce', 'nonce');
    $cat = sanitize_text_field($_POST['cat']);
    $query = new WP_Query([
        'post_type'      => 'product',
        'post_status'    => 'publish',
        'posts_per_page' => 8,
        'tax_query'      => [[
            'taxonomy' => 'product_cat',
            'field'    => 'slug',
            'terms'    => $cat,
        ]],
    ]);
    ob_start();
    while ($query->have_posts()): $query->the_post();
        $product = wc_get_product(get_the_ID());
        if (!$product) continue;
        ?>
        <div class="bh-product-card" data-pid="<?php the_ID(); ?>">
          <div class="bh-product-card__img">
            <a href="<?php the_permalink(); ?>">
              <?php if (has_post_thumbnail()): the_post_thumbnail('bazaarhub-product-card');
              else: ?><img src="<?php echo esc_url(wc_placeholder_img_src()); ?>" alt="">
              <?php endif; ?>
            </a>
            <div class="bh-product-card__badges"><?php echo bazaarhub_product_badge($product); ?></div>
            <div class="bh-product-card__actions">
              <button class="bh-wishlist-btn" data-pid="<?php the_ID(); ?>"><i class="far fa-heart"></i></button>
              <button class="bh-quickview-btn" data-pid="<?php the_ID(); ?>"><i class="fas fa-eye"></i></button>
            </div>
          </div>
          <div class="bh-product-card__body">
            <h4 class="bh-product-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
            <div class="bh-product-card__price"><?php echo $product->get_price_html(); ?></div>
            <button class="bh-btn bh-btn--green bh-add-to-cart" data-pid="<?php the_ID(); ?>">
              <i class="fas fa-cart-plus"></i> Add to Cart
            </button>
          </div>
        </div>
        <?php
    endwhile;
    wp_reset_postdata();
    $html = ob_get_clean();
    wp_send_json_success(['html' => $html]);
}

/* ── AJAX: Deals products by category ── */
add_action('wp_ajax_bazaarhub_deals_products', 'bazaarhub_deals_products_ajax');
add_action('wp_ajax_nopriv_bazaarhub_deals_products', 'bazaarhub_deals_products_ajax');
function bazaarhub_deals_products_ajax() {
    check_ajax_referer('bazaarhub_nonce', 'nonce');
    $cat = sanitize_text_field($_POST['cat']);
    $args = ['post_type'=>'product','post_status'=>'publish','posts_per_page'=>8,'orderby'=>'date','order'=>'DESC'];
    if ($cat) $args['tax_query'] = [['taxonomy'=>'product_cat','field'=>'slug','terms'=>$cat]];
    $query = new WP_Query($args);
    ob_start();
    while ($query->have_posts()): $query->the_post();
        $product = wc_get_product(get_the_ID());
        if (!$product) continue;
        $in_wl = bazaarhub_is_in_wishlist(get_the_ID());
        ?>
        <div class="bh-product-card" data-pid="<?php the_ID(); ?>">
          <div class="bh-product-card__img">
            <a href="<?php the_permalink(); ?>">
              <?php if (has_post_thumbnail()): the_post_thumbnail('bazaarhub-product-card');
              else: ?><img src="<?php echo esc_url(wc_placeholder_img_src()); ?>" alt=""><?php endif; ?>
            </a>
            <div class="bh-product-card__badges"><?php echo bazaarhub_product_badge($product); ?></div>
            <div class="bh-product-card__actions">
              <button class="bh-wishlist-btn <?php echo $in_wl?'active':''; ?>" data-pid="<?php the_ID(); ?>"><i class="<?php echo $in_wl?'fas':'far'; ?> fa-heart"></i></button>
              <button class="bh-quickview-btn" data-pid="<?php the_ID(); ?>"><i class="fas fa-eye"></i></button>
            </div>
          </div>
          <div class="bh-product-card__body">
            <div class="bh-product-card__cat"><?php echo wc_get_product_category_list(get_the_ID(),'|','<span>','</span>'); ?></div>
            <h4 class="bh-product-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
            <div class="bh-product-card__rating"><?php echo bazaarhub_render_stars($product->get_average_rating()); ?><span>(<?php echo $product->get_review_count(); ?>)</span></div>
            <div class="bh-product-card__price"><?php echo $product->get_price_html(); ?></div>
            <button class="bh-btn bh-btn--green bh-add-to-cart" data-pid="<?php the_ID(); ?>"><i class="fas fa-cart-plus"></i> Add to Cart</button>
          </div>
        </div>
        <?php
    endwhile;
    wp_reset_postdata();
    wp_send_json_success(['html' => ob_get_clean()]);
}

/* ── AJAX: Bestseller products by category ── */
add_action('wp_ajax_bazaarhub_bestseller_products', 'bazaarhub_bestseller_products_ajax');
add_action('wp_ajax_nopriv_bazaarhub_bestseller_products', 'bazaarhub_bestseller_products_ajax');
function bazaarhub_bestseller_products_ajax() {
    check_ajax_referer('bazaarhub_nonce', 'nonce');
    $cat = sanitize_text_field($_POST['cat']);
    $args = ['post_type'=>'product','post_status'=>'publish','posts_per_page'=>8,'meta_key'=>'total_sales','orderby'=>'meta_value_num','order'=>'DESC'];
    if ($cat) $args['tax_query'] = [['taxonomy'=>'product_cat','field'=>'slug','terms'=>$cat]];
    $query = new WP_Query($args);
    ob_start();
    while ($query->have_posts()): $query->the_post();
        $product = wc_get_product(get_the_ID());
        if (!$product) continue;
        $in_wl = bazaarhub_is_in_wishlist(get_the_ID());
        ?>
        <div class="bh-product-card" data-pid="<?php the_ID(); ?>">
          <div class="bh-product-card__img">
            <a href="<?php the_permalink(); ?>">
              <?php if (has_post_thumbnail()): the_post_thumbnail('bazaarhub-product-card');
              else: ?><img src="<?php echo esc_url(wc_placeholder_img_src()); ?>" alt=""><?php endif; ?>
            </a>
            <div class="bh-product-card__badges"><?php echo bazaarhub_product_badge($product); ?></div>
            <div class="bh-product-card__actions">
              <button class="bh-wishlist-btn <?php echo $in_wl?'active':''; ?>" data-pid="<?php the_ID(); ?>"><i class="<?php echo $in_wl?'fas':'far'; ?> fa-heart"></i></button>
              <button class="bh-quickview-btn" data-pid="<?php the_ID(); ?>"><i class="fas fa-eye"></i></button>
            </div>
          </div>
          <div class="bh-product-card__body">
            <h4 class="bh-product-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
            <div class="bh-product-card__rating"><?php echo bazaarhub_render_stars($product->get_average_rating()); ?></div>
            <div class="bh-product-card__price"><?php echo $product->get_price_html(); ?></div>
            <button class="bh-btn bh-btn--green bh-add-to-cart" data-pid="<?php the_ID(); ?>"><i class="fas fa-cart-plus"></i> Add to Cart</button>
          </div>
        </div>
        <?php
    endwhile;
    wp_reset_postdata();
    wp_send_json_success(['html' => ob_get_clean()]);
}
