<?php
$title = get_theme_mod('bestseller_title','Bestsellers of the Week');
$desc  = get_theme_mod('bestseller_desc','Our most popular products loved by customers.');
$count = absint(get_theme_mod('bestseller_count',8)) ?: 8;
$def_cat = get_theme_mod('bestseller_cat','');

$cats = get_terms(['taxonomy'=>'product_cat','hide_empty'=>true,'parent'=>0,'number'=>12,'orderby'=>'count','order'=>'DESC']);

$args = ['post_type'=>'product','post_status'=>'publish','posts_per_page'=>$count,'meta_key'=>'total_sales','orderby'=>'meta_value_num','order'=>'DESC'];
if ($def_cat) $args['tax_query'] = [['taxonomy'=>'product_cat','field'=>'slug','terms'=>$def_cat]];
$q = new WP_Query($args);
if (!$q->have_posts()) return;
?>
<section class="bh-bestseller">
  <div class="bh-container">

    <div class="bh-section-header">
      <h2 class="bh-section-title"><i class="fas fa-star" style="color:#f59e0b;margin-right:6px"></i><?php echo esc_html($title); ?></h2>
      <a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>" class="bh-view-all"><?php _e('View All','bazaarhub'); ?> <i class="fas fa-arrow-right"></i></a>
    </div>

    <?php if (!is_wp_error($cats) && !empty($cats)): ?>
    <div class="bh-cattabs-wrap">
      <div class="swiper bh-cattabs-swiper bh-bestseller-tabs-swiper">
        <div class="swiper-wrapper">
          <div class="swiper-slide bh-cattabs__slide">
            <button class="bh-cattabs__btn active" data-cat="" data-section="bestseller">
              <div class="bh-cattabs__img"><i class="fas fa-star"></i></div>
              <span class="bh-cattabs__name"><?php _e('All','bazaarhub'); ?></span>
            </button>
          </div>
          <?php foreach ($cats as $cat):
            $tid = get_term_meta($cat->term_id,'thumbnail_id',true);
            $img = $tid ? wp_get_attachment_image_url($tid,'thumbnail') : '';
          ?>
          <div class="swiper-slide bh-cattabs__slide">
            <button class="bh-cattabs__btn" data-cat="<?php echo esc_attr($cat->slug); ?>" data-section="bestseller">
              <div class="bh-cattabs__img">
                <?php if ($img): ?><img src="<?php echo esc_url($img); ?>" alt=""><?php else: ?><i class="fas fa-tag"></i><?php endif; ?>
              </div>
              <span class="bh-cattabs__name"><?php echo esc_html($cat->name); ?></span>
            </button>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
    <?php endif; ?>

    <div class="bh-catshow__body">
      <div class="bh-catshow__promos">
        <a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>" class="bh-promo-card bh-promo-card--green">
          <span class="bh-promo-card__label"><i class="fas fa-star"></i> <?php _e('Bestseller','bazaarhub'); ?></span>
          <h3 class="bh-promo-card__title"><?php echo esc_html($title); ?></h3>
          <small class="bh-promo-card__sub"><?php echo esc_html($desc); ?></small>
          <span class="bh-promo-card__btn"><?php _e('Shop Now!','bazaarhub'); ?></span>
        </a>
        <a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>" class="bh-promo-card bh-promo-card--orange">
          <span class="bh-promo-card__label"><?php _e('Top Rated','bazaarhub'); ?></span>
          <h3 class="bh-promo-card__title"><?php _e('Customer Favorites','bazaarhub'); ?></h3>
          <small class="bh-promo-card__sub"><?php _e('Most loved products','bazaarhub'); ?></small>
          <span class="bh-promo-card__btn"><?php _e('Explore!','bazaarhub'); ?></span>
        </a>
      </div>

      <div id="bh-bestseller-products" class="bh-catshow__products">
        <?php while ($q->have_posts()): $q->the_post();
          $product = wc_get_product(get_the_ID());
          if (!$product) continue;
          $in_wl = bazaarhub_is_in_wishlist(get_the_ID());
        ?>
        <div class="bh-product-card" data-pid="<?php the_ID(); ?>">
          <div class="bh-product-card__img">
            <a href="<?php the_permalink(); ?>">
              <?php if (has_post_thumbnail()): the_post_thumbnail('bazaarhub-product-card');
              else: ?><img src="<?php echo wc_placeholder_img_src(); ?>" alt=""><?php endif; ?>
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
            <button class="bh-btn bh-btn--green bh-add-to-cart" data-pid="<?php the_ID(); ?>"><i class="fas fa-cart-plus"></i> <?php _e('Add to Cart','bazaarhub'); ?></button>
          </div>
        </div>
        <?php endwhile; wp_reset_postdata(); ?>
      </div>
    </div>

  </div>
</section>
