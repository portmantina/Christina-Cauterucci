<?php
function eureka_enqueue_styles(){
	// Load default stylesheets
	wp_enqueue_style( 'eureka-style', get_stylesheet_uri() );
	wp_enqueue_style( 'eureka-layout', get_template_directory_uri() . '/layouts/content-sidebar.css');
}
add_action( 'wp_enqueue_scripts', 'eureka_enqueue_styles' );


/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes
 * @return array $classes Array of revised list of body classes
 * @since Eureka 1.0
 */
function eureka_body_classes( $classes ) {
	// Adds a class of single-author to blogs with only 1 published author
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	} else {
		$classes[] = 'single-author';
	}

	return $classes;
}
add_filter( 'body_class', 'eureka_body_classes' );

/**
 * Controls the post padding style
 *
 * @param array $classes
 * @return array $classes Array of classes with 'bubble' class to use
 * @since Eureka 1.0
 */
function eureka_bubble( $classes ){
	global $post, $is_IE;
	
	// Get out of here if this is the singular view for posts/pages
	if( is_singular() )
		return $classes;

	// IE? no special padding for you
	if ( $is_IE ) {
		if ( strpos( $_SERVER['HTTP_USER_AGENT'], 'MSIE 7' ) || 
		   strpos( $_SERVER['HTTP_USER_AGENT'], 'MSIE 8' ) ) {
			$classes[] = 'bubble';
			return $classes;
		}
	}	
	
	// Now we get to the cool bit, odd numbered posts will have a wider padding on the left, even numbered posts, the right.	
	if( $post->ID % 2 )
		$classes[] = 'bubble-left';
	else
		$classes[] = 'bubble-right';
		
	return $classes;
}
add_filter( 'post_class', 'eureka_bubble' );


/**
 * Display background gradient instead of WordPress default background colors.
 *
 * @since Eureka 1.1
 */
function eureka_bg_gradient(){

	// Follow @_custom_background_cb()
	// $background is the saved custom image, or the default image.
	$background = get_background_image();

	// $color is the saved custom color.
	$color = get_theme_mod( 'background_color', '488ba3' );

	if ( ! $background && ! $color )
		return;

	$style = $color ? "background-color: #$color;" : '';

	if ( $background ) {
		$image = " background-image: url('$background');";

		$repeat = get_theme_mod( 'background_repeat', 'repeat' );
		if ( ! in_array( $repeat, array( 'no-repeat', 'repeat-x', 'repeat-y', 'repeat' ) ) )
			$repeat = 'repeat';
		$repeat = " background-repeat: $repeat;";

		$position = get_theme_mod( 'background_position_x', 'left' );
		if ( ! in_array( $position, array( 'center', 'right', 'left' ) ) )
			$position = 'left';
		$position = " background-position: top $position;";

		$attachment = get_theme_mod( 'background_attachment', 'scroll' );
		if ( ! in_array( $attachment, array( 'fixed', 'scroll' ) ) )
			$attachment = 'scroll';
		$attachment = " background-attachment: $attachment;";

		$style .= $image . $repeat . $position . $attachment;
	
	// Now we're entering gradient territory
	} else {
	
		$bottom_bg =  '#'  . esc_attr( get_theme_mod( 'background_color_bottom', '6caec6') );
		$top_bg = '#' . $color;
	}

if( $background ):
?>
<style type="text/css" id="custom-background-css">
body.custom-background { <?php echo trim( $style ); ?> }
</style>
<?php else: ?>
<style type="text/css" media="screen">
/* =Global
-------------------------------------------------------------- */
body {
	background: <?php echo $top_bg; ?>; /* Old browsers */
	background: -moz-linear-gradient(top,  <?php echo $top_bg; ?> 20%, <?php echo $bottom_bg; ?>  50%); /* FF3.6+ */
	background: -webkit-gradient(linear, left top, left bottom, color-stop(20%,<?php echo $top_bg; ?>), color-stop(50%,<?php echo $bottom_bg; ?>)); /* Chrome,Safari4+ */
	background: -webkit-linear-gradient(top,  <?php echo $top_bg; ?> 20%,<?php echo $bottom_bg; ?> 50%); /* Chrome10+,Safari5.1+ */
	background: -o-linear-gradient(top,  <?php echo $top_bg; ?> 20%,<?php echo $bottom_bg; ?> 50%); /* Opera 11.10+ */
	background: -ms-linear-gradient(top,  <?php echo $top_bg; ?> 20%,<?php echo $bottom_bg; ?> 50%); /* IE10+ */
	background: linear-gradient(top,  <?php echo $top_bg; ?> 20%, <?php echo $bottom_bg; ?> 50%); /* W3C */
	filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='<?php echo $top_bg; ?>', endColorstr='<?php echo $bottom_bg; ?>',GradientType=0 ); /* IE6-9 */
}
</style>
<?php
endif;
}

