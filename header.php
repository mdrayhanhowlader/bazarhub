<?php defined('ABSPATH') || exit; ?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<meta name="viewport" content="width=device-width,initial-scale=1">
<link rel="preconnect" href="https://fonts.googleapis.com">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<!-- TOP BAR -->
<div class="bh-topbar-new">
  <div class="bh-topbar-new__inner bh-container">

    <!-- Left: free delivery text -->
    <div class="bh-topbar-new__left">
      <span><?php echo wp_kses_post(bazaarhub_top_bar_text()); ?></span>
    </div>

    <!-- Right: auth links -->
    <div class="bh-topbar-new__right">
      <?php if(is_user_logged_in()): ?>
        <a href="<?php echo esc_url(get_permalink(get_option('woocommerce_myaccount_page_id'))); ?>" class="bh-topbar-new__link">
          <i class="fas fa-user"></i> My Account
        </a>
        <a href="<?php echo esc_url(wp_logout_url(home_url())); ?>" class="bh-topbar-new__link">
          Logout
        </a>
      <?php else: ?>
        <a href="<?php echo esc_url(wp_login_url()); ?>" class="bh-topbar-new__link">
          <i class="fas fa-sign-in-alt"></i> Login
        </a>
        <a href="<?php echo esc_url(wp_registration_url()); ?>" class="bh-topbar-new__link">
          Register
        </a>
      <?php endif; ?>
      <a href="<?php echo esc_url(wc_get_account_endpoint_url('orders')); ?>" class="bh-topbar-new__link">
        <i class="fas fa-box"></i> Track Order
      </a>
    </div>

  </div>
</div>

<!-- MAIN HEADER -->
<header class="bh-header" id="bh-header">
  <div class="bh-container bh-header__inner">

    <div class="bh-header__logo">
      <?php
      $tagline = get_theme_mod('header_tagline','');
      if(has_custom_logo()):
        if($tagline):
      ?>
        <div class="bh-logo-with-tagline">
          <?php the_custom_logo(); ?>
          <span class="bh-logo-tagline"><?php echo esc_html($tagline); ?></span>
        </div>
      <?php else: the_custom_logo();
        endif;
      else: ?>
        <a href="<?php echo esc_url(home_url('/')); ?>" class="bh-logo-text">
          <i class="fas fa-store"></i>
          <span><?php echo esc_html(get_bloginfo('name')); ?></span>
          <?php if($tagline): ?><span class="bh-header__tagline"><?php echo esc_html($tagline); ?></span><?php endif; ?>
        </a>
      <?php endif; ?>
    </div>

    <div class="bh-header__search">
      <div class="bh-search-wrap">
        <select class="bh-search-cat" id="bh-search-cat">
          <option value="">All Categories</option>
          <?php foreach(get_terms(['taxonomy'=>'product_cat','hide_empty'=>true,'parent'=>0]) as $c): ?>
            <option value="<?php echo esc_attr($c->slug); ?>"><?php echo esc_html($c->name); ?></option>
          <?php endforeach; ?>
        </select>
        <input type="text" class="bh-search-input" id="bh-search-input" placeholder="Search for products...">
        <button class="bh-search-btn" id="bh-search-btn"><i class="fas fa-search"></i></button>
        <div class="bh-search-dropdown" id="bh-search-dropdown"></div>
      </div>
    </div>

    <button class="bh-mobile-search-icon" id="bh-mobile-search-icon" style="display:none"><i class="fas fa-search"></i></button>
    <div class="bh-header__actions">
      <a href="<?php echo esc_url(bazaarhub_compare_url()); ?>" class="bh-action-btn" title="Compare">
        <i class="fas fa-exchange-alt"></i>
        <span class="bh-action-count bh-compare-count"><?php echo bazaarhub_compare_count(); ?></span>
        <span class="bh-action-label">Compare</span>
      </a>
      <a href="<?php echo esc_url(bazaarhub_wishlist_url()); ?>" class="bh-action-btn" title="Wishlist">
        <i class="far fa-heart"></i>
        <span class="bh-action-count bh-wishlist-count"><?php echo esc_html(bazaarhub_get_wishlist_count()); ?></span>
        <span class="bh-action-label">Wishlist</span>
      </a>
      <div class="bh-cart-wrap">
        <button class="bh-action-btn bh-cart-trigger" id="bh-cart-trigger" title="Cart">
          <i class="fas fa-shopping-cart"></i>
          <span class="bh-action-count bh-cart-count"><?php echo bazaarhub_get_cart_count(); ?></span>
          <span class="bh-action-label">Cart</span>
        </button>
      </div>
      <button class="bh-hamburger" id="bh-hamburger" aria-label="Menu">
        <span></span><span></span><span></span>
      </button>
    </div>

  </div>
