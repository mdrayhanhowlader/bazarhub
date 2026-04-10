/**
 * BazaarHub Theme — Main JavaScript
 */
(function($) {
    'use strict';

    // ── DOM Ready ──────────────────────────────────────────
    $(function() {
        BH.init();
    });

    const BH = {

        init() {
            this.initSwipers();
            this.initMobileMenu();
            this.initCategoryPanel();
            this.initSearch();
            this.initWishlist();
            this.initAddToCart();
            this.initQuickView();
            this.initMiniCart();
            this.initCountdown();
            this.initBackToTop();
            this.initStickyHeader();
        },

        // ── Toast ──────────────────────────────────────────
        toast(msg, type = 'success') {
            const $t = $('#bh-toast');
            $t.text(msg).removeClass('error').addClass(type === 'error' ? 'error' : '').addClass('show');
            setTimeout(() => $t.removeClass('show'), 3000);
        },

        // ── Swipers ────────────────────────────────────────
        initSwipers() {
            // Hero
            if ($('.bh-hero-swiper').length) {
                new Swiper('.bh-hero-swiper', {
                    loop: true, autoplay: { delay: 5000, disableOnInteraction: false },
                    effect: 'fade', fadeEffect: { crossFade: true },
                    pagination: { el: '.swiper-pagination', clickable: true },
                    navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' },
                });
            }
            // Category Tabs (showcase)
            if ($('.bh-cattabs-swiper').length) {
                new Swiper('.bh-cattabs-swiper', {
                    spaceBetween: 8,
                    freeMode: true,
                    slidesPerView: 'auto',
                    breakpoints: {
                        0:   { slidesPerView: 3.3, spaceBetween: 8 },
                        400: { slidesPerView: 4,   spaceBetween: 8 },
                        640: { slidesPerView: 5.5, spaceBetween: 10 },
                        768: { slidesPerView: 7,   spaceBetween: 10 },
                        1024:{ slidesPerView: 10,  spaceBetween: 12 },
                    }
                });
            }
            // Category Circles
            if ($('.bh-cat-swiper').length) {
                new Swiper('.bh-cat-swiper', {
                    spaceBetween: 8, freeMode: true,
                    navigation: { nextEl: '.bh-cat-next', prevEl: '.bh-cat-prev' },
                    breakpoints: {
                        0:    { slidesPerView: 3.5 },
                        400:  { slidesPerView: 4.5 },
                        480:  { slidesPerView: 5 },
                        640:  { slidesPerView: 6 },
                        768:  { slidesPerView: 8 },
                        1024: { slidesPerView: 10 },
                        1280: { slidesPerView: 12 },
                    }
                });
            }
            // Deals
            if ($('.bh-deals-swiper').length) {
                new Swiper('.bh-deals-swiper', {
                    spaceBetween: 12,
                    navigation: { nextEl: '.bh-deals-next', prevEl: '.bh-deals-prev' },
                    breakpoints: {
                        0:    { slidesPerView: 1.1, spaceBetween: 10 },
                        480:  { slidesPerView: 1.5, spaceBetween: 10 },
                        600:  { slidesPerView: 2, spaceBetween: 12 },
                        768:  { slidesPerView: 2.5, spaceBetween: 12 },
                        900:  { slidesPerView: 3, spaceBetween: 14 },
                        1100: { slidesPerView: 4, spaceBetween: 16 },
                        1280: { slidesPerView: 5, spaceBetween: 16 },
                    }
                });
            }
            // New Arrivals
            if ($('.bh-arrivals-swiper').length) {
                new Swiper('.bh-arrivals-swiper', {
                    spaceBetween: 12,
                    navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' },
                    breakpoints: {
                        0:    { slidesPerView: 1.1, spaceBetween: 10 },
                        480:  { slidesPerView: 1.5, spaceBetween: 10 },
                        600:  { slidesPerView: 2, spaceBetween: 12 },
                        768:  { slidesPerView: 2.5, spaceBetween: 12 },
                        900:  { slidesPerView: 3, spaceBetween: 14 },
                        1100: { slidesPerView: 4, spaceBetween: 16 },
                        1280: { slidesPerView: 5, spaceBetween: 16 },
                    }
                });
            }
        },

        // ── Mobile Menu ────────────────────────────────────
        initMobileMenu() {
            const $btn     = $('#bh-hamburger');
            const $menu    = $('#bh-mobile-menu');
            const $overlay = $('#bh-overlay');
            const $close   = $('#bh-mobile-close');

            function openMenu() {
                $menu.addClass('active');
                $overlay.addClass('active');
                $btn.addClass('active');
                $('body').css('overflow', 'hidden');
            }
            function closeMenu() {
                $menu.removeClass('active');
                $overlay.removeClass('active');
                $btn.removeClass('active');
                $('body').css('overflow', '');
            }

            $btn.on('click', function(e) { e.stopPropagation(); openMenu(); });
            $close.on('click', closeMenu);
            $overlay.on('click', closeMenu);
            $(document).on('keydown', function(e) { if (e.key === 'Escape') closeMenu(); });

            // Mobile submenu
            $('.bh-mobile-nav .menu-item-has-children > a').on('click', function(e) {
                const $sub = $(this).next('.sub-menu');
                if ($sub.length) {
                    e.preventDefault();
                    $sub.slideToggle(200);
                    $(this).toggleClass('open');
                }
            });
        },

        // ── Category Panel ─────────────────────────────────
        initCategoryPanel() {
            const $wrap = $('.bh-all-cats');
            const $btn  = $('#bh-all-cats-btn');

            $btn.on('click', function(e) {
                e.stopPropagation();
                $wrap.toggleClass('bh-all-cats--open');
            });
            $(document).on('click', function(e) {
                if (!$wrap.is(e.target) && $wrap.has(e.target).length === 0) {
                    $wrap.removeClass('bh-all-cats--open');
                }
            });
        },

        // ── Live Search ────────────────────────────────────
        initSearch() {
            const $input    = $('#bh-search-input');
            const $dropdown = $('#bh-search-dropdown');
            const $btn      = $('#bh-search-btn');
            let searchTimer;

            $input.on('input', function() {
                clearTimeout(searchTimer);
                const val = $(this).val().trim();
                if (val.length < 2) { $dropdown.removeClass('active').html(''); return; }
                searchTimer = setTimeout(() => {
                    $.post(bazaarhub_ajax.ajax_url, {
                        action: 'bazaarhub_live_search',
                        term: val,
                        nonce: bazaarhub_ajax.nonce
                    }, function(res) {
                        if (res.success && res.data.html) {
                            $dropdown.html(res.data.html).addClass('active');
                        } else {
                            $dropdown.removeClass('active');
                        }
                    });
                }, 300);
            });

            $btn.on('click', function() {
                const val = $input.val().trim();
                if (val) window.location = bazaarhub_ajax.ajax_url.replace('admin-ajax.php','') + '?s=' + encodeURIComponent(val) + '&post_type=product';
            });

            $input.on('keydown', function(e) {
                if (e.key === 'Enter') $btn.trigger('click');
            });

            $(document).on('click', function(e) {
                if (!$('#bh-search-input, #bh-search-dropdown').is(e.target) && !$('#bh-search-dropdown').has(e.target).length) {
                    $dropdown.removeClass('active');
                }
            });
        },

        // ── Wishlist ───────────────────────────────────────
        initWishlist() {
            $(document).on('click', '.bh-wishlist-btn', function(e) {
                e.preventDefault(); e.stopPropagation();
                const $btn = $(this);
                const pid  = $btn.data('pid');

                $.post(bazaarhub_ajax.ajax_url, {
                    action: 'bazaarhub_toggle_wishlist',
                    product_id: pid,
                    nonce: bazaarhub_ajax.nonce
                }, function(res) {
                    if (res.success) {
                        if (res.data.action === 'added') {
                            $btn.addClass('active').find('i').removeClass('far').addClass('fas');
                            BH.toast(bazaarhub_ajax.i18n.added_to_wishlist);
                        } else {
                            $btn.removeClass('active').find('i').removeClass('fas').addClass('far');
                            BH.toast(bazaarhub_ajax.i18n.removed_wishlist);
                            if ($btn.closest('.bh-product-card').parent().hasClass('bh-wishlist-grid')) {
                                $btn.closest('.bh-product-card').fadeOut(300, function() { $(this).remove(); });
                            }
                        }
                        $('.bh-wishlist-count').text(res.data.count);
                    } else {
                        if (res.data && res.data.login_required) {
                            BH.toast('Please log in to use wishlist', 'error');
                        }
                    }
                });
            });
        },

        // ── Add to Cart ────────────────────────────────────
        initAddToCart() {
            $(document).on('click', '.bh-add-to-cart', function(e) {
                e.preventDefault();
                const $btn = $(this);
                const pid  = $btn.data('pid');
                if ($btn.hasClass('loading')) return;

                $btn.addClass('loading').html('<i class="fas fa-spinner fa-spin"></i>');

                $.post(bazaarhub_ajax.ajax_url, {
                    action: 'woocommerce_add_to_cart',
                    product_id: pid,
                    quantity: 1,
                    nonce: bazaarhub_ajax.nonce
                }, function() {
                    $btn.removeClass('loading').html('<i class="fas fa-check"></i> ' + bazaarhub_ajax.i18n.added_to_cart);
                    BH.toast(bazaarhub_ajax.i18n.added_to_cart);
                    BH.refreshMiniCart();
                    setTimeout(() => {
                        $btn.html('<i class="fas fa-cart-plus"></i> Add to Cart');
                    }, 2000);
                }).fail(function() {
                    // Fallback: use WC AJAX
                    $.post(bazaarhub_ajax.ajax_url, {
                        action: 'wc_add_to_cart',
                        product_id: pid,
                        quantity: 1,
                    }, function() {
                        $btn.removeClass('loading').html('<i class="fas fa-check"></i> Added!');
                        BH.toast(bazaarhub_ajax.i18n.added_to_cart);
                        BH.refreshMiniCart();
                        setTimeout(() => $btn.html('<i class="fas fa-cart-plus"></i> Add to Cart'), 2000);
                    });
                });
            });

            // WC native add to cart — refresh drawer and open it
            $(document.body).on('added_to_cart', function() {
                BH.refreshMiniCart();
                BH.toast(bazaarhub_ajax.i18n.added_to_cart);
                setTimeout(function() {
                    $('#bh-cart-drawer').addClass('active');
                    $('#bh-cart-overlay').addClass('active');
                    $('body').css('overflow', 'hidden');
                }, 400);
            });
        },

        // ── Quick View ─────────────────────────────────────
        initQuickView() {
            $(document).on('click', '.bh-quickview-btn', function(e) {
                e.preventDefault(); e.stopPropagation();
                const pid = $(this).data('pid');
                const $modal = $('#bh-quickview-modal');
                const $body  = $('#bh-quickview-body');

                $body.html('<div class="bh-loading"><i class="fas fa-spinner fa-spin"></i></div>');
                $modal.addClass('active');
                $('body').css('overflow', 'hidden');

                $.post(bazaarhub_ajax.ajax_url, {
                    action: 'bazaarhub_quick_view',
                    product_id: pid,
                    nonce: bazaarhub_ajax.nonce
                }, function(res) {
                    if (res.success) $body.html(res.data.html);
                });
            });

            $(document).on('click', '.bh-modal-close', function() {
                $('#bh-quickview-modal').removeClass('active');
                $('body').css('overflow', '');
            });

            $(document).on('keydown', function(e) {
                if (e.key === 'Escape') {
                    $('#bh-quickview-modal').removeClass('active');
                    $('body').css('overflow', '');
                }
            });
        },

        // ── Cart Drawer ────────────────────────────────────
        initMiniCart() {
            const $drawer  = $('#bh-cart-drawer');
            const $overlay = $('#bh-cart-overlay');
            const $trigger = $('#bh-cart-trigger');
            const $close   = $('#bh-cart-close');

            function openDrawer() {
                $drawer.addClass('active');
                $overlay.addClass('active');
                $('body').css('overflow', 'hidden');
            }
            function closeDrawer() {
                $drawer.removeClass('active');
                $overlay.removeClass('active');
                $('body').css('overflow', '');
            }

            $trigger.on('click', function(e) {
                e.stopPropagation();
                $drawer.hasClass('active') ? closeDrawer() : openDrawer();
            });

            $close.on('click', closeDrawer);
            $overlay.on('click', closeDrawer);

            $(document).on('keydown', function(e) {
                if (e.key === 'Escape') closeDrawer();
            });
        },

        refreshMiniCart() {
            $.post(bazaarhub_ajax.ajax_url, {
                action: 'bazaarhub_refresh_mini_cart',
                nonce: bazaarhub_ajax.nonce
            }, function(res) {
                if (res.success) {
                    $('#bh-cart-body').html(res.data.html);
                    const cnt = parseInt(res.data.count) || 0;
                    $('.bh-cart-count').text(cnt);
                    const label = cnt === 1 ? cnt + ' item' : cnt + ' items';
                    $('#bh-cart-item-label').text(label);
                }
            });
        },

        // ── Countdown Timer ────────────────────────────────
        initCountdown() {
            const $cd = $('.bh-countdown[data-end]');
            if (!$cd.length) return;

            const endDate = new Date($cd.data('end')).getTime();

            function tick() {
                const now  = new Date().getTime();
                const diff = endDate - now;
                if (diff <= 0) { $cd.html('<span class="bh-cd-expired">Deal Ended</span>'); return; }
                const hrs  = Math.floor(diff / (1000*60*60));
                const mins = Math.floor((diff % (1000*60*60)) / (1000*60));
                const secs = Math.floor((diff % (1000*60)) / 1000);
                $cd.find('.bh-cd-hours').text(String(hrs).padStart(2,'0'));
                $cd.find('.bh-cd-mins').text(String(mins).padStart(2,'0'));
                $cd.find('.bh-cd-secs').text(String(secs).padStart(2,'0'));
            }
            tick();
            setInterval(tick, 1000);
        },

        // ── Back to Top ────────────────────────────────────
        initBackToTop() {
            const $btn = $('#bh-back-top');
            $(window).on('scroll', function() {
                $btn.toggleClass('visible', $(this).scrollTop() > 400);
            });
            $btn.on('click', function() {
                $('html, body').animate({ scrollTop: 0 }, 400);
            });
        },

        // ── Sticky Header shadow ───────────────────────────
        initStickyHeader() {
            const $header = $('#bh-header');
            $(window).on('scroll', function() {
                $header.toggleClass('scrolled', $(this).scrollTop() > 10);
            });
        }
    };

    window.BH = BH;

})(jQuery);

