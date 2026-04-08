<?php
$title = get_theme_mod('catshow_title', 'Shop by Categories');
$cats  = get_terms(['taxonomy'=>'product_cat','hide_empty'=>true,'parent'=>0,
                    'number'=>12,'orderby'=>'count','order'=>'DESC']);
if (empty($cats) || is_wp_error($cats)) return;
$first_cat = $cats[0];
?>
<section class="bh-catshow">
  <div class="bh-container">

    <div class="bh-section-header">
      <h2 class="bh-section-title"><?php echo esc_html($title); ?></h2>
      <a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>" class="bh-view-all">View All <i class="fas fa-arrow-right"></i></a>
    </div>

    <!-- Category Tabs — Swiper on mobile, scroll on desktop -->
    <div class="bh-cattabs-wrap">
      <div class="swiper bh-cattabs-swiper">
        <div class="swiper-wrapper">
          <?php foreach ($cats as $i => $cat):
            $tid = get_term_meta($cat->term_id, 'thumbnail_id', true);
            $img = $tid ? wp_get_attachment_image_url($tid, 'thumbnail') : '';
          ?>
          <div class="swiper-slide bh-cattabs__slide">
            <button class="bh-cattabs__btn <?php echo $i === 0 ? 'active' : ''; ?>"
              data-cat="<?php echo esc_attr($cat->slug); ?>">
              <div class="bh-cattabs__img">
                <?php if ($img): ?>
                  <img src="<?php echo esc_url($img); ?>" alt="<?php echo esc_attr($cat->name); ?>">
                <?php else: ?>
                  <i class="fas fa-tag"></i>
                <?php endif; ?>
              </div>
              <span class="bh-cattabs__name"><?php echo esc_html($cat->name); ?></span>
            </button>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>

    <!-- Main Layout: promo cards + products -->
    <div class="bh-catshow__body">

      <!-- Promo Cards -->
      <div class="bh-catshow__promos">
        <a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>" class="bh-promo-card bh-promo-card--green">
          <span class="bh-promo-card__label"><?php _e('Special Offer','bazaarhub'); ?></span>
          <h3 class="bh-promo-card__title"><?php _e('Fresh Products Daily','bazaarhub'); ?></h3>
          <small class="bh-promo-card__sub"><?php _e('Up to 30% off','bazaarhub'); ?></small>
          <span class="bh-promo-card__btn"><?php _e('Order Now!','bazaarhub'); ?></span>
        </a>
        <a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>" class="bh-promo-card bh-promo-card--orange">
          <span class="bh-promo-card__label"><?php _e('Hot Deal','bazaarhub'); ?></span>
          <h3 class="bh-promo-card__title"><?php _e('Best Sellers','bazaarhub'); ?></h3>
          <small class="bh-promo-card__sub"><?php _e('Limited time offer','bazaarhub'); ?></small>
          <span class="bh-promo-card__btn"><?php _e('Order Now!','bazaarhub'); ?></span>
        </a>
      </div>

      <!-- Products Grid -->
      <div id="bh-catshow2-products" class="bh-catshow__products">
        <?php
        $pquery = new WP_Query([
          'post_type'      => 'product',
          'post_status'    => 'publish',
          'posts_per_page' => 8,
          'tax_query'      => [[
            'taxonomy' => 'product_cat',
            'field'    => 'slug',
            'terms'    => $first_cat->slug,
          ]],
        ]);
        while ($pquery->have_posts()): $pquery->the_post();
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
              <i class="fas fa-cart-plus"></i> <?php _e('Add to Cart','bazaarhub'); ?>
            </button>
          </div>
        </div>
        <?php endwhile; wp_reset_postdata(); ?>
      </div>

    </div>
  </div>
</section>