</header>

<!-- NAVBAR -->
<nav class="bh-navbar" id="bh-navbar">
  <div class="bh-container bh-navbar__inner">
    <div class="bh-catmenu" id="bh-catmenu">
      <button class="bh-catmenu__btn" id="bh-catmenu-btn" aria-expanded="false">
        <i class="fas fa-th-large"></i>
        <span>All Categories</span>
        <i class="fas fa-chevron-down bh-catmenu__arrow"></i>
      </button>
      <div class="bh-catmenu__panel" id="bh-catmenu-panel">
        <div class="bh-catmenu__grid">
          <?php
          $all_cats = get_terms(['taxonomy'=>'product_cat','hide_empty'=>true,'parent'=>0,'orderby'=>'name']);
          if(!is_wp_error($all_cats)):
            foreach($all_cats as $pcat):
              $sub_cats = get_terms(['taxonomy'=>'product_cat','hide_empty'=>true,'parent'=>$pcat->term_id,'orderby'=>'name']);
          ?>
          <div class="bh-catmenu__col">
            <a href="<?php echo esc_url(get_term_link($pcat)); ?>" class="bh-catmenu__col-title">
              <?php echo esc_html($pcat->name); ?>
            </a>
            <?php if(!empty($sub_cats) && !is_wp_error($sub_cats)): ?>
            <ul class="bh-catmenu__sub-list">
              <?php foreach($sub_cats as $sub): ?>
              <li><a href="<?php echo esc_url(get_term_link($sub)); ?>"><?php echo esc_html($sub->name); ?></a></li>
              <?php endforeach; ?>
            </ul>
            <?php endif; ?>
          </div>
          <?php endforeach; endif; ?>
        </div>
        <div class="bh-catmenu__viewall">
          <a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>">View All Categories <i class="fas fa-arrow-right"></i></a>
        </div>
      </div>
    </div>
    <div class="bh-navlinks">
      <?php
      $nav_cats = get_terms(['taxonomy'=>'product_cat','hide_empty'=>true,'parent'=>0,'number'=>6,'orderby'=>'count','order'=>'DESC']);
      if(!is_wp_error($nav_cats)) foreach($nav_cats as $cat):
        $tid = get_term_meta($cat->term_id,'thumbnail_id',true);
        $img = $tid ? wp_get_attachment_image_url($tid,[18,18]) : '';
        $active = is_product_category($cat->slug) ? 'bh-navlinks__link--active' : '';
      ?>
      <a href="<?php echo esc_url(get_term_link($cat)); ?>" class="bh-navlinks__link <?php echo $active; ?>">
        <?php if($img): ?><img src="<?php echo esc_url($img); ?>" alt=""><?php endif; ?>
        <?php echo esc_html($cat->name); ?>
      </a>
      <?php endforeach; ?>
    </div>
    <a href="<?php echo esc_url(home_url('/#bh-deals')); ?>" class="bh-navbar__deals">
      <i class="fas fa-fire"></i> Best Deals
    </a>
  </div>
</nav>