/* ── Mobile Shop Sidebar Toggle ── */
(function($){
  $('#bh-mobile-filter-toggle').on('click', function(){
    var $sidebar = $('.bh-shop-sidebar');
    var $btn = $(this);
    $sidebar.toggleClass('bh-sidebar-open');
    if($sidebar.hasClass('bh-sidebar-open')){
      $btn.html('<i class="fas fa-times"></i> Close Filters');
    } else {
      $btn.html('<i class="fas fa-sliders-h"></i> Filter & Sort');
    }
  });
})(jQuery);

(function($){
$(document).on("click",".bh-qty-plus",function(){
var inp=$(this).siblings("input.input-qty");
var max=parseInt($(this).data("max"))||9999;
var val=parseInt(inp.val())||1;
if(val<max){inp.val(val+1).trigger("change");}
else{inp.trigger("shake");}
});
$(document).on("click",".bh-qty-minus",function(){
var inp=$(this).siblings("input.input-qty");
var min=parseInt(inp.attr("min"))||1;
var val=parseInt(inp.val())||1;
if(val>min){inp.val(val-1).trigger("change");}
});
$(document).on("click",".bh-sp-wishlist-btn",function(){
var $btn=$(this);
var active=$btn.hasClass("active");
$btn.find("span").text(active?"Add to Wishlist":"Remove from Wishlist");
});
})(jQuery);

