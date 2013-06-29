<?php
/**
 * Eureka functions
 *
 * @package Eureka
 * @version 1.1
 * @author Vicky Arulsingam <vix@garinungkadol.com>
 * @copyright Copyright (c) 2012, Vicky Arulsingam
 * @link http://garinungkadol.com/themes/eureka
 * @license http://www.gnu.org/licenses/gpl-2.0.html 
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 *
 * @since Eureka 1.0
 */
if ( ! isset( $content_width ) )
	$content_width = 638; /* pixels */

if ( ! function_exists( 'eureka_setup' ) ):
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 *
 * @since Eureka 1.0
 */
function eureka_setup() {

	/**
	 * Make theme available for translation
	 * Translations can be filed in the /languages/ directory
	 */
	load_theme_textdomain( 'eureka', get_template_directory() . '/languages' );


	/**
	 * Common functions
	 */
	require( get_template_directory() . '/functions/common.php' );
	
	/**
	 * Additional Template Tags
	 */
	require( get_template_directory() . '/functions/template-tags.php' );
	
	
	/**
	 * WordPress Hooks
	 */
	require( get_template_directory() . '/functions/hooks.php' );
	
	/**
	 * Further refinements
	 */
	require( get_template_directory() . '/functions/tweaks.php' );
	
	/**
	 * Sidebar and Widget functionality
	 */
	require( get_template_directory() . '/functions/widgets.php' );
	
	/**
	 * Theme options
	 */
	require( get_template_directory() . '/functions/theme-options.php' );
	
	/**
	 * Handle theme options
	 */
	 if( is_admin() ) {
		
		new Eureka_Theme_Options();
	}	
	
	/**
	 * Theme Customizer
	 */
	require( get_template_directory() . '/functions/theme-options-customizer.php' );
	
	/**
	 * CSS functionality
	 */
	require( get_template_directory() . '/functions/css.php' );
	
	/**
	 * WordPress.com-specific functions and definitions
	 */
	require( get_template_directory() . '/functions/wpcom.php' );


	

	 

	/**
	 * Add default posts and comments RSS feed links to head
	 */
	add_theme_support( 'automatic-feed-links' );

	/**
	 * This theme uses wp_nav_menu() in one location.
	 */
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'eureka' ),
	) );

	/**
	 * Add support for Post Formats
	 */
	add_theme_support( 'post-formats', array( 'aside', 'gallery', 'quote', 'chat', 'link', 'image', 'status', 'video', 'audio' ) );
	
	/**
	 * Add support for a custom background.
	 * Call a custom callback to handle the background css
	 */
	 add_theme_support( 'custom-background', array( 'default-color' => '488ba3', 'wp-head-callback' => 'eureka_bg_gradient' ) );
	 

	
}
endif; // eureka_setup
add_action( 'after_setup_theme', 'eureka_setup' );






