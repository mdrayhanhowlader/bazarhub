<?php
$query = new WP_Query(['post_type'=>'product','post_status'=>'publish','posts_per_page'=>8,
  'tax_query'=>[['taxonomy'=>'product_visibility','field'=>'name','terms'=>'featured']],'orderby'=>'date','order'=>'DESC']);
if (!$query->have_posts()) {
    $query = new WP_Query(['post_type'=>'product','post_status'=>'publish','posts_per_page'=>8,'orderby'=>'popularity','order'=>'DESC']);
}
if (!$query->have_posts()) return;
$total = $query->post_count;
$show_btn = $total > 4;
?>
<section class="bh-featured-products" id="bh-featured">
  <div class="bh-container">
    <div class="bh-section-header">
      <h2 class="bh-section-title"><?php _e("Today's Picks","bazaarhub"); ?></h2>
      <a href="<?php echo esc_url(get_permalink(wc_get_page_id("shop"))); ?>" class="bh-view-all"><?php _e("Explore More","bazaarhub"); ?> <i class="fas fa-arrow-right"></i></a>
    </div>
    <div class="bh-featured__grid" id="bh-featured-grid">
      <?php $i = 0; while($query->have_posts()): $query->the_post();
        $product = wc_get_product(get_the_ID());
        if (!$product) continue;
        $in_wl = bazaarhub_is_in_wishlist(get_the_ID());
        $hidden = ($i >= 4 && $show_btn) ? ' bh-featured__card--hidden' : '';
        $i++;
      ?>
      <div class="bh-product-card<?php echo $hidden; ?>" data-pid="<?php the_ID(); ?>">
        <div class="bh-product-card__img">
          <a href="<?php the_permalink(); ?>">
            <?php if(has_post_thumbnail()): the_post_thumbnail("bazaarhub-product-card");
            else: ?><img src="<?php echo wc_placeholder_img_src(); ?>" alt="placeholder">
            <?php endif; ?>
          </a>
          <div class="bh-product-card__badges"><?php echo bazaarhub_product_badge($product); ?></div>
          <div class="bh-product-card__actions">
            <button class="bh-wishlist-btn <?php echo $in_wl?"active":""; ?>" data-pid="<?php the_ID(); ?>"><i class="<?php echo $in_wl?"fas":"far"; ?> fa-heart"></i></button>
            <button class="bh-quickview-btn" data-pid="<?php the_ID(); ?>"><i class="fas fa-eye"></i></button>
          </div>
        </div>
        <div class="bh-product-card__body">
          <div class="bh-product-card__cat"><?php echo wc_get_product_category_list(get_the_ID(),"|","<span>","</span>"); ?></div>
          <h4 class="bh-product-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
          <div class="bh-product-card__rating"><?php echo bazaarhub_render_stars($product->get_average_rating()); ?><span>(<?php echo $product->get_review_count(); ?>)</span></div>
          <div class="bh-product-card__price"><?php echo $product->get_price_html(); ?></div>
          <button class="bh-btn bh-btn--green bh-add-to-cart" data-pid="<?php the_ID(); ?>"><i class="fas fa-cart-plus"></i> <?php _e("Add to Cart","bazaarhub"); ?></button>
        </div>
      </div>
      <?php endwhile; wp_reset_postdata(); ?>
    </div>
    <?php if($show_btn): ?>
    <div class="bh-featured__showall-wrap">
      <button class="bh-featured__showall" id="bh-featured-showall">
        <i class="fas fa-th-large"></i> <?php _e("Show All","bazaarhub"); ?> (<?php echo $total; ?>)
      </button>
    </div>
    <?php endif; ?>
  </div>
</section>