(function($){
  $(document).on('click','.bh-qty-plus',function(){
    var inp=$(this).siblings('input.input-qty');
    var mx=parseInt($(this).data('max'))||9999;
    var v=parseInt(inp.val())||1;
    if(v<mx) inp.val(v+1).trigger('change');
  });
  $(document).on('click','.bh-qty-minus',function(){
    var inp=$(this).siblings('input.input-qty');
    var mn=parseInt(inp.attr('min'))||1;
    var v=parseInt(inp.val())||1;
    if(v>mn) inp.val(v-1).trigger('change');
  });
  $(document).on('click','.bh-sp-wishlist-btn',function(){
    var active=$(this).hasClass('active');
    $(this).find('span').text(active?'Add to Wishlist':'Remove from Wishlist');
  });
})(jQuery);

/* ── Popup Newsletter ── */
(function($){
  var $overlay = $('#bh-popup-overlay');
  if(!$overlay.length) return;

  function setCookie(name, value, seconds) {
    var expires = new Date(Date.now() + seconds * 1000).toUTCString();
    document.cookie = name + '=' + value + '; path=/; expires=' + expires;
  }

  function dismiss(permanent) {
    $overlay.addClass('hidden');
    if (permanent) {
      setCookie('bh_popup_dismissed', 'permanent', 60 * 60 * 24 * 30); // 30 days
    } else {
      // Hide until midnight today
      var now      = new Date();
      var midnight = new Date(now.getFullYear(), now.getMonth(), now.getDate() + 1);
      setCookie('bh_popup_dismissed', 'today', Math.round((midnight - now) / 1000));
    }
  }

  // Show after 3-second delay
  setTimeout(function(){
    $overlay.removeClass('hidden');
  }, 3000);

  // Close on X — permanent if checkbox checked
  $('#bh-popup-close').on('click', function(){
    dismiss($('#bh-popup-skip').is(':checked'));
  });

  // Close on backdrop click
  $overlay.on('click', function(e){
    if($(e.target).is($overlay)) dismiss(false);
  });

  // Close on ESC
  $(document).on('keydown', function(e){
    if(e.key === 'Escape') dismiss(false);
  });

  // Form submit — permanent dismiss after success message
  $('#bh-popup-form').on('submit', function(e){
    e.preventDefault();
    $(this).html('<p style="color:#2e7d32;font-weight:700;text-align:center;padding:16px"><i class="fas fa-check-circle" style="font-size:28px;display:block;margin-bottom:8px"></i>Subscribed successfully!</p>');
    setTimeout(function(){ dismiss(true); }, 2000);
  });

  /* ── Category Menu Toggle ── */
  var $catBtn = $('#bh-catmenu-btn');
  var $catMenu = $('#bh-catmenu');

  $catBtn.on('click', function(e){
    e.stopPropagation();
    $catMenu.toggleClass('bh-catmenu--open');
    $catBtn.attr('aria-expanded', $catMenu.hasClass('bh-catmenu--open'));
  });

  $(document).on('click', function(e){
    if(!$catMenu.is(e.target) && $catMenu.has(e.target).length === 0){
      $catMenu.removeClass('bh-catmenu--open');
    }
  });

  /* ── Mobile subcategory toggle ── */
  $(document).on('click', '.bh-mcats__link', function(e){
    var $sub = $(this).next('.bh-mcats__sub');
    if($sub.length){
      e.preventDefault();
      $sub.slideToggle(200);
      $(this).find('i').toggleClass('fa-chevron-down fa-chevron-up');
    }
  });

})(jQuery);

