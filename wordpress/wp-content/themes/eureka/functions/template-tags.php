<?php
/**
 * Custom template tags for this theme.
 *
 * @package Eureka
 * @subpackage Template Tags
 */

if ( ! function_exists( 'eureka_content_nav' ) ):
/**
 * Display navigation to next/previous pages when applicable
 * 
 * @since Eureka 1.0
 *
 * @param string $nav_id ID to use for <nav> tag.
 * @uses apply_filters() Calls 'eureka-single-nav' hook to indicate whether or not
 * to show page navigation links in single posts.
 **/
function eureka_content_nav( $nav_id ) {
	global $wp_query;
	
	$show_single_nav = apply_filters( 'eureka-single-nav', false );

	if( is_single() && $show_single_nav == false )
		return;
	
	// Theme supports single post navigation display
	$nav_class = 'site-navigation paging-navigation';
	if ( is_single() )
		$nav_class = 'site-navigation post-navigation';

	?>
	<nav role="navigation" id="<?php echo esc_attr( $nav_id ); ?>" class="<?php echo $nav_class; ?>">
		<h1 class="assistive-text"><?php _e( 'Post navigation', 'eureka' ); ?></h1>

	<?php if ( is_single() ) : // navigation links for single posts ?>

		<?php previous_post_link( '<div class="nav-previous">%link</div>', '<span class="meta-nav">' . _x( '&larr;', 'Previous post link', 'eureka' ) . '</span> %title' ); ?>
		<?php next_post_link( '<div class="nav-next">%link</div>', '%title <span class="meta-nav">' . _x( '&rarr;', 'Next post link', 'eureka' ) . '</span>' ); ?>

	<?php elseif ( $wp_query->max_num_pages > 1 && ( is_home() || is_archive() || is_search() ) ) : // navigation links for home, archive, and search pages ?>
		
		<?php if ( get_previous_posts_link() ) : ?>
		<div class="nav-next"><?php previous_posts_link( __( '<span class="meta-nav">&larr;</span> Newer posts  ', 'eureka' ) ); ?></div>
		<?php endif; ?>
	
	
		<?php if ( get_next_posts_link() ) : ?>
		<div class="nav-previous"><?php next_posts_link( __( ' Continue <span class="meta-nav">&rarr;</span>', 'eureka' ) ); ?></div>
		<?php endif; ?>

		

	<?php endif; ?>

	</nav><!-- #<?php echo $nav_id; ?> -->
	<?php
}
endif; // eureka_content_nav



