<?php
class Eureka_Theme_Options{

	/**
	 * Stores the page menu hook
	 *
	 * @var string
	 * @since Eureka 1.0
	 * @access private
	 */
	var $page = '';
	
	/** 
	 * Stores the update message
	 *
	 * @var string
	 * @since Eureka 1.0
	 * @access private
	 */
	var $updated = '';
	
	/** 
	 * Stores the list of option tabs and their content
	 *
	 * @var array
	 * @since Eureka 1.0
	 * @access private
	 */
	var $tabs = array();
	
	/**
	 * Constructor
	 *
	 * @since Eureka 1.0
	 */
	function __construct(){
	
		add_action( 'admin_menu', array( $this, 'init' ) );
	}
	
	/**
	 * Set up the hooks for the theme settings page
	 *
	 * @since Eureka 1.0
	 */
	function init(){
		if ( ! current_user_can('edit_theme_options') )
			return;
		
		// Create the settings page
		$this->page = $page = add_theme_page( __('Theme Options', 'eureka' ), __('Theme Options', 'eureka'), eureka_get_settings_page_cap(), 'eureka-settings', array(&$this, 'admin_page') );	
		
	
		 
		// Set the tabs
		$this->tabs = $this->get_settings_tabs();
		
		// Actions to run when the settings page is loaded
		add_action("load-$page", array(&$this, 'admin_load'));
		add_action("load-$page", array(&$this, 'take_action'), 49);
	}
	
	/** 
	 * Enqueue the scripts and styles for settings page
	 *
	 * @since Eureka 1.0
	 */
	function admin_load(){
		wp_enqueue_script( 'eureka-theme-colors', get_template_directory_uri() . '/assets/js/theme-color-settings.js', array( 'jquery', 'farbtastic' ), '20120411', true );
		wp_enqueue_style('farbtastic');
		wp_enqueue_style('eureka-admin', get_template_directory_uri() . '/assets/css/admin.css');
	
	}
	
	/**
	 * Return a list of tabs for the settings page
	 *
	 * Each tab consists of an array for the following:
	 * `name`: Tab ID
	 * `title`: Display title
	 * `callback`: Callback function to display tab HTML
	 * `parameters`: Parameters to use with callback function
	 *
	 * Override using the filter hook `eureka_get_settings_page_tabs`
	 *
	 * @return array
	 * @since Eureka 1.0
	 */
	function get_settings_tabs(){
		$tabs = array(
			'color-scheme' => array(
				'name' => 'color-scheme',
				'title' => __( 'Color Scheme', 'eureka' ),
				'callback' => array( $this, 'show_mod_page_color_scheme' ),
				'parameters' => array( 'current_scheme' => get_theme_mod( 'color_scheme', 'blue' ), 'default_colors' => eureka_get_default_color_schemes() )
				),
			'custom-colors' => array(
				'name' => 'custom-color',
				'title' => __( 'Custom Colors', 'eureka' ),
				'callback' => array( $this, 'show_mod_page_custom_colors' ),
				'parameters' => array()

			)
		);
		
		return apply_filters( 'eureka_get_settings_page_tabs', $tabs );
	}
	