/* ── Bestseller Swiper ── */
if(document.querySelector('.bh-bestseller-swiper')) {
  new Swiper('.bh-bestseller-swiper', {
    spaceBetween: 12,
    navigation: { nextEl: '.bh-bestseller-swiper .swiper-button-next', prevEl: '.bh-bestseller-swiper .swiper-button-prev' },
    breakpoints: {
      0:    { slidesPerView: 1.1, spaceBetween: 10 },
      480:  { slidesPerView: 1.5, spaceBetween: 10 },
      600:  { slidesPerView: 2, spaceBetween: 12 },
      900:  { slidesPerView: 3, spaceBetween: 14 },
      1200: { slidesPerView: 4, spaceBetween: 16 },
    }
  });
}

/* ── Compare ── */
(function($){
  $(document).on('click','.bh-compare-btn',function(e){
    e.preventDefault(); e.stopPropagation();
    var $btn = $(this), pid = $btn.data('pid');
    $.post(bazaarhub_ajax.ajax_url, {
      action:'bazaarhub_toggle_compare', product_id:pid, nonce:bazaarhub_ajax.nonce
    }, function(res){
      if(res.success){
        if(res.data.action==='added'){
          $btn.addClass('active');
          BH.toast('Added to compare!');
        } else {
          $btn.removeClass('active');
          BH.toast('Removed from compare.');
        }
        $('.bh-compare-count').text(res.data.count);
      } else {
        BH.toast(res.data.message || 'Max 4 products allowed.','error');
      }
    });
  });

  // Compare remove on compare page
  $(document).on('click','.bh-compare-remove',function(){
    var pid = $(this).data('pid');
    $.post(bazaarhub_ajax.ajax_url, {
      action:'bazaarhub_toggle_compare', product_id:pid, nonce:bazaarhub_ajax.nonce
    }, function(res){
      if(res.success){ location.reload(); }
    });
  });
})(jQuery);

