<?php

/**
 * Returns true if a blog has more than 1 category
 *
 * @since Eureka 1.0
 */
function eureka_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'all_the_cool_cats' ) ) ) {
		// Create an array of all the categories that are attached to posts
		$all_the_cool_cats = get_categories( array(
			'hide_empty' => 1,
		) );

		// Count the number of categories that are attached to the posts
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'all_the_cool_cats', $all_the_cool_cats );
	}

	if ( '1' != $all_the_cool_cats ) {
		// This blog has more than 1 category so eureka_categorized_blog should return true
		return true;
	} else {
		// This blog has only 1 category so eureka_categorized_blog should return false
		return false;
	}
}

/**
 * Flush out the transients used in eureka_categorized_blog
 *
 * @since Eureka 1.0
 */
function eureka_category_transient_flusher() {
	// Like, beat it. Dig?
	delete_transient( 'all_the_cool_cats' );
}
add_action( 'edit_category', 'eureka_category_transient_flusher' );
add_action( 'save_post', 'eureka_category_transient_flusher' );


/**
 * Return the default colors for each color scheme
 * Used to determine which color to use as a default according to user-selected scheme
 *
 * Override using the filter hook 'eureka-color-schemes'
 *
 * @return array
 * @since Eureka 1.0
 **/
function eureka_get_default_color_schemes(){
	$color_schemes = array( 
		'blue' =>	array(
			'background_color' => '488ba3',
			'background_color_bottom'  => '6caec6',
			'bubble_background' => '272628',
			'bubble_text_color' => 'ffffff',
			'bubble_link_color' => '6caec6',
			'other_text_color' => 'ffffff',
			'other_hover_color' => '155f7a',
			'link_color' => '272628'
		),
		'chocolate' => array(
			'background_color' => 'a54058',
			'background_color_bottom'  => 'c66c9e',
			'bubble_background' => '2e1f1a',
			'bubble_text_color' => 'ffffff',
			'bubble_link_color' => 'db8680',
			'other_text_color' => 'ffffff',
			'other_hover_color' => '7a1544',
			'link_color' => '2e1f1a'
		),
		'slate' => array(
			'background_color' => '202527',
			'background_color_bottom'  => '4c5b61',
			'bubble_background' => '8299ab',
			'bubble_text_color' => '000000',
			'bubble_link_color' => '384b51',
			'other_text_color' => 'ffffff',
			'other_hover_color' => '122930',
			'link_color' => '384b51'
		),
		'purple' => array(
			'background_color' => '321C5A',
			'background_color_bottom'  => '552D6B',
			'bubble_background' => 'eb7f24',
			'bubble_text_color' => '000000',
			'bubble_link_color' => 'ff7703',
			'other_text_color' => 'ffffff',
			'other_hover_color' => '855095',
			'link_color' => '855095'
		)
	);

	return apply_filters( 'eureka-color-schemes', $color_schemes );
}

/**
 * Get default settings
 *
 * @since Eureka 1.1
 */
function eureka_default_settings(){
	$default_color_schemes = eureka_get_default_color_schemes();
	$current_color_scheme = get_theme_mod( 'color_scheme', 'blue' );
	
	$default_settings = array(
		'color_scheme' => $current_color_scheme,
		'background_color_bottom' => get_theme_mod( 'background_color_bottom', $default_color_schemes[$current_color_scheme]['background_color_bottom'] ),
		'bubble_background'   => get_theme_mod( 'bubble_background', $default_color_schemes[$current_color_scheme]['bubble_background'] ),
		'bubble_text_color'   => get_theme_mod( 'other_text_color', $default_color_schemes[$current_color_scheme]['bubble_text_color'] ),
		'bubble_link_color'   => get_theme_mod( 'bubble_link_color', $default_color_schemes[$current_color_scheme]['bubble_link_color'] ),
		'other_text_color'   => get_theme_mod( 'other_text_color', $default_color_schemes[$current_color_scheme]['other_text_color'] ),
		'other_hover_color'   => get_theme_mod( 'other_hover_color', $default_color_schemes[$current_color_scheme]['other_hover_color'] )
	);
	return $default_settings;

}

/* 
* Validate color
*
* @since Eureka 1.1
*/
function eureka_validate_color( $color, $default_color = '' ){
	
	$valid_color = preg_replace('/[^a-fA-F0-9]+/', '', esc_attr( $color ) );

	if ( strlen($valid_color) != 6 && strlen($valid_color) != 3 ) 
		$valid_color = $default_color;
	
	return $valid_color;
	
}

?>