<?php
if ( ! defined('ABSPATH') ) exit;

function bazaarhub_customizer( $wp_customize ) {

    // ── Top Bar ──────────────────────────────────────────
    $wp_customize->add_section('bh_top_bar', ['title'=>'Top Bar','priority'=>30]);
    $wp_customize->add_setting('top_bar_text',    ['default'=>'🚚 Free Delivery on Orders Over ৳500','sanitize_callback'=>'wp_kses_post','transport'=>'postMessage']);
    $wp_customize->add_setting('top_bar_phone',   ['default'=>'01700-000000','sanitize_callback'=>'sanitize_text_field']);
    $wp_customize->add_setting('top_bar_bg',      ['default'=>'#1a7a3c','sanitize_callback'=>'sanitize_hex_color']);
    $wp_customize->add_control('top_bar_text',  ['label'=>'Offer Text','section'=>'bh_top_bar','type'=>'text']);
    $wp_customize->add_control('top_bar_phone', ['label'=>'Phone Number','section'=>'bh_top_bar','type'=>'text']);
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize,'top_bar_bg',['label'=>'Background Color','section'=>'bh_top_bar']));

    // ── Header ───────────────────────────────────────────
    $wp_customize->add_section('bh_header', ['title'=>'Header Settings','priority'=>31]);
    $wp_customize->add_setting('header_tagline', ['default'=>'Your One-Stop Shop','sanitize_callback'=>'sanitize_text_field']);
    $wp_customize->add_setting('header_bg',      ['default'=>'#ffffff','sanitize_callback'=>'sanitize_hex_color']);
    $wp_customize->add_control('header_tagline', ['label'=>'Tagline Below Logo','section'=>'bh_header','type'=>'text']);
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize,'header_bg',['label'=>'Header Background','section'=>'bh_header']));

    // ── Hero Banners ─────────────────────────────────────
    $wp_customize->add_section('bh_hero', ['title'=>'Hero Banners (Carousel)','priority'=>32]);
    for ( $i = 1; $i <= 3; $i++ ) {
        $wp_customize->add_setting("hero_title_$i",    ['default'=>"Big Sale $i",'sanitize_callback'=>'sanitize_text_field']);
        $wp_customize->add_setting("hero_subtitle_$i", ['default'=>'Up To 50% Off','sanitize_callback'=>'sanitize_text_field']);
        $wp_customize->add_setting("hero_btn_text_$i", ['default'=>'Shop Now','sanitize_callback'=>'sanitize_text_field']);
        $wp_customize->add_setting("hero_btn_url_$i",  ['default'=>'/shop','sanitize_callback'=>'esc_url_raw']);
        $wp_customize->add_setting("hero_image_$i",    ['default'=>'','sanitize_callback'=>'esc_url_raw']);
        $wp_customize->add_setting("hero_bg_color_$i", ['default'=>'#e8f5e9','sanitize_callback'=>'sanitize_hex_color']);
        $wp_customize->add_control("hero_title_$i",    ['label'=>"Slide $i Title",'section'=>'bh_hero','type'=>'text']);
        $wp_customize->add_control("hero_subtitle_$i", ['label'=>"Slide $i Subtitle",'section'=>'bh_hero','type'=>'text']);
        $wp_customize->add_control("hero_btn_text_$i", ['label'=>"Slide $i Button Text",'section'=>'bh_hero','type'=>'text']);
        $wp_customize->add_control("hero_btn_url_$i",  ['label'=>"Slide $i Button URL",'section'=>'bh_hero','type'=>'url']);
        $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize,"hero_image_$i",['label'=>"Slide $i Image",'section'=>'bh_hero']));
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize,"hero_bg_color_$i",['label'=>"Slide $i BG Color",'section'=>'bh_hero']));
    }

    // ── Offer Banners ────────────────────────────────────
    $wp_customize->add_section('bh_offers', ['title'=>'Offer / Ad Banners','priority'=>33]);
    for ( $i = 1; $i <= 4; $i++ ) {
        $wp_customize->add_setting("offer_image_$i",  ['default'=>'','sanitize_callback'=>'esc_url_raw']);
        $wp_customize->add_setting("offer_url_$i",    ['default'=>'/shop','sanitize_callback'=>'esc_url_raw']);
        $wp_customize->add_setting("offer_label_$i",  ['default'=>"Special Offer $i",'sanitize_callback'=>'sanitize_text_field']);
        $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize,"offer_image_$i",['label'=>"Ad Banner $i Image",'section'=>'bh_offers']));
        $wp_customize->add_control("offer_url_$i",    ['label'=>"Ad Banner $i Link",'section'=>'bh_offers','type'=>'url']);
        $wp_customize->add_control("offer_label_$i",  ['label'=>"Ad Banner $i Label",'section'=>'bh_offers','type'=>'text']);
    }

    // ── Best Deals Section ───────────────────────────────
    $wp_customize->add_section('bh_deals', ['title'=>'Best Deals Section','priority'=>34]);
    $wp_customize->add_setting('deals_title',    ['default'=>'Best Deals Today','sanitize_callback'=>'sanitize_text_field','transport'=>'postMessage']);
    $wp_customize->add_setting('deals_category', ['default'=>'','sanitize_callback'=>'sanitize_text_field']);
    $wp_customize->add_setting('deals_count',    ['default'=>'8','sanitize_callback'=>'absint']);
    $wp_customize->add_setting('deals_timer_end',['default'=>'','sanitize_callback'=>'sanitize_text_field']);
    $wp_customize->add_control('deals_title',    ['label'=>'Section Title','section'=>'bh_deals','type'=>'text']);
    $wp_customize->add_control('deals_category', ['label'=>'Category Slug (leave empty = on-sale products)','section'=>'bh_deals','type'=>'text']);
    $wp_customize->add_control('deals_count',    ['label'=>'Products to Show','section'=>'bh_deals','type'=>'number']);
    $wp_customize->add_control('deals_timer_end',['label'=>'Countdown End Date (YYYY-MM-DD HH:MM:SS)','section'=>'bh_deals','type'=>'text']);

    // ── Colors ───────────────────────────────────────────
    $wp_customize->add_section('bh_colors', ['title'=>'Theme Colors','priority'=>35]);
    $wp_customize->add_setting('color_primary',   ['default'=>'#2e7d32','sanitize_callback'=>'sanitize_hex_color','transport'=>'postMessage']);
    $wp_customize->add_setting('color_secondary', ['default'=>'#ff6f00','sanitize_callback'=>'sanitize_hex_color','transport'=>'postMessage']);
    $wp_customize->add_setting('color_accent',    ['default'=>'#43a047','sanitize_callback'=>'sanitize_hex_color','transport'=>'postMessage']);
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize,'color_primary',   ['label'=>'Primary Green','section'=>'bh_colors']));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize,'color_secondary', ['label'=>'Accent Orange','section'=>'bh_colors']));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize,'color_accent',    ['label'=>'Light Green','section'=>'bh_colors']));

    // ── Footer ───────────────────────────────────────────
    $wp_customize->add_section('bh_footer', ['title'=>'Footer Settings','priority'=>36]);
    $wp_customize->add_setting('footer_about',     ['default'=>'BazaarHub is your trusted online grocery & department store.','sanitize_callback'=>'wp_kses_post']);
    $wp_customize->add_setting('footer_copyright', ['default'=>'© 2025 BazaarHub. All rights reserved.','sanitize_callback'=>'wp_kses_post','transport'=>'postMessage']);
    $wp_customize->add_setting('footer_bg',        ['default'=>'#1b1b1b','sanitize_callback'=>'sanitize_hex_color']);
    $wp_customize->add_control('footer_about',     ['label'=>'About Text','section'=>'bh_footer','type'=>'textarea']);
    $wp_customize->add_control('footer_copyright', ['label'=>'Copyright Text','section'=>'bh_footer','type'=>'text']);
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize,'footer_bg',['label'=>'Footer Background','section'=>'bh_footer']));

    // ── Social Links ─────────────────────────────────────
    $wp_customize->add_section('bh_social', ['title'=>'Social Links','priority'=>37]);
    foreach(['facebook','instagram','twitter','youtube','whatsapp'] as $s) {
        $wp_customize->add_setting("social_$s", ['default'=>'#','sanitize_callback'=>'esc_url_raw']);
        $wp_customize->add_control("social_$s",  ['label'=>ucfirst($s).' URL','section'=>'bh_social','type'=>'url']);
    }
}
add_action( 'customize_register', 'bazaarhub_customizer' );

