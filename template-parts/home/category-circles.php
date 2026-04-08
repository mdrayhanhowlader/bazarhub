<?php
$cats = get_terms(['taxonomy'=>'product_cat','hide_empty'=>true,'parent'=>0,'number'=>12,'orderby'=>'count','order'=>'DESC']);
if (empty($cats)||is_wp_error($cats)) return;
?>
<section class="bh-cat-circles">
  <div class="bh-container">
    <div class="swiper bh-cat-swiper">
      <div class="swiper-wrapper">
        <?php foreach($cats as $cat):
          $tid = get_term_meta($cat->term_id,'thumbnail_id',true);
          $img = $tid ? wp_get_attachment_image($tid,[100,100]) : '<span class="bh-cat-icon-fallback"><i class="fas fa-tag"></i></span>';
        ?>
        <div class="swiper-slide">
          <a href="<?php echo esc_url(get_term_link($cat)); ?>" class="bh-cat-circle">
            <div class="bh-cat-circle__img"><?php echo $img; ?></div>
            <span class="bh-cat-circle__name"><?php echo esc_html($cat->name); ?></span>
          </a>
        </div>
        <?php endforeach; ?>
      </div>
      <div class="swiper-button-next bh-cat-next"></div>
      <div class="swiper-button-prev bh-cat-prev"></div>
    </div>
  </div>
</section>
