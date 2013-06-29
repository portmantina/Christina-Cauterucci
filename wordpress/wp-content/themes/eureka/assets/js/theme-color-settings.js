// Color picker that allows the user to reset to default color
// Hat tip: https://gist.github.com/1878051
var farbtastic, pickColor, input, colorPicker, defaultButton

(function($) {

	var defaultColor = '';
	var currentColor = '';
	
	// Set the color
	pickColor = function(color) {
		farbtastic.setColor(color);
		$(input).val(color);
		
		// If we have a default color, and they match, then we need to hide the 'Default' link.
		// Otherwise, we hide the 'Clear' link when it is empty.
		if ( ( defaultColor && color === defaultColor ) || ( ! defaultColor && ( '' === color || '#' === color ) ) )
			$(defaultButton).hide();
		else
			$(defaultButton).show();
	}


	$(document).ready(function() {
		
		// "Select a color" link is clicked
		$('.pickcolor').click(function() {
			// define the variables for current default color, color picker, default link and input box
			defaultColor = jQuery(this).siblings('input[type="hidden"]').val();
			colorPicker = jQuery(this).next('div');
			input = jQuery(this).prev('input');
			defaultButton = jQuery(this).siblings('a[class="clearcolor"]');
			// Show the color picker
			$(colorPicker).show();
			farbtastic = $.farbtastic(colorPicker, function(color) {
				pickColor(color);
				$(input).val(color).css('background', color); 
			});
			pickColor($(input).val());
			return false;
		});
		
		// "Default" link is clicked
		$('.clearcolor').click( function(e) {
			// define the variables for current default color, color picker, default link and input box
			defaultColor = jQuery(this).siblings('input[type="hidden"]').val();
			colorPicker = jQuery(this).prev('div');
			input = jQuery(this).siblings('input[type="text"]');
			defaultButton = jQuery(this);
			farbtastic = $.farbtastic(colorPicker, function(defaultColor) {
				pickColor( defaultColor );
				$(input).val(defaultColor).css('background', defaultColor); 
			});
			pickColor( defaultColor);
			e.preventDefault();
		});
		
		$(colorPicker).keyup(function() {
			var _hex = $(colorPicker).val(), hex = _hex;
			if ( hex.charAt(0) != '#' )
				hex = '#' + hex;
			hex = hex.replace(/[^#a-fA-F0-9]+/, '');
			if ( hex != _hex )
				$(colorPicker).val(hex);
			if ( hex.length == 4 || hex.length == 7 )
				pickColor( hex );
		});
		
		

		$(document).mousedown(function(){
			$(colorPicker).each(function(){
				var display = $(this).css('display');
				if ( display == 'block' )
					$(this).fadeOut(2);
			});
		});
		

	
		$('.clearcolor').each(function(index){
			var defaultColor = $(this).siblings('input[type="hidden"]').val();
			var currentColor = $(this).siblings('input[type="text"]').val();
			if ( ( defaultColor && currentColor === defaultColor ) || ( ! defaultColor && ( '' === currentColor || '#' === currentColor ) ) )
				$(this).hide();
			else
				$(this).show();
		
		});
		
		// Set input background in form
		$('input[type="text"]').each(function(){
			var currentColor = $(this).val();
			$(this).css('background', currentColor);
		});

	});
})(jQuery);