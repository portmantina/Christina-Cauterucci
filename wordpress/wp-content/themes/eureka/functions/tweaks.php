<?php
/**
 * Custom functions that act independently of the theme templates
 *
 * @package Eureka
 */

/**
 * Set the number of images to show for gallery posts in index/archive views.
 *
 * @since Eureka 1.0
 */
function eureka_gallery_thumbnail_count( $count ){
	$count = 1;
	return $count;
}
add_filter( 'eureka_gallery_thumbnail_count', 'eureka_gallery_thumbnail_count' );
 

/**
 * This function returns an ID based on the provided chat author name.  It keeps these IDs in a global 
 * array and makes sure we have a unique set of IDs.  The purpose of this function is to provide an "ID"
 * that will be used in an HTML class for individual chat rows so they can be styled.  So, speaker "John" 
 * will always have the same class each time he speaks.  And, speaker "Mary" will have a different class 
 * from "John" but will have the same class each time she speaks.
 *
 * @author David Chandra
 * @link http://www.turtlepod.org
 * @author Justin Tadlock
 * @link http://justintadlock.com
 * @copyright Copyright (c) 2012
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * @link http://justintadlock.com/archives/2012/08/21/post-formats-chat
 *
 * @global array $_eureka_post_format_chat_ids An array of IDs for the chat rows based on the author.
 * @param string $chat_author Author of the current chat row.
 * @return int The ID for the chat row based on the author.
 */
function eureka_format_chat_row_id( $chat_author ) {
	global $_eureka_post_format_chat_ids;

	/* Let's sanitize the chat author to avoid craziness and differences like "John" and "john". */
	$chat_author = strtolower( strip_tags( $chat_author ) );

	/* Add the chat author to the array. */
	$_eureka_post_format_chat_ids[] = $chat_author;

	/* Make sure the array only holds unique values. */
	$_eureka_post_format_chat_ids = array_unique( $_eureka_post_format_chat_ids );

	/* Return the array key for the chat author and add "1" to avoid an ID of "0". */
	return absint( array_search( $chat_author, $_eureka_post_format_chat_ids ) ) + 1;
}

/* Auto-add paragraphs to the chat text. */
add_filter( 'eureka_post_format_chat_text', 'wpautop' );


/** The following are additional tweaks for documentation purposes **/

/**
 * Set the maximum width for displaying attachments.
 *
 * @since Eureka 1.0
 */
function eureka_change_attachment_size( $curr_size ){
	$new_size = 350;
	return $new_size;
}
#add_filter( 'eureka_attachment_size', 'eureka_change_attachment_size' );

/**
 * Control whether a post format title will be shown in a post
 *
 * @since Eureka 1.0
 */
function eureka_add_post_format_title( $allowed_titles ){
	$allowed_titles[] = 'gallery';
	return $allowed_titles;
}
add_filter( 'eureka_included_formats', 'eureka_add_post_format_title' );

