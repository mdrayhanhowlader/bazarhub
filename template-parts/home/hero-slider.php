<?php defined('ABSPATH') || exit; ?>
<section class="bh-hero">
  <div class="bh-container">
    <div class="bh-hero__layout">

      <div class="bh-hero__main">
        <div class="swiper bh-hero-swiper">
          <div class="swiper-wrapper">
            <?php for($i=1; $i<=3; $i++):
              $title    = get_theme_mod("hero_title_$i","Fresh & Healthy");
              $subtitle = get_theme_mod("hero_subtitle_$i","Big Sale - Up To 50% Off");
              $btn_text = get_theme_mod("hero_btn_text_$i","Shop Now");
              $btn_url  = get_theme_mod("hero_btn_url_$i", get_permalink(wc_get_page_id('shop')));
              $image    = get_theme_mod("hero_image_$i","");
              $bg       = get_theme_mod("hero_bg_color_$i","#e8f5e9");
              if(!$title) continue;
              $has_img  = !empty($image);
            ?>
            <div class="swiper-slide bh-hero__slide"
              style="background-color:<?php echo esc_attr($bg); ?>;<?php if($has_img): ?>background-image:url('<?php echo esc_url($image); ?>');background-size:cover;background-position:center;<?php endif; ?>">
              <?php if($has_img): ?><div class="bh-hero__overlay"></div><?php endif; ?>
              <div class="bh-hero__content<?php echo $has_img?' bh-hero__content--over-img':''; ?>">
                <span class="bh-hero__tag">Special Offer</span>
                <h2 class="bh-hero__title"><?php echo esc_html($title); ?></h2>
                <p class="bh-hero__subtitle"><?php echo esc_html($subtitle); ?></p>
                <a href="<?php echo esc_url($btn_url); ?>" class="bh-btn bh-btn--green bh-btn--lg">
                  <?php echo esc_html($btn_text); ?> <i class="fas fa-arrow-right"></i>
                </a>
              </div>
            </div>
            <?php endfor; ?>
          </div>
          <div class="swiper-pagination"></div>
          <div class="swiper-button-next"></div>
          <div class="swiper-button-prev"></div>
        </div>
      </div>

      <div class="bh-hero__side">
        <?php for($j=1; $j<=2; $j++):
          $img = get_theme_mod("offer_image_$j","");
          $url = get_theme_mod("offer_url_$j", get_permalink(wc_get_page_id('shop')));
          $lbl = get_theme_mod("offer_label_$j","Special Offer $j");
        ?>
        <a href="<?php echo esc_url($url); ?>" class="bh-hero__side-banner">
          <?php if($img): ?>
            <img src="<?php echo esc_url($img); ?>" alt="<?php echo esc_attr($lbl); ?>">
          <?php else:
            $icons   = ['fas fa-fire', 'fas fa-star'];
            $badges  = ['Hot Deals', 'New Arrivals'];
            $titles  = ['Up to 50% Off', 'Just Dropped'];
            $subs    = ['Limited time offer', 'Fresh collection'];
            $idx = $j - 1;
          ?>
            <div class="bh-side-placeholder">
              <i class="<?php echo $icons[$idx]; ?> bh-side-placeholder__icon"></i>
              <span class="bh-side-placeholder__badge"><?php echo $badges[$idx]; ?></span>
              <p class="bh-side-placeholder__title"><?php echo $titles[$idx]; ?></p>
              <p class="bh-side-placeholder__sub"><?php echo $subs[$idx]; ?></p>
            </div>
          <?php endif; ?>
        </a>
        <?php endfor; ?>
      </div>

    </div>
  </div>
</section>
