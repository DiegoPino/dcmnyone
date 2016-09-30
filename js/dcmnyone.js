/**
 * @file
 * biodiversidadreuna2.js
 *
 * Provides utility javascript to biodiversidad reuna 2 theme
 */

(function ($) {
  Drupal.behaviors.biodiversidad2 = {
    attach: function (context, settings) {
		
	 $('nav').affix({
	        offset: {
	          top: parseInt($('.navbar').css('margin-top')+1)
	        }
	  });

	  
	    $('[data-toggle="offcanvas"]').click(function () {
	      $('.row-offcanvas').toggleClass('active')
	    });
	 
	
	  
	
	}
  };
})(jQuery);