jQuery(function($){
  /* Category showcase tabs */
  $(document).on('click', '.bh-catshow2__tab', function(){
    var $tab = $(this);
    var cat = $tab.data('cat');
    $('.bh-catshow2__tab').css({'border-color':'#eee','background':'#fafafa'}).find('span').css('color','#333');
    $tab.css({'border-color':'#2e7d32','background':'#e8f5e9'}).find('span').css('color','#2e7d32');
    var $products = $('#bh-catshow2-products');
    $products.css('opacity','0.5');
    $.post(bazaarhub_ajax.ajax_url, {
      action: 'bazaarhub_cat_products',
      nonce: bazaarhub_ajax.nonce,
      cat: cat
    }, function(res){
      if(res.success) $products.html(res.data.html).css('opacity','1');
    });
  });

  /* Category showcase tab handler (Shop by Categories) */
  $(document).on('click', '.bh-cattabs__btn:not([data-section])', function(){
    var $btn    = $(this);
    var cat     = $btn.data('cat') || '';
    var $target = $('#bh-catshow2-products');
    if (!$target.length) return;

    $btn.closest('.bh-cattabs-wrap').find('.bh-cattabs__btn').removeClass('active');
    $btn.addClass('active');
    $target.css('opacity','0.4');

    $.post(bazaarhub_ajax.ajax_url, {
      action: 'bazaarhub_cat_products',
      nonce:  bazaarhub_ajax.nonce,
      cat:    cat
    }, function(res){
      if (res.success) $target.html(res.data.html).css('opacity','1');
    });
  });

  /* Shared handler for deals & bestseller tabs */
  $(document).on('click', '.bh-cattabs__btn[data-section]', function(){
    var $btn     = $(this);
    var section  = $btn.data('section');
    var cat      = $btn.data('cat') || '';
    var actionMap = { deals: 'bazaarhub_deals_products', bestseller: 'bazaarhub_bestseller_products' };
    var targetMap = { deals: '#bh-deals-products', bestseller: '#bh-bestseller-products' };
    var action   = actionMap[section];
    var $target  = $(targetMap[section]);
    if (!action || !$target.length) return;

    $btn.closest('.bh-cattabs-wrap').find('.bh-cattabs__btn').removeClass('active');
    $btn.addClass('active');
    $target.css('opacity','0.5');

    $.post(bazaarhub_ajax.ajax_url, {
      action: action,
      nonce:  bazaarhub_ajax.nonce,
      cat:    cat
    }, function(res){
      if (res.success) $target.html(res.data.html).css('opacity','1');
    });
  });
});

