<?php defined('ABSPATH') || exit; get_header(); ?>
<div class="bh-single-product-page">
<div class="bh-container">
<?php woocommerce_breadcrumb(); ?>
<?php do_action('woocommerce_before_single_product'); ?>
<?php while(have_posts()): the_post(); global $product; ?>

<div class="bh-sp-layout">
  <!-- Gallery -->
  <div class="bh-sp-gallery">
    <?php woocommerce_show_product_images(); ?>
  </div>

  <!-- Info -->
  <div class="bh-sp-summary">
    <?php $cats = wc_get_product_category_list($product->get_id(),'|','<span class="bh-sp-cat">','</span>');
    if($cats) echo '<div class="bh-sp-cats">'.$cats.'</div>'; ?>

    <h1 class="bh-sp-title"><?php the_title(); ?></h1>

    <div class="bh-sp-rating-row">
      <?php if(wc_review_ratings_enabled()): ?>
        <div class="bh-sp-stars"><?php echo wc_get_rating_html($product->get_average_rating()); ?></div>
        <a href="#reviews" class="bh-sp-review-count">(<?php echo $product->get_review_count(); ?> Reviews)</a>
      <?php endif; ?>
      <?php if($product->is_in_stock()): ?>
        <span class="bh-sp-stock in-stock"><i class="fas fa-check-circle"></i> In Stock</span>
      <?php else: ?>
        <span class="bh-sp-stock out-of-stock"><i class="fas fa-times-circle"></i> Out of Stock</span>
      <?php endif; ?>
    </div>

    <div class="bh-sp-price"><?php echo $product->get_price_html(); ?></div>

    <?php if($product->get_short_description()): ?>
      <div class="bh-sp-short-desc"><?php echo wpautop($product->get_short_description()); ?></div>
    <?php endif; ?>

    <div class="bh-sp-actions">
      <?php woocommerce_template_single_add_to_cart(); ?>
    </div>

    <?php $in_wl = bazaarhub_is_in_wishlist($product->get_id()); $pid = $product->get_id(); ?>
    <div class="bh-sp-extra-actions">
      <button class="bh-sp-wishlist-btn bh-wishlist-btn <?php echo $in_wl ? 'active' : ''; ?>" data-pid="<?php echo $pid; ?>">
        <i class="<?php echo $in_wl ? 'fas' : 'far'; ?> fa-heart"></i>
        <span><?php echo $in_wl ? 'Remove from Wishlist' : 'Add to Wishlist'; ?></span>
      </button>
    </div>

    <div class="bh-sp-meta">
      <?php if($product->get_sku()): ?>
        <div class="bh-sp-meta-item"><strong>SKU:</strong> <?php echo $product->get_sku(); ?></div>
      <?php endif; ?>
      <?php $cat_list = wc_get_product_category_list($pid,', '); if($cat_list): ?>
        <div class="bh-sp-meta-item"><strong>Category:</strong> <?php echo $cat_list; ?></div>
      <?php endif; ?>
      <?php $tags = wc_get_product_tag_list($pid,', '); if($tags): ?>
        <div class="bh-sp-meta-item"><strong>Tags:</strong> <?php echo $tags; ?></div>
      <?php endif; ?>
    </div>

    <div class="bh-sp-trust">
      <div class="bh-trust-item"><i class="fas fa-truck"></i><span>Free Delivery</span></div>
      <div class="bh-trust-item"><i class="fas fa-shield-alt"></i><span>Secure Pay</span></div>
      <div class="bh-trust-item"><i class="fas fa-undo"></i><span>Easy Return</span></div>
    </div>
  </div>
</div>

<div class="bh-sp-tabs-section">
  <?php woocommerce_output_product_data_tabs(); ?>
</div>

<?php woocommerce_output_related_products(); ?>
<?php endwhile; ?>
<?php do_action('woocommerce_after_single_product'); ?>
</div>
</div>
<?php get_footer(); ?>
