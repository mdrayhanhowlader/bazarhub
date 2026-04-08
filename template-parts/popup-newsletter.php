<?php
// Show popup only once per session
$shown = isset($_COOKIE['bh_popup_shown']);
if($shown) return;
?>
<div class="bh-popup-overlay" id="bh-popup-overlay">
  <div class="bh-popup" id="bh-popup" role="dialog" aria-modal="true">
    <button class="bh-popup__close" id="bh-popup-close" aria-label="Close"><i class="fas fa-times"></i></button>
    <div class="bh-popup__left">
      <div class="bh-popup__img-wrap">
        <?php
        $img = get_theme_mod('popup_image','');
        if($img): ?>
          <img src="<?php echo esc_url($img); ?>" alt="Newsletter offer">
        <?php else: ?>
          <div class="bh-popup__img-placeholder">
            <i class="fas fa-leaf"></i>
            <span>Fresh &amp; Organic</span>
          </div>
        <?php endif; ?>
      </div>
    </div>
    <div class="bh-popup__right">
      <span class="bh-popup__tag">EXCLUSIVE OFFER</span>
      <h2 class="bh-popup__title">Want Access to<br><strong>Discounts &amp; Deals?</strong></h2>
      <p class="bh-popup__sub">Subscribe today and get <strong>25% OFF</strong> your first order. Limited time offer.</p>
      <form class="bh-popup__form" id="bh-popup-form">
        <input type="email" placeholder="Your Email Address" required class="bh-popup__input">
        <button type="submit" class="bh-popup__btn">Subscribe <i class="fas fa-arrow-right"></i></button>
      </form>
      <p class="bh-popup__note">* Free delivery on first order. No spam, unsubscribe anytime.</p>
      <label class="bh-popup__skip">
        <input type="checkbox" id="bh-popup-skip"> Don't show this again
      </label>
    </div>
  </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
  const popup = document.getElementById("bh-popup-overlay");
  const closeBtn = document.getElementById("bh-popup-close");
  const skipCheckbox = document.getElementById("bh-popup-skip");

  // Close button click
  closeBtn.addEventListener("click", function () {
    popup.style.display = "none";

    // যদি "Don't show again" check করা থাকে
    if (skipCheckbox.checked) {
      document.cookie = "bh_popup_shown=true; path=/; max-age=" + (60*60*24*30); // 30 days
    }
  });

  // Optional: overlay click করলে close
  popup.addEventListener("click", function (e) {
    if (e.target === popup) {
      popup.style.display = "none";
    }
  });
});
</script>
