/**
 * @file
 * Global utilities.
 *
 */
(function($, Drupal) {
  $('[data-toggle="tooltip"]').tooltip();

  'use strict';

  Drupal.behaviors.najm_portal_theme = {
    attach: function(context, settings) {

      // Custom code here
      // Hide ajax loader after ajax request completed.
      $( document ).ajaxComplete(function() {
        if ($('body').hasClass('page-view-our-services')) {
          $('.ajax-progress.ajax-progress-fullscreen').remove();
        }
      });

      // Check on current language.
      let rtl = false;
      if (jQuery('html').attr('lang') == 'ar') {
        rtl = true;
      } else {
        rtl = false;
      }

      // Fire clients slider.
      jQuery('#clients-slider').owlCarousel({
        loop: false,
        margin: 0,
        items: 4,
        dots: false,
        nav: true,
        navigation: true,
        slideSpeed: 200,
        autoplay: false,
        rtl: rtl,
        responsive: {
          0: {
            items: 2
          },
          768: {
            items: 4
          },
          1024: {
            items: 4
          }
        },
      });

    }
  };

})(jQuery, Drupal);
