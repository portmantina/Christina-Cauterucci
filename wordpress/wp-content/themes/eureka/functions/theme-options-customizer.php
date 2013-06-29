<?php
/**
 * Color controls
 *
 * @package Eureka
 * @subpackage Color Scheme
 */


/**
 * Adds color settings to the Customize page
 * 
 * @since Eureka 1.1
 */
function eureka_customize_register( $wp_customize ){
	$defaults = eureka_default_settings();
	
	$wp_customize->add_setting( 'background_color_bottom', array(
		'default'    => $defaults['background_color_bottom'],
		'type'       => 'theme_mod',
		'sanitize_callback' => 'sanitize_hex_color_no_hash',
		'capability' => 'edit_theme_options',
	) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'background_color_bottom',
		array( 
			'label' => __( 'Bottom Gradient Color', 'eureka' ),
			'section' => 'colors',
			'settings' => 'background_color_bottom',
			'statuses' => array( 
				$defaults['background_color_bottom'] => __('Default', 'eureka' ),
				)
		)
	) );
	

	$wp_customize->add_setting( 'bubble_background', array(
		'default'    => $defaults['bubble_background'],
		'type'       => 'theme_mod',
		'sanitize_callback' => 'sanitize_hex_color_no_hash',
		'capability' => 'edit_theme_options',
	) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bubble_background',
		array( 
			'label' => __( 'Background & Border Color for Bubble/Sticky Post', 'eureka' ),
			'section' => 'colors',
			'settings' => 'bubble_background',
			'statuses' => array( 
				$defaults['bubble_background'] => __('Default', 'eureka' ),
				)
		)
	) );

	$wp_customize->add_setting( 'bubble_text_color', array(
		'default'    => $defaults['bubble_text_color'],
		'type'       => 'theme_mod',
		'sanitize_callback' => 'sanitize_hex_color_no_hash',
		'capability' => 'edit_theme_options',
	) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bubble_text_color',
		array( 
			'label' => __( 'Text Color for Bubble/Sticky Post', 'eureka' ),
			'section' => 'colors',
			'settings' => 'bubble_text_color',
			'statuses' => array( 
				$defaults['bubble_text_color'] => __('Default', 'eureka' ),
				)
		)
	) );
	
	$wp_customize->add_setting( 'bubble_link_color', array(
		'default'    => $defaults['bubble_link_color'],
		'type'       => 'theme_mod',
		'sanitize_callback' => 'sanitize_hex_color_no_hash',
		'capability' => 'edit_theme_options',
	) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bubble_link_color',
		array( 
			'label' => __( 'Link for Bubble/Sticky Post', 'eureka' ),
			'section' => 'colors',
			'settings' => 'bubble_link_color',
			'statuses' => array( 
				$defaults['bubble_link_color'] => __('Default', 'eureka' ),
				)
		)
	) );
	
	
	$wp_customize->add_setting( 'other_text_color', array(
		'default'    => $defaults['other_text_color'],
		'type'       => 'theme_mod',
		'sanitize_callback' => 'sanitize_hex_color_no_hash',
		'capability' => 'edit_theme_options',
	) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'other_text_color',
		array( 
			'label' => __( 'Text/Link Color for Header/Footer', 'eureka' ),
			'section' => 'colors',
			'settings' => 'other_text_color',
			'statuses' => array( 
				$defaults['other_text_color'] => __('Default', 'eureka' ),
				)
		)
	) );
	
	$wp_customize->add_setting( 'other_hover_color', array(
		'default'    => $defaults['other_hover_color'],
		'type'       => 'theme_mod',
		'sanitize_callback' => 'sanitize_hex_color_no_hash',
		'capability' => 'edit_theme_options',
	) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'other_hover_color',
		array( 
			'label' => __( 'Link Hover Color for Header/Footer', 'eureka' ),
			'section' => 'colors',
			'settings' => 'other_hover_color',
			'statuses' => array( 
				$defaults['other_hover_color'] => __('Default', 'eureka' ),
				)
		)
	) );
	
	$wp_customize->add_setting( 'link_color', array(
		'default'    => $defaults['link_color'],
		'type'       => 'theme_mod',
		'sanitize_callback' => 'sanitize_hex_color_no_hash',
		'capability' => 'edit_theme_options',
	) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'link_color',
		array( 
			'label' => __( 'Content Link Color', 'eureka' ),
			'section' => 'colors',
			'settings' => 'link_color',
			'statuses' => array( 
				$defaults['link_color'] => __('Default', 'eureka' ),
				)
		)
	) );

}
add_action( 'customize_register', 'eureka_customize_register' );
?>