<?php
/**
 * Dynamic Offer Sections — Homepage
 *
 * Renders each published Offer (Dashboard → Offers) as a product section.
 * Active offers show countdown to end. Upcoming offers show countdown to start.
 */
defined('ABSPATH') || exit;

$offers = bh_get_homepage_offers();
if (empty($offers)) return;

$now = time();

$badge_map = [
    'red'    => ['bg' => '#e53935', 'dark' => '#b71c1c', 'light' => '#ffebee'],
    'orange' => ['bg' => '#ff6f00', 'dark' => '#e65100', 'light' => '#fff3e0'],
    'blue'   => ['bg' => '#1565c0', 'dark' => '#0d47a1', 'light' => '#e3f2fd'],
    'green'  => ['bg' => '#2e7d32', 'dark' => '#1b5e20', 'light' => '#e8f5e9'],
    'purple' => ['bg' => '#6a1b9a', 'dark' => '#4a148c', 'light' => '#f3e5f5'],
];

foreach ($offers as $offer):
    $oid         = $offer->ID;
    $start       = (int)get_post_meta($oid, '_offer_start',       true);
    $end         = (int)get_post_meta($oid, '_offer_end',         true);
    $badge       = get_post_meta($oid, '_offer_badge',       true) ?: 'Flash Sale';
    $badge_color = get_post_meta($oid, '_offer_badge_color', true) ?: 'red';
    $subtitle    = get_post_meta($oid, '_offer_subtitle',    true);
    $count       = (int)(get_post_meta($oid, '_offer_count', true) ?: 8);
    $view_url    = get_post_meta($oid, '_offer_view_url',    true) ?: get_permalink(wc_get_page_id('shop'));
    $icon        = get_post_meta($oid, '_offer_icon',        true) ?: 'fas fa-fire';

    $is_upcoming = ($start > $now);
    $colors      = $badge_map[$badge_color] ?? $badge_map['red'];
    $bg          = $colors['bg'];
    $dark        = $colors['dark'];
    $light       = $colors['light'];

    // Countdown target: upcoming → count to start; active → count to end
    $cd_target = $is_upcoming
        ? date('Y-m-d\TH:i:s', $start)
        : date('Y-m-d\TH:i:s', $end);

    $products = bh_get_offer_products($oid, $count);
    $has_products = $products->have_posts();
