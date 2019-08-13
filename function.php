<?php


//remove menu


add_action( 'admin_init', 'my_remove_menu_pages' );
function my_remove_menu_pages() {
 
global $user_ID;
 
if ( current_user_can( 'pelelang' ) ) {
remove_menu_page('tools.php'); // Tools
remove_menu_page('profile.php'); // Tools


}
}



// Translate the word Name on the Subscribe form module
function my_text_with_context_translations( $translated, $text, $context, $domain ) {
  if ( 'fl-automator' == $domain ) {
    if ( 'Leave a Comment' == $text && 'Respond form title.' == $context ) {
      $translated = 'Isi Form Untuk Ajukan Bid';
    }
  }
  return $translated;
}
add_filter( 'gettext_with_context', 'my_text_with_context_translations', 10, 4 );

// http://scribu.net/wordpress/prevent-blog-authors-from-editing-comments.html
function restrict_comment_editing( $caps, $cap, $user_id, $args ) {
	if ( 'edit_comment' == $cap ) {
		$comment = get_comment( $args[0] );
  
		if ( $comment->user_id != $user_id )
			$caps[] = 'moderate_comments';
	}
  
	return $caps;
}
add_filter( 'map_meta_cap', 'restrict_comment_editing', 10, 4 );



/* Move Featured Image Below Title */
function move_featured_image_box() {
    remove_meta_box( 'postimagediv', 'post', 'side' );
    add_meta_box('postimagediv', __('Featured Image'), 'post_thumbnail_meta_box', 'post', 'normal', 'high');
 
}
add_action('do_meta_boxes', 'move_featured_image_box');


/*move publish to bottom*/

function so_screen_layout_columns( $columns ) {
    $columns['post'] = 1;
    return $columns;
}
add_filter( 'screen_layout_columns', 'so_screen_layout_columns' );

function so_screen_layout_post() {
    return 1;
}
add_filter( 'get_user_option_screen_layout_post_lelang_koi', 'so_screen_layout_post' );


/* filter id */

add_filter('relevanssi_content_to_index', 'rlv_index_post_id', 10, 2);
function rlv_index_post_id($content, $post) {
    $content .= " " . $post->ID;
    return $content;
}

/* make comment to modified post */
// post sort by new comment

add_action('wp_insert_comment','update_post_time',99,2);
function update_post_time($comment_id, $comment_object) {
    // Get the post's ID
    $post_id = $comment_object->comment_post_ID;
    // Double check for post's ID, since this value is mandatory in wp_update_post()
    if ($post_id) {
        // Get the current time
        $time = current_time('mysql');
        // Form an array of data to be updated
        $post_data = array(
            'ID'           => $post_id, 
            'post_modified'   => $time, 
            'post_modified_gmt' =>  get_gmt_from_date( $time )
        );
        // Update the post
        wp_update_post( $post_data );
    }
}


/* UM change post to CPT */
function custom_um_profile_query_make_posts( $args = array() ) {

    // Change the post type to our liking.

    $args['post_type'] = 'post';
    return $args;

}

add_filter( 'um_profile_query_make_posts', 'custom_um_profile_query_make_posts', 12, 1 );

/* ADD CURRENCY GRAVITY FORM */

add_filter( 'gform_currencies', 'add_inr_currency' );
function add_inr_currency( $currencies ) {
    $currencies['INR'] = array(
        'name'               => __( 'Indonesian Rupiah', 'gravityforms' ),
        'symbol_left'        => 'Rp',
        'symbol_right'       => '',
        'symbol_padding'     => ' ',
        'thousand_separator' => ',',
        'decimal_separator'  => '.',
        'decimals'           => 2
    );

    return $currencies;
}