// Output CSS variables from customizer
function bazaarhub_customizer_css() {
    $primary   = get_theme_mod('color_primary',   '#2e7d32');
    $secondary = get_theme_mod('color_secondary', '#ff6f00');
    $accent    = get_theme_mod('color_accent',    '#43a047');
    $top_bg    = get_theme_mod('top_bar_bg',      '#1a7a3c');
    $footer_bg = get_theme_mod('footer_bg',       '#1b1b1b');
    echo "<style>:root{
        --bh-primary:{$primary};
        --bh-secondary:{$secondary};
        --bh-accent:{$accent};
        --bh-topbar-bg:{$top_bg};
        --bh-footer-bg:{$footer_bg};
    }</style>";
}
add_action( 'wp_head', 'bazaarhub_customizer_css' );

function bazaarhub_customizer_extra($wp_customize) {
    // ── Bestseller Section ──────────────────────────────
    $wp_customize->add_section('bh_bestseller',['title'=>'Bestseller Section','priority'=>38]);
    $wp_customize->add_setting('bestseller_title',['default'=>'Bestsellers of the Week','sanitize_callback'=>'sanitize_text_field','transport'=>'postMessage']);
    $wp_customize->add_setting('bestseller_desc', ['default'=>'Our most popular products loved by customers.','sanitize_callback'=>'sanitize_text_field']);
    $wp_customize->add_setting('bestseller_count',['default'=>'8','sanitize_callback'=>'absint']);
    $wp_customize->add_setting('bestseller_cat',  ['default'=>'','sanitize_callback'=>'sanitize_text_field']);
    $wp_customize->add_control('bestseller_title', ['label'=>'Section Title','section'=>'bh_bestseller','type'=>'text']);
    $wp_customize->add_control('bestseller_desc',  ['label'=>'Description','section'=>'bh_bestseller','type'=>'textarea']);
    $wp_customize->add_control('bestseller_count', ['label'=>'Products Count','section'=>'bh_bestseller','type'=>'number']);
    $wp_customize->add_control('bestseller_cat',   ['label'=>'Category Slug (optional)','section'=>'bh_bestseller','type'=>'text']);

    // ── Category Showcase ────────────────────────────────
    $wp_customize->add_section('bh_cat_showcase',['title'=>'Category Showcase Section','priority'=>39]);
    $wp_customize->add_setting('catshow_title',['default'=>'Shop by Categories','sanitize_callback'=>'sanitize_text_field','transport'=>'postMessage']);
    $wp_customize->add_control('catshow_title',['label'=>'Section Title','section'=>'bh_cat_showcase','type'=>'text']);
    for($i=1;$i<=8;$i++){
        $wp_customize->add_setting("catshow_image_$i",['default'=>'','sanitize_callback'=>'esc_url_raw']);
        $wp_customize->add_setting("catshow_label_$i",['default'=>"Category $i",'sanitize_callback'=>'sanitize_text_field']);
        $wp_customize->add_setting("catshow_url_$i",  ['default'=>'/shop','sanitize_callback'=>'esc_url_raw']);
        $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize,"catshow_image_$i",['label'=>"Cat $i Image",'section'=>'bh_cat_showcase']));
        $wp_customize->add_control("catshow_label_$i",['label'=>"Cat $i Label",'section'=>'bh_cat_showcase','type'=>'text']);
        $wp_customize->add_control("catshow_url_$i",  ['label'=>"Cat $i URL",'section'=>'bh_cat_showcase','type'=>'url']);
    }

    // ── Promo Banners (sidebar on homepage) ─────────────
    $wp_customize->add_section('bh_promo_side',['title'=>'Homepage Promo Side Banners','priority'=>40]);
    for($i=1;$i<=4;$i++){
        $wp_customize->add_setting("promo_side_image_$i",['default'=>'','sanitize_callback'=>'esc_url_raw']);
        $wp_customize->add_setting("promo_side_url_$i",  ['default'=>'/shop','sanitize_callback'=>'esc_url_raw']);
        $wp_customize->add_setting("promo_side_label_$i",['default'=>"Promo $i",'sanitize_callback'=>'sanitize_text_field']);
        $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize,"promo_side_image_$i",['label'=>"Promo Banner $i",'section'=>'bh_promo_side']));
        $wp_customize->add_control("promo_side_url_$i",  ['label'=>"Promo $i URL",'section'=>'bh_promo_side','type'=>'url']);
        $wp_customize->add_control("promo_side_label_$i",['label'=>"Promo $i Label",'section'=>'bh_promo_side','type'=>'text']);
    }
}
add_action('customize_register','bazaarhub_customizer_extra');
