<?php
defined('ABSPATH') || exit;
remove_action('woocommerce_before_shop_loop','woocommerce_result_count',20);
remove_action('woocommerce_before_shop_loop','woocommerce_catalog_ordering',30);

$term      = get_queried_object();
$cat_name  = $term ? esc_html($term->name) : __('Products','bazaarhub');
$cat_desc  = $term ? $term->description : '';
$cat_count = $term ? (int)$term->count : 0;
$cat_slug  = $term ? $term->slug : '';
$is_sale   = ! empty($_GET['on_sale']);
$is_feat   = ! empty($_GET['featured']);

// Color map per category slug — fallback cycles through 4 palettes
$palette_map = [
    'electronics'      => ['from'=>'#0d47a1','mid'=>'#1565c0','to'=>'#42a5f5','icon'=>'fas fa-laptop',    'particle'=>'⚡'],
    'fashion'          => ['from'=>'#4a148c','mid'=>'#6a1b9a','to'=>'#ce93d8','icon'=>'fas fa-tshirt',    'particle'=>'✦'],
    'fashion-style'    => ['from'=>'#4a148c','mid'=>'#6a1b9a','to'=>'#ce93d8','icon'=>'fas fa-tshirt',    'particle'=>'✦'],
    'home-kitchen'     => ['from'=>'#bf360c','mid'=>'#d84315','to'=>'#ff8a65','icon'=>'fas fa-blender',   'particle'=>'★'],
    'gadgets'          => ['from'=>'#1b5e20','mid'=>'#2e7d32','to'=>'#66bb6a','icon'=>'fas fa-mobile-alt','particle'=>'◆'],
    'men'              => ['from'=>'#0277bd','mid'=>'#0288d1','to'=>'#4fc3f7','icon'=>'fas fa-male',      'particle'=>'▲'],
    'women'            => ['from'=>'#880e4f','mid'=>'#ad1457','to'=>'#f48fb1','icon'=>'fas fa-female',    'particle'=>'♥'],
    'clothing'         => ['from'=>'#4a148c','mid'=>'#7b1fa2','to'=>'#ba68c8','icon'=>'fas fa-tshirt',    'particle'=>'✦'],
    'uncategorized'    => ['from'=>'#37474f','mid'=>'#455a64','to'=>'#90a4ae','icon'=>'fas fa-box',       'particle'=>'●'],
];
$fallbacks = [
    ['from'=>'#0d47a1','mid'=>'#1565c0','to'=>'#42a5f5','icon'=>'fas fa-star',   'particle'=>'⚡'],
    ['from'=>'#4a148c','mid'=>'#6a1b9a','to'=>'#ce93d8','icon'=>'fas fa-bolt',   'particle'=>'✦'],
    ['from'=>'#bf360c','mid'=>'#d84315','to'=>'#ff8a65','icon'=>'fas fa-fire',   'particle'=>'★'],
    ['from'=>'#1b5e20','mid'=>'#2e7d32','to'=>'#66bb6a','icon'=>'fas fa-leaf',   'particle'=>'◆'],
];

$pal = $palette_map[$cat_slug] ?? $fallbacks[crc32($cat_slug) % 4];

// On sale — always use red/orange palette
if ($is_sale) {
    $pal = ['from'=>'#b71c1c','mid'=>'#c62828','to'=>'#ef5350','icon'=>'fas fa-fire','particle'=>'🔥'];
}

get_header('shop');
?>