/**
 * Display user-selected theme colors
 * Hooked into `wp_head` instead of `wp_print_styles` so as to override style.css.
 *
 * @since Eureka 1.0
 *
 */
function eureka_color_scheme(){
	$style = '';
	$background_color = '#' . esc_attr( get_theme_mod( 'background_color', '488ba3' ) );
	$style .= 'a:hover, a:focus, a:active, .entry-title a:hover, .entry-title a:active, .entry-title a:focus { color: ' . $background_color . '; }';
	$style .= '.entry-content table, .comment-content table { border: 1px solid ' . $background_color . '; }';
	$style .= '.entry-content th, .comment-content th, .comment footer, #wp-calendar td { background-color: '. $background_color . '; } ';
	$style .= '.entry-content td, .entry-content th, .comment-content  td, .comment-content  th { border-bottom: 1px solid ' . $background_color . ';}';
	
	
	$bubble_color = '#' . esc_attr( get_theme_mod( 'bubble_background', '272628' ) );
	$style .= '.bubble-left, .bubble-right, .bubble { border-color: ' . $bubble_color . '; }';
	$style .= '.sticky { background: ' . $bubble_color . '; }';
	$style .= '#secondary h1 { color: ' . $bubble_color . '; }';
	$style .= 'a, .entry-title a, .entry-title a:visited, .entry-content th, .comment-content th  { color: '. $bubble_color . ';}';
	$style .= '.main-navigation li:hover > a { 	background: '. $bubble_color .'; }';

	$bubble_text_color = '#' . esc_attr( get_theme_mod( 'bubble_text_color', 'ffffff' ) );
	$style .= '.sticky { color: ' . $bubble_text_color . '; }';
	
	$bubble_link_color = '#' . esc_attr( get_theme_mod( 'bubble_link_color', '6caec6' ) );
	$style .= '#wp-calendar th, .site-description, h1.page-title { color: ' . $bubble_link_color .'; }';
	$style .= '#wp-calendar th, #wp-calendar td { border: 1px solid '. $bubble_link_color .'; }';
	$style .= '.entry-content blockquote { 	background-color: ' . $bubble_link_color . '; }';
	
	$other_text_color = '#' . esc_attr( get_theme_mod( 'other_text_color', 'ffffff' ) );
	$style .= '#secondary, #secondary a, .site-title a, .main-navigation a, .main-navigation a:visited, #wp-calendar td  { color: ' . $other_text_color . '; }';
	
	$other_hover_color = '#' . esc_attr( get_theme_mod( 'other_hover_color', '155f7a' ) );
	$style .= '#secondary a:hover, #secondary a:active, #secondary a:focus, .site-title a:hover, #wp-calendar td a:hover { color: ' . $other_hover_color . '; }';
	$style .= '.main-navigation ul, #wp-calendar th  { background: ' . $other_hover_color . '; }';
	$style .= '.widget-title { border-bottom: 1px solid ' . $other_text_color . ';} ';
	
	$link_color = '#' . esc_attr( get_theme_mod( 'link_color', '272628' ) );
	$style .= '.entry-content a, .entry-content a:visited, .entry-meta a { color: '. $link_color . ';} ';
		
	$style .= '.sticky a:link, .sticky a:visited { color: ' . $background_color . '; }';
	
	
	
	
	echo '<style type="text/css" media="screen">';
	echo $style;
	echo '</style>';


}
add_action( 'wp_head', 'eureka_color_scheme', 100 );
?>