	/**
	 * Execute color scheme modifications
	 *
	 * @since Eureka 1.0
	 */
	function take_action() {

		if ( empty($_POST) )
			return;

		$color_schemes = eureka_get_default_color_schemes();
		$current_scheme = get_theme_mod( 'color_scheme' );
			
		// Handles the modification for the color scheme	
		if( isset( $_POST['color-scheme'] ) ){
			// Validate the nonce before continuing
			check_admin_referer( 'eureka-settings' );
			// Make sure that color scheme is valid
			$scheme = wp_filter_nohtml_kses( $_POST['color-scheme'] );
			$valid_scheme = ( array_key_exists( $scheme, $color_schemes ) ? $scheme : $current_scheme );
			// Update color scheme and colors
			set_theme_mod('color_scheme', $valid_scheme);
			
			foreach( $color_schemes as $name => $color ) {
				if( $name == $valid_scheme ) {
					set_theme_mod( 'background_color', $color['background_color'] );
					set_theme_mod( 'background_color_bottom', $color['background_color_bottom'] );
					set_theme_mod( 'bubble_background', $color['bubble_background'] );
					set_theme_mod( 'bubble_text_color', $color['bubble_text_color'] );
					set_theme_mod( 'bubble_link_color', $color['bubble_link_color'] );
					set_theme_mod( 'other_text_color', $color['other_text_color'] );
					set_theme_mod( 'other_hover_color', $color['other_hover_color'] );
					set_theme_mod( 'link_color', $color['link_color'] );
				}
			
			}
			$this->updated = true;
		}
		
		// Handles modification of individual cololrs
		if( isset( $_GET['tab'] ) && $_GET['tab'] == 'custom-colors' ): 
			// Validate the nonce before continuing
			check_admin_referer( 'eureka-settings' );
			
			// Validate each color before updating
			if( isset( $_POST['background-color'] ) ) {
				$color = eureka_validate_color( $_POST['background-color'], $color_schemes[$current_scheme]['background_color'] );
				set_theme_mod('background_color', $color);
			}
		
			if( isset( $_POST['background-color-bottom'] ) ) {
				$color = eureka_validate_color( $_POST['background-color-bottom'], $color_schemes[$current_scheme]['background_color_bottom'] );
				set_theme_mod('background_color_bottom', $color);
			}
		
			if( isset( $_POST['bubble-background'] ) ) {
				$color = eureka_validate_color( $_POST['bubble-background'], $color_schemes[$current_scheme]['bubble_background'] );
				set_theme_mod('bubble_background', $color);
			}
		
			if( isset( $_POST['bubble-text-color'] ) ) {
				$color = eureka_validate_color( $_POST['bubble-text-color'], $color_schemes[$current_scheme]['bubble_text_color'] );
				set_theme_mod('bubble_text_color', $color);
			}
		
			if( isset( $_POST['bubble-link-color'] ) ) {
				$color = eureka_validate_color( $_POST['bubble-link-color'], $color_schemes[$current_scheme]['bubble_link_color'] );
				set_theme_mod('bubble_link_color', $color);
			}
		
			if( isset( $_POST['other-text-color'] ) ) {
				$color = eureka_validate_color( $_POST['other-text-color'], $color_schemes[$current_scheme]['other_text_color'] );
				set_theme_mod('other_text_color', $color);
			}
		
			if( isset( $_POST['other-hover-color'] ) ) {
				$color = eureka_validate_color( $_POST['other-hover-color'], $color_schemes[$current_scheme]['other_hover_color'] );
				set_theme_mod('other_hover_color', $color);
			}
			
			if( isset( $_POST['link-color'] ) ) {
				$color = eureka_validate_color( $_POST['link-color'], $color_schemes[$current_scheme]['link_color'] );
				set_theme_mod('link_color', $color);
			}
		endif;

		$url_parameters = isset($_GET['tab'])? 'updated=true&tab='.$_GET['tab'] : 'updated=true';
		wp_safe_redirect(admin_url('themes.php?page=eureka-settings&'.$url_parameters));
		exit();
		
	}

	/**
	 * Display the theme settings page
	 *
	 * @since Eureka 1.0
	 **/
	function admin_page(){
		if( isset( $_GET['updated'] ) && $_GET['updated'] == 'true' ) 
			$this->updated = true;
		else
			$this->updated = false;
?>
<div class="wrap" id="custom-background">
<?php screen_icon(); ?>
<h2><?php _e('Eureka Theme Settings', 'eureka'); ?></h2>
<?php if ( !empty($this->updated) ) { ?>
<div id="message" class="updated">
<p><?php printf( __( 'Settings updated. <a href="%s">Visit your site</a> to see how it looks.', 'eureka' ), home_url( '/' ) ); ?></p>
</div>
<?php } ?>
<form method="post" action="">
<?php $tab = $this->check_display_tab(); ?>
<?php $this->show_settings_tabs( $tab ); ?>

<?php wp_nonce_field('eureka-settings'); ?>
<?php submit_button( null, 'primary', 'save-eureka-settings' ); ?>
</form>

</div>
<?php
	}


