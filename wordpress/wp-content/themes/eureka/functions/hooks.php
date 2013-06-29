<?php
/**
 * Enqueue scripts and styles
 *
 * @since Eureka 1.0
 */
function eureka_enqueue_scripts() {
	global $post;

	// Load scripts
	wp_enqueue_script( 'jquery' );

	// Change menu style for small browser widths
	wp_enqueue_script( 'eureka-small-menu', get_template_directory_uri() . '/assets/js/small-menu.js', 'jquery', '20120411', true );

    // Keyboard navigation for images
	if ( is_singular() && wp_attachment_is_image( $post->ID ) ) {
		wp_enqueue_script( 'eureka-keyboard-image-navigation', get_template_directory_uri() . '/assets/js/keyboard-image-navigation.js', array( 'jquery' ), '20120411' );
	}
}
add_action( 'wp_enqueue_scripts', 'eureka_enqueue_scripts' );

/** 
 * Enqueue the comment-reply script only when comments are available and when threaded comments are enabled.
 * Using the hook 'comment_form_before' bypasses the need for checking for singular view.
 *
 * @since Eureka 1.0
 */
function eureka_comment_reply(){

	// Display the comment-reply script
	if ( get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'comment_form_before', 'eureka_comment_reply' );


/**
 * Display more stuff in the document head.
 *
 * Display the HTML5 shiv javascript in the document head. 
 * This script is not enqueued because of the CSS conditional tags.
 *
 * @since Eureka 1.0
 */
function eureka_head(){
?>
<!--[if lt IE 9]>
<script src="<?php echo get_template_directory_uri(); ?>/assets/js/html5.js" type="text/javascript"></script>
<![endif]-->
<?php
}
add_action( 'wp_head', 'eureka_head' );


/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 *
 * @param array $args
 * @return array $args Array of arguments for wp_page_menu()
 *
 * @since Eureka 1.0
 */
function eureka_page_menu_args( $args ) {
	$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'eureka_page_menu_args' );

/**
 * Filter in a link to a content ID attribute for the next/previous image links on image attachment pages
 *
 * @param string $url
 * @param int $id
 * @return string $url URL for next/previous image
 * 
 * @since Eureka 1.0
 */
function eureka_enhanced_image_navigation( $url, $id ) {
	if ( ! is_attachment() && ! wp_attachment_is_image( $id ) )
		return $url;

	$image = get_post( $id );
	if ( ! empty( $image->post_parent ) && $image->post_parent != $id )
		$url .= '#main';

	return $url;
}
add_filter( 'attachment_link', 'eureka_enhanced_image_navigation', 10, 2 );



/**
 * Print the <title> tag based current location
 *
 * @since Eureka 1.0
 * 
 * @param string $title Current title
 * @param string $sep Separator between title bits
 * @param string $seplocation Where to place separator
 * @return string Enhanced title tag
 */
function eureka_title_tag( $title, $sep, $seplocation ) {
	global $page, $paged;
	$enhanced_title = '';
	// Add the blog name.
	$enhanced_title = get_bloginfo( 'name' );

	// Add the blog description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		$enhanced_title .=  " $sep $site_description";

	// Add a page number if necessary:
	if ( $paged >= 2 || $page >= 2 )
		$enhanced_title .= ' ' . $sep . ' ' . sprintf( __( 'Page %s', 'eureka' ), max( $paged, $page ) );
	
	return $title . $enhanced_title ;
}
add_filter( 'wp_title', 'eureka_title_tag', 10, 3);


/**
 * Filters the post content assigned to the "chat" post format allowing for more than
 * two speakers.
 *
 * @link http://justintadlock.com/archives/2012/08/21/post-formats-chat
 *
 * @since Eureka 1.0
 * 
 * @global array $_eureka_post_format_chat_ids An array of IDs for the chat rows based on the author.
 * @param string $content The content of the post.
 * @return string $chat_output The formatted content of the post.
 */
function eureka_chat_content( $content ) {
	global $_eureka_post_format_chat_ids;

	/* If this is not a 'chat' post, return the content. */
	if ( !has_post_format( 'chat' ) )
		return $content;

	/* Set the global variable of speaker IDs to a new, empty array for this chat. */
	$_eureka_post_format_chat_ids = array();

	/* Allow the separator (separator for speaker/text) to be filtered. */
	$separator = apply_filters( 'eureka_post_format_chat_separator', ':' );

	/* Open the chat transcript div and give it a unique ID based on the post ID. */
	$chat_output = "\n\t\t\t" . '<div id="chat-transcript-' . esc_attr( get_the_ID() ) . '" class="chat-transcript">';

	/* Split the content to get individual chat rows. */
	$chat_rows = preg_split( "/(\r?\n)+|(<br\s*\/?>\s*)+/", $content );

	/* Loop through each row and format the output. */
	foreach ( $chat_rows as $chat_row ) {

		/* If a speaker is found, create a new chat row with speaker and text. */
		if ( strpos( $chat_row, $separator ) ) {

			/* Split the chat row into author/text. */
			$chat_row_split = explode( ':', trim( $chat_row ), 2 );

			/* Get the chat author and strip tags. */
			$chat_author = strip_tags( trim( $chat_row_split[0] ) );

			/* Get the chat text. */
			$chat_text = trim( $chat_row_split[1] );

			/* Get the chat row ID (based on chat author) to give a specific class to each row for styling. */
			$speaker_id = eureka_format_chat_row_id( $chat_author );

			/* Open the chat row. */
			$chat_output .= "\n\t\t\t\t" . '<div class="chat-row ' . sanitize_html_class( "chat-speaker-{$speaker_id}" ) . '">';

			/* Add the chat row author. */
			$chat_output .= "\n\t\t\t\t\t" . '<div class="chat-author ' . sanitize_html_class( strtolower( "chat-author-{$chat_author}" ) ) . ' vcard"><cite class="fn">' . apply_filters( 'eureka_post_format_chat_author', $chat_author, $speaker_id ) . '</cite>' . $separator . '</div>';

			/* Add the chat row text. */
			$chat_output .= "\n\t\t\t\t\t" . '<div class="chat-text">' . str_replace( array( "\r", "\n", "\t" ), '', apply_filters( 'eureka_post_format_chat_text', $chat_text, $chat_author, $speaker_id ) ) . '</div>';

			/* Close the chat row. */
			$chat_output .= "\n\t\t\t\t" . '</div><!-- .chat-row -->';
		}

		/**
		 * If no author is found, assume this is a separate paragraph of text that belongs to the
		 * previous speaker and label it as such, but let's still create a new row.
		 */
		else {

			/* Make sure we have text. */
			if ( !empty( $chat_row ) ) {

				/* Open the chat row. */
				$chat_output .= "\n\t\t\t\t" . '<div class="chat-row ' . sanitize_html_class( "chat-speaker-{$speaker_id}" ) . '">';

				/* Don't add a chat row author.  The label for the previous row should suffice. */

				/* Add the chat row text. */
				$chat_output .= "\n\t\t\t\t\t" . '<div class="chat-text">' . str_replace( array( "\r", "\n", "\t" ), '', apply_filters( 'eureka_post_format_chat_text', $chat_row, $chat_author, $speaker_id ) ) . '</div>';

				/* Close the chat row. */
				$chat_output .= "\n\t\t\t</div><!-- .chat-row -->";
			}
		}
	}

	/* Close the chat transcript div. */
	$chat_output .= "\n\t\t\t</div><!-- .chat-transcript -->\n";

	/* Return the chat content and apply filters for developers. */
	return apply_filters( 'eureka_post_format_chat_content', $chat_output );
}
/* Filter the content of chat posts. */
add_filter( 'the_content', 'eureka_chat_content' );


if ( ! function_exists( 'eureka_comment' ) ) :
/**
 * Template for comments and pingbacks.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 *
 * @since Eureka 1.0
 */
function eureka_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case 'pingback' :
		case 'trackback' :
	?>
	<li class="post pingback">
		<p><?php _e( 'Pingback:', 'eureka' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __( '(Edit)', 'eureka' ), ' ' ); ?></p>
	<?php
			break;
		default :
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<article id="comment-<?php comment_ID(); ?>" class="comment">
			<footer>
				<div class="comment-author vcard">
					<?php echo get_avatar( $comment, 40 ); ?>
					<?php printf( __( '%s <span class="says">says:</span>', 'eureka' ), sprintf( '<cite class="fn">%s</cite>', get_comment_author_link() ) ); ?>
				</div><!-- .comment-author .vcard -->
				<?php if ( $comment->comment_approved == '0' ) : ?>
					<em><?php _e( 'Your comment is awaiting moderation.', 'eureka' ); ?></em>
					<br />
				<?php endif; ?>

				<div class="comment-meta commentmetadata">
					<a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>"><time pubdate datetime="<?php comment_time( 'c' ); ?>">
					<?php
						/* translators: 1: date, 2: time */
						printf( __( '%1$s at %2$s', 'eureka' ), get_comment_date(), get_comment_time() ); ?>
					</time></a>
					<?php edit_comment_link( __( '(Edit)', 'eureka' ), ' ' );
					?>
				</div><!-- .comment-meta .commentmetadata -->
			</footer>

			<div class="comment-content"><?php comment_text(); ?></div>

			<div class="reply">
				<?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
			</div><!-- .reply -->
		</article><!-- #comment-## -->

	<?php
			break;
	endswitch;
}
endif; // ends check for eureka_comment()


/**
 * Filter Capability for Theme Settings Page
 * 
 * 
 * This filter implements a WordPress 3.2 fix for
 * a minor bug, in which add_theme_page() is passed
 * the "edit_theme_options" capability, but the
 * settings page form is passed through options.php,
 * which expects the "manage_options" capability.
 *
 * This allows EDITORS to access the Theme options page
 * and update/save Theme settings.
 * 
 * @since	Eureka 1.0
 *
 * @return string Appropriate capability for saving Theme settings.
 */
function eureka_get_settings_page_cap() {
	return 'edit_theme_options';
}
// Hook into option_page_capability_{option_page}
add_action( 'option_page_capability_eureka-settings', 'eureka_get_settings_page_cap' );

?>