jQuery(function($){
  $('#bh-mobile-search-toggle').on('click', function(){
    $('#bh-mobile-search-bar').toggleClass('active');
    $(this).find('i').toggleClass('fa-search fa-times');
  });
  $('#bh-hamburger-mobile').on('click', function(){
    $('#bh-mobile-menu').addClass('active');
    $('#bh-overlay').addClass('active');
  });
});

jQuery(function($){
  $('#bh-mobile-search-toggle').on('click', function(){
    $('#bh-mobile-search-bar').toggleClass('active');
    $(this).find('i').toggleClass('fa-search fa-times');
  });
  $('#bh-hamburger-mobile').on('click', function(){
    $('#bh-mobile-menu').addClass('active');
    $('#bh-overlay').addClass('active');
  });
});
jQuery(function($){
  $('#bh-hamburger-m').on('click', function(){
    $('#bh-mobile-menu').addClass('active');
    $('#bh-overlay').addClass('active');
    $('body').css('overflow','hidden');
  });
});
jQuery(function($){
  $('#bh-hamburger-m').on('click', function(){
    $('#bh-mobile-menu').addClass('active');
    $('#bh-overlay').addClass('active');
    $('body').css('overflow','hidden');
  });
});
jQuery(function($){
  $('#bh-mobile-search-icon').on('click', function(){
    $('.bh-header__search').toggleClass('bh-search-open');
    $(this).find('i').toggleClass('fa-search fa-times');
  });
});
jQuery(function($){
  $('#bh-mobile-search-icon').on('click', function(){
    $('#bh-search-modal').addClass('active');
    setTimeout(function(){ $('#bh-mobile-search-input').focus(); }, 100);
  });
  $('#bh-search-modal-close, #bh-search-modal').on('click', function(e){
    if(e.target === this) $('#bh-search-modal').removeClass('active');
  });
  $('#bh-search-modal .bh-search-modal__close').on('click', function(){
    $('#bh-search-modal').removeClass('active');
  });
});
jQuery(function($){
  $(document).on('input', '#bh-mobile-menu-search', function(){
    var val = $(this).val().trim();
    if(val.length < 2) return;
    $.ajax({
      url: bazaarhub_ajax.ajax_url,
      data: { action: 'bazaarhub_live_search', nonce: bazaarhub_ajax.nonce, q: val },
      success: function(res) {
        if(res.success) $('#bh-mobile-search-dropdown').html(res.data.html);
      }
    });
  });
});
jQuery(function($){
  $(document).on('click', '.bh-cats-toggle', function(){
    $(this).closest('.bh-mobile-cats-wrap').toggleClass('open');
  });
});