?>
<!-- ── Offer: <?php echo esc_html($offer->post_title); ?> ── -->
<section class="bh-offer-section bh-offer-section--<?php echo esc_attr($badge_color); ?>" id="bh-offer-<?php echo $oid; ?>">

  <!-- Hero Bar -->
  <div class="bh-offer-section__hero" style="background:linear-gradient(135deg,<?php echo esc_attr($bg); ?>,<?php echo esc_attr($dark); ?>)">
    <div class="bh-container bh-offer-section__hero-inner">

      <div class="bh-offer-section__hero-left">
        <span class="bh-offer-section__badge-pill">
          <i class="<?php echo esc_attr($icon); ?>"></i> <?php echo esc_html($badge); ?>
        </span>
        <div>
          <h2 class="bh-offer-section__title">
            <?php if ($is_upcoming): ?>
              <span class="bh-offer-section__upcoming-tag"><?php _e('Upcoming','bazaarhub'); ?></span>
            <?php endif; ?>
            <?php echo esc_html($offer->post_title); ?>
          </h2>
          <?php if ($subtitle): ?>
          <p class="bh-offer-section__sub"><?php echo esc_html($subtitle); ?></p>
          <?php endif; ?>
        </div>
      </div>

      <div class="bh-offer-section__hero-right">
        <div class="bh-deals__countdown bh-offer-section__countdown">
          <span class="bh-deals__countdown-label">
            <?php echo $is_upcoming ? __('Starts in:','bazaarhub') : __('Ends in:','bazaarhub'); ?>
          </span>
          <div class="bh-countdown bh-offer-countdown" data-end="<?php echo esc_attr($cd_target); ?>">
            <div class="bh-countdown__item">
              <span class="bh-countdown__num" data-unit="d">00</span>
              <span class="bh-countdown__lbl"><?php _e('Days','bazaarhub'); ?></span>
            </div>
            <span class="bh-countdown__sep">:</span>
            <div class="bh-countdown__item">
              <span class="bh-countdown__num" data-unit="h">00</span>
              <span class="bh-countdown__lbl"><?php _e('Hrs','bazaarhub'); ?></span>
            </div>
            <span class="bh-countdown__sep">:</span>
            <div class="bh-countdown__item">
              <span class="bh-countdown__num" data-unit="m">00</span>
              <span class="bh-countdown__lbl"><?php _e('Min','bazaarhub'); ?></span>
            </div>
            <span class="bh-countdown__sep">:</span>
            <div class="bh-countdown__item">
              <span class="bh-countdown__num" data-unit="s">00</span>
              <span class="bh-countdown__lbl"><?php _e('Sec','bazaarhub'); ?></span>
            </div>
          </div>
        </div>
        <a href="<?php echo esc_url($view_url); ?>" class="bh-offer-section__viewall">
          <?php _e('View All','bazaarhub'); ?> <i class="fas fa-arrow-right"></i>
        </a>
      </div>

    </div>
  </div>

  <div class="bh-container">

    <?php if ($is_upcoming): ?>
    <!-- Upcoming banner -->
    <div class="bh-offer-section__upcoming-wrap" style="border-left:4px solid <?php echo esc_attr($bg); ?>">
      <div class="bh-offer-section__upcoming-icon" style="background:<?php echo esc_attr($light); ?>;color:<?php echo esc_attr($bg); ?>">
        <i class="<?php echo esc_attr($icon); ?>"></i>
      </div>
      <div>
        <h3 style="margin:0 0 4px;color:<?php echo esc_attr($bg); ?>;font-size:16px"><?php _e('Coming Soon!','bazaarhub'); ?></h3>
        <p style="margin:0;color:#555;font-size:13.5px">
          <?php printf(
              /* translators: %s: formatted date */
              __('This offer launches on %s','bazaarhub'),
              '<strong>' . date_i18n(get_option('date_format') . ' ' . get_option('time_format'), $start) . '</strong>'
          ); ?>
        </p>
        <?php if ($has_products): ?>
        <p style="margin:6px 0 0;font-size:12.5px;color:#777"><i class="fas fa-eye" style="margin-right:4px"></i><?php _e('Preview of products in this offer:','bazaarhub'); ?></p>
        <?php endif; ?>
      </div>
    </div>
    <?php endif; ?>

    <?php if ($has_products): ?>
    <div class="bh-offer-section__products<?php echo $is_upcoming ? ' bh-offer-section__products--upcoming' : ''; ?>">
      <?php while ($products->have_posts()): $products->the_post();
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
          <?php if ($is_upcoming): ?>
          <div class="bh-offer-coming-overlay" style="background:<?php echo esc_attr($bg); ?>cc">
            <span><i class="fas fa-clock"></i> <?php _e('Coming Soon','bazaarhub'); ?></span>
          </div>
          <?php endif; ?>
          <div class="bh-product-card__badges"><?php echo bazaarhub_product_badge($product); ?></div>
          <div class="bh-product-card__actions">
            <button class="bh-wishlist-btn <?php echo $in_wl ? 'active' : ''; ?>" data-pid="<?php the_ID(); ?>">
              <i class="<?php echo $in_wl ? 'fas' : 'far'; ?> fa-heart"></i>
            </button>
            <button class="bh-quickview-btn" data-pid="<?php the_ID(); ?>"><i class="fas fa-eye"></i></button>
          </div>
        </div>
        <div class="bh-product-card__body">
          <div class="bh-product-card__cat"><?php echo wc_get_product_category_list(get_the_ID(), '|', '<span>', '</span>'); ?></div>
          <h4 class="bh-product-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
          <div class="bh-product-card__rating">
            <?php echo bazaarhub_render_stars($product->get_average_rating()); ?>
            <span>(<?php echo $product->get_review_count(); ?>)</span>
          </div>
          <div class="bh-product-card__price"><?php echo $product->get_price_html(); ?></div>
          <?php if (!$is_upcoming): ?>
          <button class="bh-btn bh-btn--green bh-add-to-cart" data-pid="<?php the_ID(); ?>">
            <i class="fas fa-cart-plus"></i> <?php _e('Add to Cart','bazaarhub'); ?>
          </button>
          <?php else: ?>
          <button class="bh-btn bh-offer-coming-btn" style="background:<?php echo esc_attr($bg); ?>" disabled>
            <i class="<?php echo esc_attr($icon); ?>"></i> <?php _e('Available Soon','bazaarhub'); ?>
          </button>
          <?php endif; ?>
        </div>
      </div>
      <?php endwhile; wp_reset_postdata(); ?>
    </div>

    <?php elseif (!$is_upcoming): ?>
    <!-- Active but no products assigned yet -->
    <div class="bh-offer-section__empty">
      <i class="fas fa-box-open" style="font-size:36px;color:#ccc;margin-bottom:10px;display:block"></i>
      <p style="color:#888;margin:0 0 12px"><?php _e('No products assigned to this offer yet.','bazaarhub'); ?></p>
      <a href="<?php echo esc_url(admin_url('edit.php?post_type=product')); ?>" class="bh-btn bh-btn--green">
        <i class="fas fa-plus"></i> <?php _e('Assign Products','bazaarhub'); ?>
      </a>
    </div>
    <?php endif; ?>

  </div>
</section>
<?php endforeach; ?>