	/**
	 * Validate the chosen tab string
	 *
	 * @since Eureka 1.0
	 */
	function check_display_tab() {

		if( isset( $_GET['tab'] ) ) $tab = esc_attr( $_GET['tab'] );
		else $tab = 'color-scheme';
		
		if( array_key_exists( $tab, $this->tabs ) )
			$tab = $tab;
		else
			$tab = 'color-scheme';

		return $tab;
	}

	/**
	 * Handle the HTML display for tabbed data
	 *
	 * @since Eureka 1.0
	 **/
	function show_settings_tabs( $current = 'custom-colors'){
	
	$tabs = $this->tabs;
	
	
		echo '<h2 class="nav-tab-wrapper">';
		foreach( $tabs as $tab => $tab_info ){
			$class = ( $tab == $current ) ? ' nav-tab-active' : '';
			echo "<a class='nav-tab$class' href='?page=eureka-settings&tab=$tab'>{$tab_info['title']}</a>";
		}
		echo '</h2>';
		
	$this->show_tab_form( $current );	

	}

    /**
	 * Display the content of each tab's callback function.
	 *
	 * @since Eureka 1.0
	 **/
	function show_tab_form( $current ){
		
		foreach( $this->tabs as $tab => $tab_info ){
			if( $current == $tab ) {
				call_user_func_array( $tab_info['callback'], $tab_info['parameters'] );
			}
		
		}

	}