<!-- ═══ Category Hero Banner ═══ -->
<div class="bh-cat-hero" style="background:linear-gradient(135deg,<?php echo esc_attr($pal['from']); ?> 0%,<?php echo esc_attr($pal['mid']); ?> 55%,<?php echo esc_attr($pal['to']); ?> 100%);">

  <!-- Animated particles -->
  <span class="bh-cat-hero__p bh-cat-hero__p--1"><?php echo $pal['particle']; ?></span>
  <span class="bh-cat-hero__p bh-cat-hero__p--2"><?php echo $pal['particle']; ?></span>
  <span class="bh-cat-hero__p bh-cat-hero__p--3"><?php echo $pal['particle']; ?></span>
  <span class="bh-cat-hero__p bh-cat-hero__p--4"><?php echo $pal['particle']; ?></span>

  <!-- Big decorative ring -->
  <span class="bh-cat-hero__ring bh-cat-hero__ring--1"></span>
  <span class="bh-cat-hero__ring bh-cat-hero__ring--2"></span>

  <div class="bh-container">
    <div class="bh-cat-hero__inner">

      <!-- Icon bubble -->
      <div class="bh-cat-hero__icon-wrap">
        <i class="<?php echo esc_attr($pal['icon']); ?>"></i>
      </div>

      <!-- Text -->
      <div class="bh-cat-hero__text">
        <?php if ($is_sale): ?>
          <span class="bh-cat-hero__badge bh-cat-hero__badge--sale">
            <i class="fas fa-fire"></i> <?php _e('Hot Deals','bazaarhub'); ?>
          </span>
        <?php elseif ($is_feat): ?>
          <span class="bh-cat-hero__badge bh-cat-hero__badge--feat">
            <i class="fas fa-star"></i> <?php _e('Featured','bazaarhub'); ?>
          </span>
        <?php else: ?>
          <span class="bh-cat-hero__badge">
            <i class="fas fa-tag"></i> <?php printf('%d %s', $cat_count, __('Products','bazaarhub')); ?>
          </span>
        <?php endif; ?>

        <h1 class="bh-cat-hero__title">
          <?php if ($is_sale): ?>
            <?php printf('%s — %s', __('Sale','bazaarhub'), $cat_name); ?>
          <?php else: ?>
            <?php echo $cat_name; ?>
          <?php endif; ?>
        </h1>

        <?php if ($is_sale): ?>
          <p class="bh-cat-hero__sub"><?php _e('Limited time discounts on selected items','bazaarhub'); ?></p>
        <?php elseif ($cat_desc): ?>
          <p class="bh-cat-hero__sub"><?php echo wp_strip_all_tags($cat_desc); ?></p>
        <?php else: ?>
          <p class="bh-cat-hero__sub"><?php printf(__('Browse all %s products','bazaarhub'), $cat_name); ?></p>
        <?php endif; ?>

        <div class="bh-cat-hero__actions">
          <a href="<?php echo esc_url(get_term_link($term)); ?>" class="bh-cat-hero__btn bh-cat-hero__btn--all">
            <i class="fas fa-th-large"></i> <?php _e('All Products','bazaarhub'); ?>
          </a>
          <?php if (!$is_sale): ?>
          <a href="<?php echo esc_url(add_query_arg('on_sale','1', get_term_link($term))); ?>" class="bh-cat-hero__btn bh-cat-hero__btn--sale">
            <i class="fas fa-fire"></i> <?php _e('Sale Items','bazaarhub'); ?>
          </a>
          <?php endif; ?>
        </div>
      </div>

      <!-- Big discount badge (only on sale) -->
      <?php if ($is_sale): ?>
      <div class="bh-cat-hero__deal-badge">
        <span class="bh-cat-hero__deal-pct">UP TO</span>
        <span class="bh-cat-hero__deal-num">50%</span>
        <span class="bh-cat-hero__deal-off">OFF</span>
      </div>
      <?php else: ?>
      <div class="bh-cat-hero__deco-text"><?php echo strtoupper(mb_substr($cat_name,0,4)); ?></div>
      <?php endif; ?>

    </div>
  </div>
</div>

