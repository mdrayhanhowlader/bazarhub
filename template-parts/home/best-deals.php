<?php
$title    = bazaarhub_get_option('deals_title','Best Deals Today');
$cat_slug = bazaarhub_get_option('deals_category','');
$count    = absint(bazaarhub_get_option('deals_count',8));

$cats = get_terms(['taxonomy'=>'product_cat','hide_empty'=>true,'parent'=>0,'number'=>12,'orderby'=>'count','order'=>'DESC']);

// Priority: manually tagged "Hot Deal" products; fallback to category/date
$query = bh_get_section_products('bh_hot_deal', $count);
if (!$query->have_posts()) {
    $args = ['post_type'=>'product','post_status'=>'publish','posts_per_page'=>$count,'orderby'=>'date','order'=>'DESC'];
    if ($cat_slug) $args['tax_query'] = [['taxonomy'=>'product_cat','field'=>'slug','terms'=>$cat_slug]];
    $query = new WP_Query($args);
}
if (!$query->have_posts()) return;
?>
<section class="bh-deals" id="bh-deals">

  <!-- Flash Sale Hero Bar -->
  <div class="bh-deals__hero">
    <div class="bh-container bh-deals__hero-inner">
      <div class="bh-deals__hero-left">
        <span class="bh-deals__flash-badge"><i class="fas fa-bolt"></i> Flash Sale</span>
        <div>
          <h2 class="bh-deals__hero-title"><i class="fas fa-fire" style="color:#ff6f00"></i> <?php echo esc_html($title); ?></h2>
          <p class="bh-deals__hero-sub"><?php _e('Limited time offers — Grab before they\'re gone!','bazaarhub'); ?></p>
        </div>
      </div>
      <div style="display:flex;align-items:center;gap:16px;flex-wrap:wrap">
        <div class="bh-deals__countdown">
          <span class="bh-deals__countdown-label"><?php _e('Ends in:','bazaarhub'); ?></span>
          <?php
          $raw_end  = bazaarhub_get_option('deals_timer_end','');
          $end_date = '';
          if ($raw_end) {
              $ts = strtotime($raw_end);
              if ($ts && $ts > time()) {
                  $end_date = date('Y-m-d\TH:i:s', $ts);
              }
          }
          if (!$end_date) {
              $end_date = date('Y-m-d', strtotime('+1 day')) . 'T23:59:59';
          }
          ?>
          <div class="bh-countdown" id="bh-deals-countdown" data-end="<?php echo esc_attr($end_date); ?>">
            <div class="bh-countdown__item"><span class="bh-countdown__num" id="bh-cd-h">00</span><span class="bh-countdown__lbl">Hrs</span></div>
            <span class="bh-countdown__sep">:</span>
            <div class="bh-countdown__item"><span class="bh-countdown__num" id="bh-cd-m">00</span><span class="bh-countdown__lbl">Min</span></div>
            <span class="bh-countdown__sep">:</span>
            <div class="bh-countdown__item"><span class="bh-countdown__num" id="bh-cd-s">00</span><span class="bh-countdown__lbl">Sec</span></div>
          </div>
        </div>
        <a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop')).'?orderby=date'); ?>" class="bh-deals__viewall"><?php _e('View All','bazaarhub'); ?> <i class="fas fa-arrow-right"></i></a>
      </div>
    </div>
  </div>

  <div class="bh-container">
    <div class="bh-deals__body">

    <?php if (!is_wp_error($cats) && !empty($cats)): ?>
    <div class="bh-cattabs-wrap">
      <div class="swiper bh-cattabs-swiper bh-deals-tabs-swiper">
        <div class="swiper-wrapper">
          <div class="swiper-slide bh-cattabs__slide">
            <button class="bh-cattabs__btn active" data-cat="" data-section="deals"><?php
              ?><div class="bh-cattabs__img"><i class="fas fa-fire"></i></div>
              <span class="bh-cattabs__name"><?php _e('All','bazaarhub'); ?></span>
            </button>
          </div>
          <?php foreach ($cats as $cat):
            $tid = get_term_meta($cat->term_id,'thumbnail_id',true);
            $img = $tid ? wp_get_attachment_image_url($tid,'thumbnail') : '';
          ?>
          <div class="swiper-slide bh-cattabs__slide">
            <button class="bh-cattabs__btn" data-cat="<?php echo esc_attr($cat->slug); ?>" data-section="deals">
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
        <a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop')).'?orderby=date'); ?>" class="bh-promo-card bh-promo-card--green">
          <span class="bh-promo-card__label"><?php _e('Hot Today','bazaarhub'); ?></span>
          <h3 class="bh-promo-card__title"><?php _e('Best Deals of the Day','bazaarhub'); ?></h3>
          <small class="bh-promo-card__sub"><?php _e('New products daily','bazaarhub'); ?></small>
          <span class="bh-promo-card__btn"><?php _e('Shop Now!','bazaarhub'); ?></span>
        </a>
        <a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop')).'?sale=1'); ?>" class="bh-promo-card bh-promo-card--orange">
          <span class="bh-promo-card__label"><?php _e('Sale','bazaarhub'); ?></span>
          <h3 class="bh-promo-card__title"><?php _e('Up to 50% Off','bazaarhub'); ?></h3>
          <small class="bh-promo-card__sub"><?php _e('Limited time offer','bazaarhub'); ?></small>
          <span class="bh-promo-card__btn"><?php _e('Grab Now!','bazaarhub'); ?></span>
        </a>
      </div>

      <div id="bh-deals-products" class="bh-catshow__products">
        <?php while ($query->have_posts()): $query->the_post();
          $product = wc_get_product(get_the_ID());
          if (!$product) continue;
          $in_wl = bazaarhub_is_in_wishlist(get_the_ID());
        ?>
        <div class="bh-product-card" data-pid="<?php the_ID(); ?>">
          <div class="bh-product-card__img">
            <a href="<?php the_permalink(); ?>">
              <?php if (has_post_thumbnail()): the_post_thumbnail('bazaarhub-product-card');
              else: ?><img src="<?php echo esc_url(wc_placeholder_img_src()); ?>" alt=""><?php endif; ?>
            </a>
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
    </div><!-- /.bh-deals__body -->

  </div>
</section>