	/**
	 * Show HTML for custom colors tab
	 *
	 * @since Eureka 1.0
	 */
	function show_mod_page_custom_colors(){	
		$color_scheme = get_theme_mod( 'color_scheme', 'blue' );
		$scheme_colors = eureka_get_default_color_schemes();
		$defaults = $scheme_colors[$color_scheme] ;
		

	
	?>
<p><?php _e( 'Modify the theme colors:', 'eureka' ); ?></p>

<table class="form-table">
<tbody>
<tr valign="top">
<td colspan="2"><h4><?php _e( 'Background Color', 'eureka' ); ?></h4></td>
</tr>
<tr valign="top">
<th scope="row"><?php _e( 'Background Color', 'eureka' ); ?></th>
<td><fieldset><legend class="screen-reader-text"><span><?php _e( 'Background Color', 'eureka' ); ?></span></legend>
<div style="position:relative;">
	<input type="text" name="background-color" class="special" value="#<?php echo esc_attr(get_theme_mod( 'background_color', $defaults['background_color'])) ?>" />
	<a href="#" class="pickcolor"><?php _e('Select Color', 'eureka') ?></a> 
	<div class="colorpicker" style="z-index:100; position:absolute; display:none;"></div>
	<a href="#" class="clearcolor"><?php _e( '(Default)', 'eureka' ); ?></a>
	<input type="hidden" class="defaultcolor" value="#<?php echo esc_attr( $defaults['background_color'] ); ?>" />
</div>
</fieldset></td>
</tr>
<tr valign="top">
<th scope="row"><?php _e( 'Bottom Gradient Color', 'eureka' ); ?></th>
<td><fieldset><legend class="screen-reader-text"><span><?php _e( 'Bottom Gradient Color', 'eureka' ); ?></span></legend>
<div style="position:relative;">
	<input type="text" name="background-color-bottom" class="special" value="#<?php echo esc_attr(get_theme_mod( 'background_color_bottom', $defaults['background_color_bottom'])) ?>" />
	<a href="#" class="pickcolor"><?php _e('Select Color', 'eureka') ?></a> 
	<div class="colorpicker" style="z-index:100; position:absolute; display:none;"></div>
	<a href="#" class="clearcolor"><?php _e( '(Default)', 'eureka' ); ?></a>
	<input type="hidden" class="defaultcolor" value="#<?php echo esc_attr( $defaults['background_color_bottom'] ); ?>" />
</div>
</fieldset></td>
</tr>

<tr valign="top">
<td colspan="2"><h4><?php _e( 'Content', 'eureka' ); ?></h4></td>
</tr>

<tr valign="top">
<th scope="row"><?php _e( 'Link Color', 'eureka' ); ?></th>
<td><fieldset><legend class="screen-reader-text"><span><?php _e( 'Link Color', 'eureka' ); ?></span></legend>
<div style="position:relative;">
	<input type="text" name="link-color" id="link-color" value="#<?php echo esc_attr(get_theme_mod( 'link_color',  $defaults['link_color'])) ?>" />
	<a href="#" class="pickcolor"><?php _e('Select Color', 'eureka') ?></a> 
	<div class="colorpicker" style="z-index:100; position:absolute; display:none;"></div>
	<a href="#" class="clearcolor"><?php _e( '(Default)', 'eureka' ); ?></a>
	<input type="hidden" class="defaultcolor" value="#<?php echo esc_attr( $defaults['link_color'] ); ?>" />
</div>
</fieldset></td>
</tr>


<tr valign="top">
<td colspan="2"><h4><?php _e( 'Bubble & Sticky Posts', 'eureka' ); ?></h4></td>
</tr>

<tr valign="top">
<th scope="row"><?php _e( 'Background &amp; Border Color', 'eureka' ); ?></th>
<td><fieldset><legend class="screen-reader-text"><span><?php _e( 'Background &amp; Border Color', 'eureka' ); ?></span></legend>
<div style="position:relative;">
	<input type="text" name="bubble-background" id="bubble-background" value="#<?php echo esc_attr(get_theme_mod( 'bubble_background',  $defaults['bubble_background'])) ?>" />
	<a href="#" class="pickcolor"><?php _e('Select Color', 'eureka') ?></a> 
	<div class="colorpicker" style="z-index:100; position:absolute; display:none;"></div>
	<a href="#" class="clearcolor"><?php _e( '(Default)', 'eureka' ); ?></a>
	<input type="hidden" class="defaultcolor" value="#<?php echo esc_attr( $defaults['bubble_background'] ); ?>" />
</div>
</fieldset></td>
</tr>


<tr valign="top">
<th scope="row"><?php _e( 'Text Color', 'eureka' ); ?></th>
<td><fieldset><legend class="screen-reader-text"><span><?php _e( 'Text Color', 'eureka' ); ?></span></legend>
<div style="position:relative;">
	<input type="text" name="bubble-text-color" id="bubble-text-color" value="#<?php echo esc_attr(get_theme_mod( 'bubble_text_color', $defaults['bubble_text_color'])) ?>" />
	<a href="#" class="pickcolor"><?php _e('Select Color', 'eureka') ?></a> 
	<div class="colorpicker" style="z-index:100; position:absolute; display:none;"></div>
	<a href="#" class="clearcolor"><?php _e( '(Default)', 'eureka' ); ?></a>
	<input type="hidden" class="defaultcolor" value="#<?php echo esc_attr( $defaults['bubble_text_color'] ); ?>" />
</div>
</fieldset></td>
</tr>

<tr valign="top">
<th scope="row"><?php _e( 'Link Color', 'eureka' ); ?></th>
<td><fieldset><legend class="screen-reader-text"><span><?php _e( 'Link Color', 'eureka' ); ?></span></legend>
<div style="position:relative;">
	<input type="text" name="bubble-link-color" id="bubble-link-color" value="#<?php echo esc_attr(get_theme_mod( 'bubble_link_color', $defaults['bubble_link_color'])) ?>" />
	<a href="#" class="pickcolor"><?php _e('Select Color', 'eureka') ?></a> 
	<div class="colorpicker" style="z-index:100; position:absolute; display:none;"></div>
	<a href="#" class="clearcolor"><?php _e( '(Default)', 'eureka' ); ?></a>
	<input type="hidden" class="defaultcolor" value="#<?php echo esc_attr( $defaults['bubble_link_color'] ); ?>" />
</div>
</fieldset></td>
</tr>


<tr valign="top">
<td colspan="2"><h4><?php _e( 'Headers &amp; Footers', 'eureka' ); ?></h4></td>
</tr>

<tr valign="top">
<th scope="row"><?php _e( 'Text &amp; Link Color', 'eureka' ); ?></th>
<td><fieldset><legend class="screen-reader-text"><span><?php _e( 'Text &amp; Link Color', 'eureka' ); ?></span></legend>
<div style="position:relative;">
	<input type="text" name="other-text-color" id="other-text-color" value="#<?php echo esc_attr(get_theme_mod( 'other_text_color',  $defaults['other_text_color'] )) ?>" />
	<a href="#" class="pickcolor"><?php _e('Select Color', 'eureka') ?></a> 
	<div class="colorpicker" style="z-index:100; position:absolute; display:none;"></div>
	<a href="#" class="clearcolor"><?php _e( '(Default)', 'eureka' ); ?></a>
	<input type="hidden" class="defaultcolor" value="#<?php echo esc_attr( $defaults['other_text_color'] ); ?>" />
</div>
</fieldset></td>
</tr>


<tr valign="top">
<th scope="row"><?php _e( 'Hover Link Color', 'eureka' ); ?></th>
<td><fieldset><legend class="screen-reader-text"><span><?php _e( 'Hover Link Color', 'eureka' ); ?></span></legend>
<div style="position:relative;">
	<input type="text" name="other-hover-color" id="other-hover-color" value="#<?php echo esc_attr(get_theme_mod( 'other_hover_color', $defaults['other_hover_color'])) ?>" />
	<a href="#" class="pickcolor"><?php _e('Select Color', 'eureka') ?></a> 
	<div class="colorpicker" style="z-index:100; position:absolute; display:none;"></div>
	<a href="#" class="clearcolor"><?php _e( '(Default)', 'eureka' ); ?></a>
	<input type="hidden" class="defaultcolor" value="#<?php echo esc_attr( $defaults['other_hover_color'] ); ?>" />
</div>
</fieldset></td>
</tr>
</tbody>
</table>
<?php
	}

