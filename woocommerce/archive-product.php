<?php defined('ABSPATH') || exit; get_header('shop'); ?>
<div class="bh-shop-page">
  <div class="bh-container">
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

        <!-- Price Filter -->
        <?php if(is_active_sidebar('shop-sidebar')): ?>
        <div class="bh-sidebar-widget">
          <h3 class="bh-sidebar-widget__title"><i class="fas fa-sliders-h"></i> Filter by Price</h3>
          <?php dynamic_sidebar('shop-sidebar'); ?>
        </div>
        <?php endif; ?>

        <!-- Sort By -->
        <div class="bh-sidebar-widget">
          <h3 class="bh-sidebar-widget__title"><i class="fas fa-sort"></i> Sort By</h3>
          <ul class="bh-sidebar-sort-list">
            <?php
            $current = isset($_GET['orderby']) ? $_GET['orderby'] : 'menu_order';
            $sorts = ['menu_order'=>'Default','popularity'=>'Popularity','rating'=>'Average Rating','date'=>'Latest','price'=>'Price: Low to High','price-desc'=>'Price: High to Low'];
            foreach($sorts as $val=>$label):
              $url = add_query_arg('orderby',$val);
              $active = ($current==$val)?'active':'';
            ?>
            <li><a href="<?php echo esc_url($url); ?>" class="<?php echo $active; ?>"><?php echo $label; ?></a></li>
            <?php endforeach; ?>
          </ul>
        </div>

        <!-- On Sale -->
        <div class="bh-sidebar-widget">
          <h3 class="bh-sidebar-widget__title"><i class="fas fa-percent"></i> Offers</h3>
          <ul class="bh-sidebar-sort-list">
            <li><a href="<?php echo add_query_arg('on_sale','1'); ?>" class="<?php echo isset($_GET['on_sale'])?'active':''; ?>"><i class="fas fa-fire" style="color:#e53935"></i> On Sale</a></li>
            <li><a href="<?php echo add_query_arg('featured','1'); ?>" class="<?php echo isset($_GET['featured'])?'active':''; ?>"><i class="fas fa-star" style="color:#f57f17"></i> Featured</a></li>
          </ul>
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
        <?php do_action('woocommerce_before_shop_loop'); ?>
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
