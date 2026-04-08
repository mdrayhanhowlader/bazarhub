<section class="bh-promo-banners">
  <div class="bh-container">
    <div class="bh-promo-banners__grid">
      <?php for($i=1;$i<=2;$i++):
        $img = get_theme_mod("offer_image_".($i+2),"");
        $url = get_theme_mod("offer_url_".($i+2), get_permalink(wc_get_page_id('shop')));
        $lbl = get_theme_mod("offer_label_".($i+2),"Hot Deal");
      ?>
        <a href="<?php echo esc_url($url); ?>" class="bh-promo-banner">
          <?php if($img): ?><img src="<?php echo esc_url($img); ?>" alt="<?php echo esc_attr($lbl); ?>">
          <?php else: ?><div class="bh-promo-placeholder"><i class="fas fa-tag"></i><span><?php echo esc_html($lbl); ?></span></div>
          <?php endif; ?>
        </a>
      <?php endfor; ?>
    </div>
  </div>
</section>
