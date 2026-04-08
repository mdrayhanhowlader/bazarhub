<?php
defined('ABSPATH') || exit;
// Prevent duplicate result count & ordering (they're already in woocommerce_before_shop_loop hook)
remove_action('woocommerce_before_shop_loop','woocommerce_result_count',20);
remove_action('woocommerce_before_shop_loop','woocommerce_catalog_ordering',30);
get_header('shop');
?>
<div class="bh-shop-page">
  <div class="bh-container">

    <!-- Mobile filter toggle -->
    <button class="bh-mobile-filter-toggle" id="bh-mobile-filter-toggle">
      <i class="fas fa-sliders-h"></i> <?php _e('Filter & Sort','bazaarhub'); ?>
    </button>

    <div class="bh-shop-layout">

      <!-- Modern Sidebar -->
      <aside class="bh-shop-sidebar" id="bh-shop-sidebar">

        <!-- Search -->
        <div class="bh-sidebar-widget bh-sidebar-search">
          <h3 class="bh-sidebar-widget__title"><i class="fas fa-search"></i> Search Products</h3>
          <form class="bh-sidebar-searchform" action="<?php echo esc_url(home_url('/')); ?>" method="get">
            <input type="text" name="s" value="<?php echo esc_attr(get_search_query()); ?>" placeholder="Search...">
            <input type="hidden" name="post_type" value="product">
            <button type="submit"><i class="fas fa-search"></i></button>
          </form>
        </div>

        <!-- Categories -->
        <div class="bh-sidebar-widget bh-sidebar-cats">
          <h3 class="bh-sidebar-widget__title"><i class="fas fa-list"></i> Categories</h3>
          <ul class="bh-sidebar-cat-list">
            <?php
            $cats = get_terms(['taxonomy'=>'product_cat','hide_empty'=>true,'parent'=>0,'orderby'=>'name']);
            if(!is_wp_error($cats)) foreach($cats as $cat):
              $active = (is_product_category($cat->slug)) ? 'active' : '';
              $tid = get_term_meta($cat->term_id,'thumbnail_id',true);
              $img = $tid ? wp_get_attachment_image_url($tid,[24,24]) : '';
            ?>
            <li class="<?php echo $active; ?>">
              <a href="<?php echo esc_url(get_term_link($cat)); ?>">
                <span class="bh-sidebar-cat-thumb">
                  <?php if($img): ?>
                    <img src="<?php echo esc_url($img); ?>" alt="">
                  <?php else: ?>
                    <i class="fas fa-tag"></i>
                  <?php endif; ?>
                </span>
                <span><?php echo esc_html($cat->name); ?></span>
                <small><?php echo (int)$cat->count; ?></small>
              </a>
            </li>
            <?php endforeach; ?>
          </ul>
        </div>

        <!-- Price Filter (WooCommerce widget) -->
        <?php
        // Only output WC price filter widget — suppress any other widgets in this sidebar
        $wc_price_widget = false;
        if ( is_active_sidebar('shop-sidebar') ) {
            global $wp_registered_widgets, $sidebars_widgets;
            $sw = $sidebars_widgets['shop-sidebar'] ?? [];
            foreach ( $sw as $wid ) {
                if ( isset($wp_registered_widgets[$wid]) && strpos($wid,'woocommerce_price_filter') !== false ) {
                    $wc_price_widget = true; break;
                }
                if ( isset($wp_registered_widgets[$wid]['classname']) && strpos($wp_registered_widgets[$wid]['classname'],'woocommerce_price_filter') !== false ) {
                    $wc_price_widget = true; break;
                }
            }
        }
        if ( $wc_price_widget ): ?>
        <div class="bh-sidebar-widget bh-sidebar-price-filter">
          <h3 class="bh-sidebar-widget__title"><i class="fas fa-sliders-h"></i> <?php _e('Filter by Price','bazaarhub'); ?></h3>
          <?php
          // Output only price filter widget
          global $wp_registered_widgets, $sidebars_widgets;
          $sw = $sidebars_widgets['shop-sidebar'] ?? [];
          foreach ( $sw as $wid ) {
              $cls = $wp_registered_widgets[$wid]['classname'] ?? '';
              if ( strpos($cls,'woocommerce_price_filter') !== false || strpos($wid,'woocommerce_price_filter') !== false ) {
                  $callback = $wp_registered_widgets[$wid]['callback'];
                  $params    = $wp_registered_widgets[$wid]['params'][0] ?? [];
                  $params['widget_id']   = $wid;
                  $params['widget_name'] = $wp_registered_widgets[$wid]['name'];
                  call_user_func($callback, [], $params);
              }
          }
          ?>
        </div>
        <?php else: ?>
        <!-- Price range quick links when no WC price filter plugin -->
        <div class="bh-sidebar-widget">
          <h3 class="bh-sidebar-widget__title"><i class="fas fa-tag"></i> <?php _e('Price Range','bazaarhub'); ?></h3>
          <ul class="bh-sidebar-sort-list">
            <?php
            $ranges = [
                ['Under ৳100',   'max_price=100'],
                ['৳100 – ৳500',  'min_price=100&max_price=500'],
                ['৳500 – ৳1000', 'min_price=500&max_price=1000'],
                ['৳1000+',       'min_price=1000'],
            ];
            foreach ($ranges as $r):
              $url = get_permalink(wc_get_page_id('shop')) . '?' . $r[1];
            ?>
            <li><a href="<?php echo esc_url($url); ?>"><i class="fas fa-chevron-right" style="font-size:9px;color:#43a047"></i> <?php echo esc_html($r[0]); ?></a></li>
            <?php endforeach; ?>
          </ul>
        </div>
        <?php endif; ?>

        <!-- Sort By -->
        <div class="bh-sidebar-widget">
          <h3 class="bh-sidebar-widget__title"><i class="fas fa-sort"></i> <?php _e('Sort By','bazaarhub'); ?></h3>
          <ul class="bh-sidebar-sort-list">
            <?php
            $current = isset($_GET['orderby']) ? sanitize_text_field($_GET['orderby']) : 'menu_order';
            $sorts = [
                'menu_order'  => __('Default','bazaarhub'),
                'popularity'  => __('Most Popular','bazaarhub'),
                'rating'      => __('Top Rated','bazaarhub'),
                'date'        => __('Newest First','bazaarhub'),
                'price'       => __('Price: Low → High','bazaarhub'),
                'price-desc'  => __('Price: High → Low','bazaarhub'),
            ];
            foreach($sorts as $val=>$label):
              $url    = add_query_arg('orderby', $val);
              $active = ($current===$val) ? 'active' : '';
            ?>
            <li><a href="<?php echo esc_url($url); ?>" class="<?php echo $active; ?>"><?php echo esc_html($label); ?></a></li>
            <?php endforeach; ?>
          </ul>
        </div>

        <!-- Offers -->
        <div class="bh-sidebar-widget">
          <h3 class="bh-sidebar-widget__title"><i class="fas fa-percent"></i> <?php _e('Offers','bazaarhub'); ?></h3>
          <ul class="bh-sidebar-sort-list">
            <li><a href="<?php echo esc_url(add_query_arg('on_sale','1')); ?>" class="<?php echo isset($_GET['on_sale'])?'active':''; ?>">
              <i class="fas fa-fire" style="color:#e53935"></i> <?php _e('On Sale','bazaarhub'); ?>
            </a></li>
            <li><a href="<?php echo esc_url(add_query_arg('featured','1')); ?>" class="<?php echo isset($_GET['featured'])?'active':''; ?>">
              <i class="fas fa-star" style="color:#f57f17"></i> <?php _e('Featured','bazaarhub'); ?>
            </a></li>
          </ul>
        </div>

        <!-- Sidebar Brand Card -->
        <div class="bh-sidebar-brand-card">
          <div class="bh-sidebar-brand-card__icon"><i class="fas fa-store"></i></div>
          <h4><?php _e('Modhu Bazar Shop','bazaarhub'); ?></h4>
          <p><?php _e('Trusted online store with 100% authentic products & fast delivery across Bangladesh.','bazaarhub'); ?></p>
          <div class="bh-sidebar-brand-card__badges">
            <span><i class="fas fa-shield-alt"></i> <?php _e('Secure Payment','bazaarhub'); ?></span>
            <span><i class="fas fa-truck"></i> <?php _e('Fast Delivery','bazaarhub'); ?></span>
            <span><i class="fas fa-undo"></i> <?php _e('Easy Returns','bazaarhub'); ?></span>
          </div>
        </div>

      </aside>

      <!-- Main Content -->
      <div class="bh-shop-content">
        <?php if(apply_filters('woocommerce_show_page_title',true)): ?>
          <h1 class="bh-shop-title"><?php woocommerce_page_title(); ?></h1>
        <?php endif; ?>
        <?php do_action('woocommerce_archive_description'); ?>
        <div class="bh-shop-toolbar">
          <?php woocommerce_result_count(); ?>
          <?php woocommerce_catalog_ordering(); ?>
        </div>
        <?php if(woocommerce_product_loop()):
          woocommerce_product_loop_start();
          while(have_posts()){ the_post(); wc_get_template_part('content','product'); }
          woocommerce_product_loop_end();
          do_action('woocommerce_after_shop_loop');
        else:
          do_action('woocommerce_no_products_found');
        endif; ?>
      </div>

    </div>
  </div>
</div>
<?php get_footer('shop'); ?>
