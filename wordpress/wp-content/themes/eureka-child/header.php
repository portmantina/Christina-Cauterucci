<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package Eureka
 * @since Eureka 1.0
 *
 * @uses do_action() Calls 'eureka_before_header' hook after #page
 * @uses do_action() Calls 'eureka_after_header' hook after header #masthead
 * @uses do_action() Calls 'eureka_before_content' hook after #main
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width" />
<title><?php wp_title( '|', true, 'right' ); ?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
	
<div id="page" class="hfeed site">
	<?php do_action( 'eureka_before_header' ); ?>
	<header id="masthead" role="banner">
	    &nbsp;
		<hgroup class="site-header">
				<h1 class="site-title"><a href="<?php echo home_url( '/' ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
				<h2 class="site-description"><?php bloginfo( 'description' ); ?></h2>
		</hgroup><!-- .site-header -->
		
		
		<a name="about"></a> 
	</header><!-- #masthead -->
	<?php do_action( 'eureka_after_header' ); ?>
	<div id="main">
		
	<?php do_action( 'eureka_before_content' ); ?>
	