	/**
	 * Show HTML for color scheme tab
	 *
	 * @since Eureka 1.0
	 **/
	 function show_mod_page_color_scheme( $value, $default_schemes){
	?>
	
<p><?php _e( 'Choose a color scheme:', 'eureka' ); ?></p>
<div id="select-color-schemes">
	<div class="select-color-scheme">
		<h3><?php _e( 'Blue Day', 'eureka' ); ?></h3>
		
		<img src="<?php echo get_template_directory_uri(); ?>/assets/images/color_scheme_blue.png" alt="" />
		
		<input type="radio" name="color-scheme" value="blue"  <?php checked( $value, 'blue'); ?> />
		
	</div>
	
	<div class="select-color-scheme">
		<h3><?php _e( 'Chocolate &amp; Cherries', 'eureka' ); ?></h3>
		
		<img src="<?php echo get_template_directory_uri(); ?>/assets/images/color_scheme_chocolate.png" alt="" />
		<input type="radio" name="color-scheme" value="chocolate"  <?php checked( $value, 'chocolate'); ?> />
	</div>
	
	<div class="select-color-scheme">
		<h3><?php _e( 'Slate', 'eureka' ); ?></h3>
		
		<img src="<?php echo get_template_directory_uri(); ?>/assets/images/color_scheme_slate.png" alt="" />
		<input type="radio" name="color-scheme" value="slate"  <?php checked( $value, 'slate'); ?> />
	</div>
	
	<div class="select-color-scheme">
		<h3><?php _e( 'Purple Monster', 'eureka' ); ?></h3>
		
		<img src="<?php echo get_template_directory_uri(); ?>/assets/images/color_scheme_purple.png" alt="" />
		<input type="radio" name="color-scheme" value="purple"  <?php checked( $value, 'purple'); ?> />
	</div>

</div>
<?php
	}
}
?>