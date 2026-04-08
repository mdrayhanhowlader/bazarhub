<?php
$count = absint(bazaarhub_get_option('arrivals_count', 8));
$query = new WP_Query(['post_type'=>'product','post_status'=>'publish','posts_per_page'=>$count,'orderby'=>'date','order'=>'DESC']);
if (!$query->have_posts()) return;
?>
<section class="bh-new-arrivals" id="bh-new-arrivals">
  <div class="bh-container">

    <!-- Section Header -->
    <div class="bh-arrivals__header">
      <div class="bh-arrivals__header-left">
        <span class="bh-arrivals__accent-bar"></span>
        <div>
          <span class="bh-arrivals__label"><?php _e('Just Dropped','bazaarhub'); ?></span>
          <h2 class="bh-arrivals__title"><i class="fas fa-star" style="color:#f59e0b"></i> <?php _e('New Arrivals','bazaarhub'); ?></h2>
        </div>
      </div>
      <a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop')).'?orderby=date'); ?>" class="bh-arrivals__viewall">
        <?php _e('View All','bazaarhub'); ?> <i class="fas fa-arrow-right"></i>
      </a>
    </div>

    <!-- Product Grid -->
    <div class="bh-arrivals__grid">
      <?php $i=0; while ($query->have_posts()): $query->the_post();
        $product = wc_get_product(get_the_ID());
        if (!$product) continue;
        $in_wl = bazaarhub_is_in_wishlist(get_the_ID());
        $i++;
      ?>
      <div class="bh-product-card bh-arrivals__card" data-pid="<?php the_ID(); ?>">
        <div class="bh-product-card__img">
          <a href="<?php the_permalink(); ?>">
            <?php if (has_post_thumbnail()): the_post_thumbnail('bazaarhub-product-card');
            else: ?><img src="<?php echo esc_url(wc_placeholder_img_src()); ?>" alt="placeholder">
            <?php endif; ?>
          </a>
          <span class="bh-arrivals__new-badge"><i class="fas fa-certificate"></i> NEW</span>
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
          <button class="bh-btn bh-btn--green bh-add-to-cart" data-pid="<?php the_ID(); ?>"><i class="fas fa-cart-plus"></i> <?php _e('Add to Cart','bazaarhub'); ?></button>
        </div>
      </div>
      <?php endwhile; wp_reset_postdata(); ?>
    </div>

  </div>
</section>
