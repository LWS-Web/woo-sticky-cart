/**
 * Here goes all the JS Code you need in your child theme buddy!
 */
(function($) {

	$(document).ready(function() {

		// Hide the cart-content
		$('#woo-sc-content').hide();

		$('#woo-sc-handle').click(function(e) {
		  	$('#woo-sc-container').toggleClass('open'); 
		    $('#woo-sc-content').slideToggle('180');
		});
	});

}(jQuery)); // END noConflict wrapper