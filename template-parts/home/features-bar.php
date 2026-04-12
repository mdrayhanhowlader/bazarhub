<section class="bh-features">
  <div class="bh-container">
    <div class="bh-features__grid">
      <?php
      $feature_defaults = [
          1 => ['icon'=>'fas fa-truck',      'title'=>__('Free Delivery','bazaarhub'), 'sub'=>__('On orders over ৳500','bazaarhub')],
          2 => ['icon'=>'fas fa-headset',    'title'=>__('24/7 Support','bazaarhub'),  'sub'=>__('Dedicated support','bazaarhub')],
          3 => ['icon'=>'fas fa-shield-alt', 'title'=>__('Secure Payment','bazaarhub'),'sub'=>__('100% secure','bazaarhub')],
          4 => ['icon'=>'fas fa-undo-alt',   'title'=>__('Easy Returns','bazaarhub'),  'sub'=>__('30-day return policy','bazaarhub')],
      ];
      for ($i = 1; $i <= 4; $i++):
          $icon  = get_theme_mod("feature_icon_$i",  $feature_defaults[$i]['icon']);
          $title = get_theme_mod("feature_title_$i", $feature_defaults[$i]['title']);
          $sub   = get_theme_mod("feature_sub_$i",   $feature_defaults[$i]['sub']);
          if (!$title) continue;
      ?>
      <div class="bh-feature-item">
        <i class="<?php echo esc_attr($icon); ?>"></i>
        <div>
          <strong><?php echo esc_html($title); ?></strong>
          <span><?php echo esc_html($sub); ?></span>
        </div>
      </div>
      <?php endfor; ?>
    </div>
  </div>
</section>
