<?php
$deals = [
  ['label'=>'Flash Sale',  'title'=>'Electronics',     'sub'=>'Up to 40% off',   'icon'=>'fas fa-bolt',       'gradient'=>'linear-gradient(135deg,#1565c0,#42a5f5)', 'i'=>3],
  ['label'=>'Hot Deal',    'title'=>'Fashion & Style',  'sub'=>'New Collection',  'icon'=>'fas fa-tshirt',     'gradient'=>'linear-gradient(135deg,#6a1b9a,#ab47bc)', 'i'=>4],
  ['label'=>'Best Price',  'title'=>'Home & Kitchen',   'sub'=>'Save up to 30%',  'icon'=>'fas fa-home',       'gradient'=>'linear-gradient(135deg,#e65100,#ff8f00)', 'i'=>5],
  ['label'=>'Mega Offer',  'title'=>'Gadgets & More',   'sub'=>'Exclusive deals', 'icon'=>'fas fa-microchip',  'gradient'=>'linear-gradient(135deg,#2e7d32,#66bb6a)', 'i'=>6],
];
?>
<section class="bh-offer-banners">
  <div class="bh-container">

    <!-- Section Header -->
    <div class="bh-offer-banners__header">
      <div class="bh-offer-banners__header-left">
        <span class="bh-offer-banners__accent-bar"></span>
        <div>
          <span class="bh-offer-banners__label"><i class="fas fa-fire"></i> <?php _e('Limited Time','bazaarhub'); ?></span>
          <h2 class="bh-offer-banners__title"><i class="fas fa-tags" style="color:#f44336"></i> <?php _e('Special Deals','bazaarhub'); ?></h2>
        </div>
      </div>
      <a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>" class="bh-offer-banners__viewall">
        <?php _e('All Deals','bazaarhub'); ?> <i class="fas fa-arrow-right"></i>
      </a>
    </div>

    <!-- Grid -->
    <div class="bh-offer-banners__grid">
      <?php foreach ($deals as $d):
        $img = get_theme_mod("offer_image_{$d['i']}", '');
        $url = get_theme_mod("offer_url_{$d['i']}", get_permalink(wc_get_page_id('shop')));
        $lbl = get_theme_mod("offer_label_{$d['i']}", $d['title']);
      ?>
      <a href="<?php echo esc_url($url); ?>" class="bh-offer-banner">
        <?php if ($img): ?>
          <img src="<?php echo esc_url($img); ?>" alt="<?php echo esc_attr($lbl); ?>">
        <?php else: ?>
          <div class="bh-offer-banner__card" style="background:<?php echo $d['gradient']; ?>">
            <i class="<?php echo $d['icon']; ?> bh-offer-banner__icon"></i>
            <span class="bh-offer-banner__badge"><?php echo esc_html($d['label']); ?></span>
            <h4 class="bh-offer-banner__title"><?php echo esc_html($lbl ?: $d['title']); ?></h4>
            <p class="bh-offer-banner__sub"><?php echo esc_html($d['sub']); ?></p>
            <span class="bh-offer-banner__cta"><?php _e('Shop Now','bazaarhub'); ?> <i class="fas fa-arrow-right"></i></span>
          </div>
        <?php endif; ?>
      </a>
      <?php endforeach; ?>
    </div>

  </div>
</section>
