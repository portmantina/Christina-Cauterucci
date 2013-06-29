<?php
/**
 * The Sidebar containing three widget areas.
 *
 * @package Eureka
 * @since Eureka 1.0
 *
 * @uses do_action() Calls 'eureka_before_sidebar' hook after #secondary
 * @uses do_action() Calls 'eureka_after_sidebar' hook after end of #secondary
 */
?>
		<div id="secondary" class="three" role="complementary">
		<?php do_action( 'eureka_before_sidebar' ); ?>
		    <div id="first" class="widget-area">
			<?php if ( ! dynamic_sidebar( 'sidebar-1' ) ) : ?>

				<aside id="search" class="widget widget_search">
					<?php get_search_form(); ?>
				</aside>

			<?php endif; // end sidebar widget area ?>
			</div>
			
			<div id="second" class="widget-area">
			<?php if ( ! dynamic_sidebar( 'sidebar-2' ) ) : ?>

				
				<aside id="meta" class="widget">
					<h1 class="widget-title"><?php _e( 'Meta', 'eureka' ); ?></h1>
					<ul>
						<?php wp_register(); ?>
						<li><?php wp_loginout(); ?></li>
						<?php wp_meta(); ?>
					</ul>
				</aside>

			<?php endif; // end sidebar widget area ?>
			</div>
			
			<div id="third" class="widget-area">
			<?php if ( ! dynamic_sidebar( 'sidebar-3' ) ) : ?>
				<aside id="archives" class="widget">
					<h1 class="widget-title"><?php _e( 'Archives', 'eureka' ); ?></h1>
					<ul>
						<?php wp_get_archives( array( 'type' => 'monthly' ) ); ?>
					</ul>
				</aside>

			<?php endif; // end sidebar widget area ?>
			</div>
			<?php do_action( 'eureka_after_sidebar' ); ?>
		</div><!-- #secondary .widget-area -->