<!-- ═══ Shop Layout ═══ -->
<div class="bh-shop-page bh-shop-page--no-top">
  <div class="bh-container">

    <!-- Mobile filter toggle -->
    <button class="bh-mobile-filter-toggle" id="bh-mobile-filter-toggle">
      <i class="fas fa-sliders-h"></i> <?php _e('Filter & Sort','bazaarhub'); ?>
    </button>

    <div class="bh-shop-layout">

      <!-- Sidebar -->
      <aside class="bh-shop-sidebar" id="bh-shop-sidebar">

        <!-- Search -->
        <div class="bh-sidebar-widget bh-sidebar-search">
          <h3 class="bh-sidebar-widget__title"><i class="fas fa-search"></i> <?php _e('Search Products','bazaarhub'); ?></h3>
          <form class="bh-sidebar-searchform" action="<?php echo esc_url(home_url('/')); ?>" method="get">
            <input type="text" name="s" value="<?php echo esc_attr(get_search_query()); ?>" placeholder="<?php _e('Search...','bazaarhub'); ?>">
            <input type="hidden" name="post_type" value="product">
            <button type="submit"><i class="fas fa-search"></i></button>
          </form>
        </div>

        <!-- Sub-categories (if any) -->
        <?php
        $sub_cats = get_terms(['taxonomy'=>'product_cat','parent'=>$term->term_id,'hide_empty'=>true,'orderby'=>'name']);
        if (!is_wp_error($sub_cats) && !empty($sub_cats)):
        ?>
        <div class="bh-sidebar-widget bh-sidebar-cats">
          <h3 class="bh-sidebar-widget__title"><i class="fas fa-list"></i> <?php _e('Sub-Categories','bazaarhub'); ?></h3>
          <ul class="bh-sidebar-cat-list">
            <?php foreach ($sub_cats as $sc):
              $tid = get_term_meta($sc->term_id,'thumbnail_id',true);
              $img = $tid ? wp_get_attachment_image_url($tid,[24,24]) : '';
            ?>
            <li>
              <a href="<?php echo esc_url(get_term_link($sc)); ?>">
                <span class="bh-sidebar-cat-thumb">
                  <?php if ($img): ?><img src="<?php echo esc_url($img); ?>" alt=""><?php else: ?><i class="fas fa-tag"></i><?php endif; ?>
                </span>
                <span><?php echo esc_html($sc->name); ?></span>
                <small><?php echo (int)$sc->count; ?></small>
              </a>
            </li>
            <?php endforeach; ?>
          </ul>
        </div>
        <?php else: ?>
        <!-- All top-level categories -->
        <div class="bh-sidebar-widget bh-sidebar-cats">
          <h3 class="bh-sidebar-widget__title"><i class="fas fa-list"></i> <?php _e('Categories','bazaarhub'); ?></h3>
          <ul class="bh-sidebar-cat-list">
            <?php
            $all_cats = get_terms(['taxonomy'=>'product_cat','hide_empty'=>true,'parent'=>0,'orderby'=>'name']);
            if (!is_wp_error($all_cats)) foreach ($all_cats as $cat):
              $active = ($cat->slug === $cat_slug) ? 'active' : '';
              $tid = get_term_meta($cat->term_id,'thumbnail_id',true);
              $img = $tid ? wp_get_attachment_image_url($tid,[24,24]) : '';
            ?>
            <li class="<?php echo $active; ?>">
              <a href="<?php echo esc_url(get_term_link($cat)); ?>">
                <span class="bh-sidebar-cat-thumb">
                  <?php if ($img): ?><img src="<?php echo esc_url($img); ?>" alt=""><?php else: ?><i class="fas fa-tag"></i><?php endif; ?>
                </span>
                <span><?php echo esc_html($cat->name); ?></span>
                <small><?php echo (int)$cat->count; ?></small>
              </a>
            </li>
            <?php endforeach; ?>
          </ul>
        </div>
        <?php endif; ?>

        <!-- Price Filter -->
        <?php
        $wc_price_widget = false;
        if (is_active_sidebar('shop-sidebar')) {
            global $wp_registered_widgets, $sidebars_widgets;
            $sw = $sidebars_widgets['shop-sidebar'] ?? [];
            foreach ($sw as $wid) {
                if (isset($wp_registered_widgets[$wid]['classname']) && strpos($wp_registered_widgets[$wid]['classname'],'woocommerce_price_filter') !== false) {
                    $wc_price_widget = true; break;
                }
            }
        }
        if ($wc_price_widget): ?>
        <div class="bh-sidebar-widget bh-sidebar-price-filter">
          <h3 class="bh-sidebar-widget__title"><i class="fas fa-sliders-h"></i> <?php _e('Filter by Price','bazaarhub'); ?></h3>
          <?php
          global $wp_registered_widgets, $sidebars_widgets;
          $sw = $sidebars_widgets['shop-sidebar'] ?? [];
          foreach ($sw as $wid) {
              $cls = $wp_registered_widgets[$wid]['classname'] ?? '';
              if (strpos($cls,'woocommerce_price_filter') !== false || strpos($wid,'woocommerce_price_filter') !== false) {
                  $callback = $wp_registered_widgets[$wid]['callback'];
                  $params   = $wp_registered_widgets[$wid]['params'][0] ?? [];
                  $params['widget_id']   = $wid;
                  $params['widget_name'] = $wp_registered_widgets[$wid]['name'];
                  call_user_func($callback, [], $params);
              }
          }
          ?>
        </div>
        <?php else: ?>
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
              $url = get_term_link($term) . '?' . $r[1];
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
            foreach ($sorts as $val => $label):
              $active = ($current === $val) ? 'active' : '';
            ?>
            <li><a href="<?php echo esc_url(add_query_arg('orderby',$val)); ?>" class="<?php echo $active; ?>"><?php echo esc_html($label); ?></a></li>
            <?php endforeach; ?>
          </ul>
        </div>

        <!-- Offers -->
        <div class="bh-sidebar-widget">
          <h3 class="bh-sidebar-widget__title"><i class="fas fa-percent"></i> <?php _e('Offers','bazaarhub'); ?></h3>
          <ul class="bh-sidebar-sort-list">
            <li>
              <a href="<?php echo esc_url(add_query_arg('on_sale','1', get_term_link($term))); ?>" class="<?php echo $is_sale ? 'active' : ''; ?>">
                <i class="fas fa-fire" style="color:#e53935"></i> <?php _e('On Sale','bazaarhub'); ?>
              </a>
            </li>
            <li>
              <a href="<?php echo esc_url(add_query_arg('featured','1', get_term_link($term))); ?>" class="<?php echo $is_feat ? 'active' : ''; ?>">
                <i class="fas fa-star" style="color:#f57f17"></i> <?php _e('Featured','bazaarhub'); ?>
              </a>
            </li>
          </ul>
        </div>

        <!-- Brand Card -->
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
        <div class="bh-shop-toolbar">
          <?php woocommerce_result_count(); ?>
          <?php woocommerce_catalog_ordering(); ?>
        </div>
        <?php if (woocommerce_product_loop()):
          woocommerce_product_loop_start();
          while (have_posts()) { the_post(); wc_get_template_part('content','product'); }
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
