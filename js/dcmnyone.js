/**
 * @file
 * dcmnyone.js
 *
 * Provides utility javascript to dcmnyone
 */

(function ($) {
  Drupal.behaviors.dcmnyone = {
    attach: function (context, settings) {
		
	    $('[data-toggle="offcanvas"]').click(function () {
	      $('.row-offcanvas').toggleClass('active')
	    });
	 
	
	  
	
	}
  };
})(jQuery);