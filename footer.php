<?php defined('ABSPATH') || exit; ?>
</main>

<?php get_template_part('template-parts/home/features-bar'); ?>

<footer class="bh-new-footer">

  <!-- Main -->
  <div class="bh-nf-main">
    <div class="bh-container">
      <div class="bh-nf-grid">

        <!-- Col 1: Brand -->
        <div class="bh-nf-brand">
          <a href="<?php echo home_url(); ?>" class="bh-nf-logo">
            <i class="fas fa-store"></i>
            <span>Modhu Bazar Shop</span>
          </a>
          <p><?php echo wp_kses_post(get_theme_mod('footer_about','আপনার বিশ্বস্ত অনলাইন শপিং গন্তব্য। সেরা মানের পণ্য, সেরা দামে পৌঁছে দিচ্ছি।')); ?></p>
          <div class="bh-nf-social">
            <?php
            $sl = ['facebook'=>'fab fa-facebook-f','instagram'=>'fab fa-instagram','twitter'=>'fab fa-twitter','youtube'=>'fab fa-youtube','whatsapp'=>'fab fa-whatsapp'];
            foreach($sl as $k=>$ic):
              $u = get_theme_mod("social_{$k}",'');
              if($u && $u !== '#'):
            ?>
              <a href="<?php echo esc_url($u); ?>" target="_blank" class="bh-nf-social-<?php echo $k; ?>"><i class="<?php echo $ic; ?>"></i></a>
            <?php endif; endforeach; ?>
          </div>
        </div>

        <!-- Col 2: Quick Links -->
        <div class="bh-nf-col">
          <h5><?php _e('Quick Links','bazaarhub'); ?></h5>
          <ul>
            <li><a href="<?php echo home_url(); ?>"><i class="fas fa-chevron-right"></i><?php _e('Home','bazaarhub'); ?></a></li>
            <li><a href="<?php echo get_permalink(wc_get_page_id('shop')); ?>"><i class="fas fa-chevron-right"></i><?php _e('Shop','bazaarhub'); ?></a></li>
            <li><a href="<?php echo get_permalink(wc_get_page_id('cart')); ?>"><i class="fas fa-chevron-right"></i><?php _e('Cart','bazaarhub'); ?></a></li>
            <li><a href="<?php echo get_permalink(wc_get_page_id('checkout')); ?>"><i class="fas fa-chevron-right"></i><?php _e('Checkout','bazaarhub'); ?></a></li>
            <li><a href="<?php echo get_permalink(wc_get_page_id('myaccount')); ?>"><i class="fas fa-chevron-right"></i><?php _e('My Account','bazaarhub'); ?></a></li>
            <li><a href="<?php echo esc_url(bazaarhub_wishlist_url()); ?>"><i class="fas fa-chevron-right"></i><?php _e('Wishlist','bazaarhub'); ?></a></li>
          </ul>
        </div>

        <!-- Col 3: Info -->
        <div class="bh-nf-col">
          <?php if(is_active_sidebar('footer-2')): ?>
            <?php dynamic_sidebar('footer-2'); ?>
          <?php else: ?>
            <h5><?php _e('Information','bazaarhub'); ?></h5>
            <ul>
              <?php
              $info_pages = [
                'about-us'         => __('About Us','bazaarhub'),
                'contact-us'       => __('Contact Us','bazaarhub'),
                'privacy-policy'   => __('Privacy Policy','bazaarhub'),
                'terms-conditions' => __('Terms & Conditions','bazaarhub'),
                'return-policy'    => __('Return Policy','bazaarhub'),
              ];
              foreach ($info_pages as $slug => $label):
                $page = get_page_by_path($slug);
                $url  = $page ? get_permalink($page->ID) : home_url('/' . $slug . '/');
              ?>
              <li><a href="<?php echo esc_url($url); ?>"><i class="fas fa-chevron-right"></i><?php echo esc_html($label); ?></a></li>
              <?php endforeach; ?>
            </ul>
          <?php endif; ?>
        </div>

        <!-- Col 4: Contact -->
        <div class="bh-nf-col">
          <h5><?php _e('Contact Us','bazaarhub'); ?></h5>
          <div class="bh-nf-contact">
            <?php if(get_theme_mod('top_bar_phone')): ?>
            <div class="bh-nf-contact-item">
              <i class="fas fa-phone-alt"></i>
              <span><?php echo esc_html(get_theme_mod('top_bar_phone')); ?></span>
            </div>
            <?php endif; ?>
            <div class="bh-nf-contact-item">
              <i class="fas fa-envelope"></i>
              <span>support@modhubazarshop.com</span>
            </div>
            <div class="bh-nf-contact-item">
              <i class="fas fa-map-marker-alt"></i>
              <span>Dhaka, Bangladesh</span>
            </div>
            <div class="bh-nf-contact-item">
              <i class="fas fa-clock"></i>
              <span><?php _e('Sat–Thu: 9AM – 9PM','bazaarhub'); ?></span>
            </div>
          </div>
        </div>

        <!-- Col 5: Newsletter -->
        <div class="bh-nf-col bh-nf-newsletter">
          <h5><?php _e('Newsletter','bazaarhub'); ?></h5>
          <p><?php _e('Subscribe for latest deals and offers.','bazaarhub'); ?></p>
          <form class="bh-nf-form" onsubmit="return false;">
            <input type="email" placeholder="<?php esc_attr_e('Enter your email','bazaarhub'); ?>">
            <button type="submit"><i class="fas fa-paper-plane"></i> <?php _e('Subscribe','bazaarhub'); ?></button>
          </form>
          <div class="bh-nf-app">
            <span><?php _e('Download App:','bazaarhub'); ?></span>
            <a href="#" class="bh-nf-app-btn"><i class="fab fa-google-play"></i> Google Play</a>
            <a href="#" class="bh-nf-app-btn"><i class="fab fa-apple"></i> App Store</a>
          </div>
        </div>

      </div>
    </div>
  </div>

  <!-- Bottom Bar -->
  <div class="bh-nf-bottom">
    <div class="bh-container bh-nf-bottom-inner">
      <p><?php echo wp_kses_post(get_theme_mod('footer_copyright','&copy; 2026 Modhu Bazar Shop. All rights reserved.')); ?></p>
      <div class="bh-nf-pay">
        <span class="bh-nf-pay__label"><?php _e('We Accept:','bazaarhub'); ?></span>

        <!-- Visa -->
        <span class="bh-pay-badge" title="Visa" style="background:#1a1f71;">
          <i class="fab fa-cc-visa" style="color:#fff;font-size:20px;"></i>
        </span>

        <!-- Mastercard -->
        <span class="bh-pay-badge" title="Mastercard" style="background:#fff;">
          <svg width="36" height="22" viewBox="0 0 36 22" xmlns="http://www.w3.org/2000/svg">
            <circle cx="13" cy="11" r="9" fill="#eb001b"/>
            <circle cx="23" cy="11" r="9" fill="#f79e1b"/>
            <path d="M18 4.8a9 9 0 0 1 0 12.4A9 9 0 0 1 18 4.8z" fill="#ff5f00"/>
          </svg>
        </span>

        <!-- Amex -->
        <span class="bh-pay-badge" title="American Express" style="background:#2e77bc;">
          <i class="fab fa-cc-amex" style="color:#fff;font-size:20px;"></i>
        </span>

        <!-- bKash -->
        <span class="bh-pay-badge" title="bKash" style="background:#e2136e;">
          <span style="color:#fff;font-weight:900;font-size:11px;letter-spacing:-.3px;line-height:1;">bKash</span>
        </span>

        <!-- Nagad -->
        <span class="bh-pay-badge" title="Nagad" style="background:#f05a28;">
          <span style="color:#fff;font-weight:900;font-size:11px;letter-spacing:-.3px;line-height:1;">Nagad</span>
        </span>

        <!-- Rocket -->
        <span class="bh-pay-badge" title="Rocket (DBBL)" style="background:#8b1a8b;">
          <span style="color:#fff;font-weight:900;font-size:10px;letter-spacing:-.3px;line-height:1;">Rocket</span>
        </span>

      </div>
    </div>
  </div>