if ( ! function_exists( 'eureka_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 *
 * @since Eureka 1.0
 */
function eureka_posted_on() {
	
	if( 'aside' == get_post_format() || 'status' == get_post_format() ):
		
		printf( __( '<a href="%1$s" title="%2$s" rel="bookmark">Posted on <time class="entry-date" datetime="%3$s" pubdate>%4$s at %5$s</time></a><span class="byline"> by <span class="author vcard"><a class="url fn n" href="%6$s" title="%7$s" rel="author">%8$s</a></span></span>', 'eureka' ),
		esc_url( get_permalink() ),
		esc_attr( get_the_time() ),
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_html( get_the_time() ) ,
		esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
		esc_attr( sprintf( __( 'View all posts by %s', 'eureka' ), get_the_author() ) ),
		esc_html( get_the_author() )
	   );
	
	elseif ( 'chat' == get_post_format() ):
		printf( __( 'Posted on <a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s" pubdate>%4$s</time></a>', 'eureka' ),
		esc_url( get_permalink() ),
		esc_attr( get_the_time() ),
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() )
	);
	else :
	printf( __( 'Posted on <a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s" pubdate>%4$s</time></a><span class="byline"> by <span class="author vcard"><a class="url fn n" href="%5$s" title="%6$s" rel="author">%7$s</a></span></span>', 'eureka' ),
		esc_url( get_permalink() ),
		esc_attr( get_the_time() ),
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
		esc_attr( sprintf( __( 'View all posts by %s', 'eureka' ), get_the_author() ) ),
		esc_html( get_the_author() )
	);
	endif;
}
endif;



/**
 * Print HTML for 'gallery' post format.
 * 
 * Displays at least one gallery image along with the permalink to the 
 * single post view.
 * 
 * @since Eureka 1.0
 * @uses apply_filters() Calls 'eureka_gallery_thumbnail_count' hook to customize number of thumbnails to display.
 */
if ( ! function_exists( 'eureka_content_gallery' ) ):
function eureka_content_gallery(){
	global $post;
	$display_cnt = (int) apply_filters( 'eureka_gallery_thumbnail_count', 3 );
	$image_img_tag = array();
	$images = get_children( array( 'post_parent' => $post->ID, 'post_type' => 'attachment', 'post_mime_type' => 'image', 'orderby' => 'menu_order', 'order' => 'ASC', 'numberposts' => 999 ) );
	if ( $images ) :
		$total_images = count( $images );
		if( $total_images >0 ): ?>
		<figure class='gallery gallery-columns-<?php echo $display_cnt;?> gallery-thumb'>
		<?php
			$cnt = 0;
			foreach( $images as $image ){
				$cnt++;
				if( $cnt > $display_cnt ) {	
					continue;
				}
				$image_img_tag = wp_get_attachment_image( $image->ID, 'thumbnail' ); ?>
				
						<p class="gallery-item"><a href="<?php the_permalink(); ?>"><?php echo $image_img_tag; ?></a></p>
		<?php
			}
		?>
		</figure><!-- .gallery .gallery-columns-3 .gallery-thumb -->
		<?php
		else:
			$image = array_shift( $images );
			$image_img_tag = wp_get_attachment_image( $image->ID, 'thumbnail' );
			?>
			<figure class="gallery-thumb">
					<a href="<?php the_permalink(); ?>"><?php echo $image_img_tag; ?></a>
				</figure><!-- .gallery-thumb -->
			<?php
		endif;
			$more_images = $total_images - $display_cnt;
			// Only display text if we have more than x images.
			if( $more_images > 0 ):
			?>
				<br clear="all" />
				<p><em><?php printf( _n( 'This gallery contains <a %1$s>%2$s more photo</a>.', 'This gallery contains <a %1$s>%2$s more photos</a>.', $more_images, 'eureka' ),
						'href="' . esc_url( get_permalink() ) . '" title="' . sprintf( esc_attr__( 'Permalink to %s', 'eureka' ), the_title_attribute( 'echo=0' ) ) . '" rel="bookmark"',
						number_format_i18n( $more_images )
					); ?></em></p>
				<?php endif;
			endif;
			?><br clear="all" />
			<?php
			the_excerpt();
			wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'eureka' ), 'after' => '</div>' ) ); ?>
<?php	
}
endif;


/**
 * Display post format title
 *
 * @since Eureka 1.0
 *
 * @uses apply_filters() Calls 'eureka_included_formats' hook to check for post formats to display
 */
if ( ! function_exists( 'eureka_show_post_format_title' ) ):
function eureka_show_post_format_title(){
	
	$allowed_formats = array( 'status', 'aside', 'quote' );
	$allowed_formats = apply_filters( 'eureka_included_formats', $allowed_formats );
	
	if( in_array( get_post_format(), $allowed_formats )  ) {
		echo get_post_format(); 
	} 
}
endif;

/**
 * Display post format icon
 *
 * @since Eureka 1.0
 */
if ( ! function_exists( 'eureka_show_post_format_icon' ) ):
function eureka_show_post_format_icon( ){
		
	$format = esc_attr( get_post_format() ) ;
	// Set default post format
	if( empty( $format) )
		$format = 'standard';
	
	// Show icon if it isn't a sticky post
	if( !is_sticky() ): 
	?>
	<div class="entry-format"><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'eureka' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><img src="<?php echo get_template_directory_uri() .'/assets/images/format-'. $format .'.png' ;?>" /></a></div><!-- .entry-format -->		
	<?php endif; ?>
<?php	
}
endif;