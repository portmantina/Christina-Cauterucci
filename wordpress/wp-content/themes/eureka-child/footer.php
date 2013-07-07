<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content after
 *
 * @package Eureka
 * @since Eureka 1.0
 *
 * @uses do_action() Calls 'eureka_after_content' hook before #main
 * @uses do_action() Calls 'eureka_credits' hook after .site-info
 */
?>
	<?php 

	/* do_action( 'eureka_after_content' ); ?>
	</div><!-- #main -->

	<footer id="colophon" class="site-footer" role="contentinfo">
		<div class="site-info">
			<?php do_action( 'eureka_credits' ); ?>
			<a href="<?php echo esc_url( __( 'http://wordpress.org/', 'eureka' ) ); ?>" title="<?php esc_attr_e( 'A Semantic Personal Publishing Platform', 'eureka' ); ?>" rel="generator"><?php printf( __( 'Proudly powered by %s', 'eureka' ), 'WordPress' ); ?></a>
			<span class="sep"> | </span>
			<a href="<?php echo esc_url( __( 'http://garinungkadol.com/', 'eureka' ) ); ?>" title="<?php esc_attr_e( 'Eureka Theme by Vicky Arulsingam', 'eureka' ); ?>" rel="generator"><?php printf( __( '%1$s theme by %2$s', 'eureka' ), 'Eureka', 'Vicky Arulsingam' ); ?></a>
		</div><!-- .site-info -->
	</footer><!-- .site-footer .site-footer -->
</div><!-- #page .hfeed .site -->
<?php wp_footer(); ?>
</body>
</html>