/* ── Deals Countdown Timer ── */
(function(){
  var el = document.getElementById('bh-deals-countdown');
  if (!el) return;
  var endStr = el.getAttribute('data-end');
  var end = endStr ? new Date(endStr).getTime() : (new Date().setHours(23,59,59,999));
  function pad(n){ return String(n).padStart(2,'0'); }
  function tick(){
    var now = Date.now(), diff = end - now;
    if (diff <= 0) { diff = 0; }
    var h = Math.floor(diff / 3600000);
    var m = Math.floor((diff % 3600000) / 60000);
    var s = Math.floor((diff % 60000) / 1000);
    var hEl = document.getElementById('bh-cd-h');
    var mEl = document.getElementById('bh-cd-m');
    var sEl = document.getElementById('bh-cd-s');
    if (hEl) hEl.textContent = pad(h);
    if (mEl) mEl.textContent = pad(m);
    if (sEl) sEl.textContent = pad(s);
  }
  tick();
  setInterval(tick, 1000);
})();

/* ── Today's Picks — Show All ── */
jQuery(function($){
  $('#bh-featured-showall').on('click', function(){
    $('#bh-featured-grid .bh-featured__card--hidden').removeClass('bh-featured__card--hidden');
    $(this).closest('.bh-featured__showall-wrap').fadeOut(200);
  });
});