</footer>

<div class="bh-modal" id="bh-quickview-modal" role="dialog" aria-modal="true">
  <div class="bh-modal__overlay bh-modal-close"></div>
  <div class="bh-modal__content">
    <button class="bh-modal__close bh-modal-close"><i class="fas fa-times"></i></button>
    <div class="bh-modal__body" id="bh-quickview-body"></div>
  </div>
</div>

<div class="bh-toast" id="bh-toast"></div>
<button class="bh-back-top" id="bh-back-top" aria-label="Back to top"><i class="fas fa-arrow-up"></i></button>
<?php get_template_part('template-parts/popup-newsletter'); ?>

<!-- CART DRAWER -->
<div class="bh-cart-drawer-overlay" id="bh-cart-overlay"></div>
<div class="bh-cart-drawer" id="bh-cart-drawer" aria-label="Shopping Cart" role="dialog">

  <!-- Header -->
  <div class="bh-cart-drawer__header">
    <div class="bh-cart-drawer__title">
      <i class="fas fa-shopping-cart"></i>
      <span id="bh-cart-item-label"><?php
        $cnt = bazaarhub_get_cart_count();
        echo esc_html($cnt) . ' ' . ($cnt == 1 ? __('item','bazaarhub') : __('items','bazaarhub'));
      ?></span>
    </div>
    <button class="bh-cart-drawer__close" id="bh-cart-close">
      <i class="fas fa-times"></i> <?php _e('Close','bazaarhub'); ?>
    </button>
  </div>

  <!-- Items -->
  <div class="bh-cart-drawer__body" id="bh-cart-body">
    <?php woocommerce_mini_cart(); ?>
  </div>

</div>

<?php wp_footer(); ?>
</body>
</html>
