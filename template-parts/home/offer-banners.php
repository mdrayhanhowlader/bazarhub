<?php
$offers = [
  [
    'i'        => 1,
    'badge'    => __('Flash Sale','bazaarhub'),
    'title'    => __('Electronics','bazaarhub'),
    'discount' => '40% OFF',
    'sub'      => __('Limited time offer','bazaarhub'),
    'icon'     => 'fas fa-laptop',
    'bg'       => 'linear-gradient(135deg,#0d47a1,#1976d2 50%,#42a5f5)',
  ],
  [
    'i'        => 2,
    'badge'    => __('Hot Deal','bazaarhub'),
    'title'    => __('Fashion & Style','bazaarhub'),
    'discount' => '30% OFF',
    'sub'      => __('New collection in','bazaarhub'),
    'icon'     => 'fas fa-tshirt',
    'bg'       => 'linear-gradient(135deg,#4a148c,#7b1fa2 50%,#ce93d8)',
  ],
  [
    'i'        => 3,
    'badge'    => __('Best Price','bazaarhub'),
    'title'    => __('Home & Kitchen','bazaarhub'),
    'discount' => '25% OFF',
    'sub'      => __('Everyday essentials','bazaarhub'),
    'icon'     => 'fas fa-blender',
    'bg'       => 'linear-gradient(135deg,#bf360c,#e64a19 50%,#ff8a65)',
  ],
  [
    'i'        => 4,
    'badge'    => __('Mega Offer','bazaarhub'),
    'title'    => __('Gadgets & More','bazaarhub'),
    'discount' => '35% OFF',
    'sub'      => __('Top brands on sale','bazaarhub'),
    'icon'     => 'fas fa-mobile-alt',
    'bg'       => 'linear-gradient(135deg,#1b5e20,#2e7d32 50%,#66bb6a)',
  ],
];
?>
<section class="bh-offer-banners">
  <div class="bh-container">

    <!-- Section Header -->
    <div class="bh-section-head">
      <div class="bh-section-head__left">
        <span class="bh-section-head__bar"></span>
        <div>
          <span class="bh-section-head__sup"><i class="fas fa-fire"></i> <?php _e('Limited Time','bazaarhub'); ?></span>
          <h2 class="bh-section-head__title"><i class="fas fa-tags" style="color:#f44336"></i> <?php _e('Special Deals','bazaarhub'); ?></h2>
        </div>
      </div>
      <a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>" class="bh-section-head__all">
        <?php _e('All Deals','bazaarhub'); ?> <i class="fas fa-arrow-right"></i>
      </a>
    </div>

    <!-- Grid -->
    <div class="bh-ob-grid">
      <?php foreach ($offers as $o):
        $img      = get_theme_mod("offer_image_{$o['i']}", '');
        $url      = get_theme_mod("offer_url_{$o['i']}",   get_permalink(wc_get_page_id('shop')));
        $title    = get_theme_mod("offer_label_{$o['i']}", $o['title']);
        $badge    = get_theme_mod("offer_badge_{$o['i']}", $o['badge']);
        $discount = get_theme_mod("offer_discount_{$o['i']}", $o['discount']);
        $sub      = get_theme_mod("offer_sub_{$o['i']}",   $o['sub']);
      ?>
      <a href="<?php echo esc_url($url); ?>" class="bh-ob-card bh-ob-card--<?php echo (int)$o['i']; ?>">

        <?php if ($img): ?>
          <img src="<?php echo esc_url($img); ?>" alt="<?php echo esc_attr($title); ?>" class="bh-ob-card__bg-img">
        <?php endif; ?>

        <!-- Decorative circles -->
        <span class="bh-ob-card__circle bh-ob-card__circle--1"></span>
        <span class="bh-ob-card__circle bh-ob-card__circle--2"></span>

        <!-- Left: Text -->
        <div class="bh-ob-card__body">
          <span class="bh-ob-card__badge"><?php echo esc_html($badge); ?></span>
          <h3 class="bh-ob-card__title"><?php echo esc_html($title); ?></h3>
          <p class="bh-ob-card__sub"><?php echo esc_html($sub); ?></p>
          <span class="bh-ob-card__cta"><?php _e('Shop Now','bazaarhub'); ?> <i class="fas fa-arrow-right"></i></span>
        </div>

        <!-- Right: Discount + Icon -->
        <div class="bh-ob-card__right">
          <div class="bh-ob-card__discount"><?php echo esc_html($discount); ?></div>
          <i class="<?php echo esc_attr($o['icon']); ?> bh-ob-card__icon"></i>
        </div>

      </a>
      <?php endforeach; ?>
    </div>

  </div>
</section>