<!-- Mobile Menu -->
<div class="bh-mobile-menu" id="bh-mobile-menu">
  <div class="bh-mobile-menu__header">
    <div class="bh-mobile-menu__logo">
      <i class="fas fa-store"></i>
      <span><?php echo esc_html(get_bloginfo('name')); ?></span>
    </div>
    <button id="bh-mobile-close" class="bh-mobile-menu__close"><i class="fas fa-times"></i></button>
  </div>
  <div class="bh-mobile-menu__user">
    <?php if(is_user_logged_in()): ?>
    <div class="bh-mobile-menu__user-info">
      <i class="fas fa-user-circle"></i>
      <span><?php echo esc_html(wp_get_current_user()->display_name); ?></span>
    </div>
    <div class="bh-mobile-menu__user-links">
      <a href="<?php echo esc_url(get_permalink(get_option('woocommerce_myaccount_page_id'))); ?>"><i class="fas fa-user"></i> My Account</a>
      <a href="<?php echo esc_url(wc_get_account_endpoint_url('orders')); ?>"><i class="fas fa-box"></i> My Orders</a>
      <a href="<?php echo esc_url(wp_logout_url(home_url())); ?>"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>
    <?php else: ?>
    <div class="bh-mobile-menu__auth">
      <a href="<?php echo esc_url(wp_login_url()); ?>" class="bh-mobile-auth-btn bh-mobile-auth-btn--login"><i class="fas fa-sign-in-alt"></i> Login</a>
      <a href="<?php echo esc_url(wp_registration_url()); ?>" class="bh-mobile-auth-btn bh-mobile-auth-btn--reg"><i class="fas fa-user-plus"></i> Register</a>
    </div>
    <?php endif; ?>
  </div>

  <div class="bh-mobile-menu__body">
    <div class="bh-mobile-cats-wrap">
    <div class="bh-mobile-menu__section-title bh-cats-toggle"><i class="fas fa-list"></i> Categories <i class="fas fa-chevron-down bh-cat-toggle-arrow"></i></div>
    <div class="bh-mobile-cats">
      <?php
      $m_cats = get_terms(['taxonomy'=>'product_cat','hide_empty'=>true,'parent'=>0,'orderby'=>'name']);
      if(!is_wp_error($m_cats)) foreach($m_cats as $cat):
        $sub = get_terms(['taxonomy'=>'product_cat','hide_empty'=>true,'parent'=>$cat->term_id]);
        $has = !empty($sub) && !is_wp_error($sub);
      ?>
      <div class="bh-mcats__item">
        <a href="<?php echo esc_url(get_term_link($cat)); ?>" class="bh-mcats__link">
          <i class="fas fa-tag"></i>
          <span><?php echo esc_html($cat->name); ?></span>
          <?php if($has): ?><i class="fas fa-chevron-right bh-mcats__arrow"></i><?php endif; ?>
        </a>
        <?php if($has): ?>
        <div class="bh-mcats__sub">
          <?php foreach($sub as $s): ?>
          <a href="<?php echo esc_url(get_term_link($s)); ?>"><i class="fas fa-minus"></i><?php echo esc_html($s->name); ?></a>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>
      </div>
      <?php endforeach; ?>
    </div>
    <div class="bh-mobile-menu__section-title"><i class="fas fa-link"></i> Quick Links</div>
    <div class="bh-mobile-links">
      <a href="<?php echo home_url('/'); ?>"><i class="fas fa-home"></i> Home</a>
      <a href="<?php echo get_permalink(wc_get_page_id('shop')); ?>"><i class="fas fa-store"></i> Shop</a>
      <a href="<?php echo get_permalink(wc_get_page_id('cart')); ?>"><i class="fas fa-shopping-cart"></i> Cart</a>
      <a href="<?php echo esc_url(wc_get_account_endpoint_url('orders')); ?>"><i class="fas fa-box"></i> Track Order</a>
    </div>
  </div>
</div><!-- /.bh-mobile-menu__body -->
</div><!-- /.bh-mobile-menu -->
<div class="bh-overlay" id="bh-overlay"></div>

<main class="bh-main" id="bh-main">
