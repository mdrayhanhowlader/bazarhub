<?php
$banners = [
  [
    'bg'      => 'linear-gradient(135deg,#1b5e20 0%,#2e7d32 50%,#43a047 100%)',
    'icon'    => 'fas fa-leaf',
    'sup'     => __('Just Arrived','bazaarhub'),
    'title'   => __('New Arrivals','bazaarhub'),
    'sub'     => __('Fresh products added every week','bazaarhub'),
    'cta'     => __('Explore Now','bazaarhub'),
    'url'     => add_query_arg('orderby','date', get_permalink(wc_get_page_id('shop'))),
    'deco'    => '✦ NEW',
  ],
  [
    'bg'      => 'linear-gradient(135deg,#b71c1c 0%,#e53935 50%,#ef5350 100%)',
    'icon'    => 'fas fa-bolt',
    'sup'     => __('Limited Time','bazaarhub'),
    'title'   => __('Big Sale On','bazaarhub'),
    'sub'     => __('Up to 50% off selected items','bazaarhub'),
    'cta'     => __('Shop Sale','bazaarhub'),
    'url'     => add_query_arg('on_sale','1', get_permalink(wc_get_page_id('shop'))),
    'deco'    => '50% OFF',
  ],
];
?>
<section class="bh-promo-banners">
  <div class="bh-container">
    <div class="bh-pb-grid">
      <?php foreach ($banners as $b): ?>
      <a href="<?php echo esc_url($b['url']); ?>" class="bh-pb-card" style="background:<?php echo esc_attr($b['bg']); ?>">

        <!-- Decorative circles -->
        <span class="bh-pb-circle bh-pb-circle--1"></span>
        <span class="bh-pb-circle bh-pb-circle--2"></span>

        <!-- Icon top-left -->
        <div class="bh-pb-icon-wrap">
          <i class="<?php echo esc_attr($b['icon']); ?>"></i>
        </div>

        <!-- Main content -->
        <div class="bh-pb-body">
          <span class="bh-pb-sup"><?php echo esc_html($b['sup']); ?></span>
          <h3 class="bh-pb-title"><?php echo esc_html($b['title']); ?></h3>
          <p class="bh-pb-sub"><?php echo esc_html($b['sub']); ?></p>
          <span class="bh-pb-cta"><?php echo esc_html($b['cta']); ?> <i class="fas fa-arrow-right"></i></span>
        </div>

        <!-- Big decorative text right -->
        <div class="bh-pb-deco"><?php echo esc_html($b['deco']); ?></div>

      </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>
