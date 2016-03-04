<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

function adsbenedict_add_ad_url() {
	add_meta_box(
		'adsbenedict_ad_url',
		'URL',
		'adsbenedict_ad_url_callback',
		'adsbenedict'
	);
}
add_action( 'add_meta_boxes' , 'adsbenedict_add_ad_url' );

function adsbenedict_ad_url_callback( $post ) {
	wp_nonce_field( 'adsbenedict_save_ad_url', 'adsbenedict_save_ad_url_nonce' );
	$url = get_post_meta( $post->ID, 'adsbenedict_url', true );
	
	echo '<label for="adsbenedict_url">URL: </label>';
	echo '<input type="text" id="adsbenedict_url" name="adsbenedict_url" value="' . esc_attr( $url ) . '" size"25" />';
}

function adsbenedict_save_ad_url( $post_id ) {
	if ( ! isset( $_POST['adsbenedict_save_ad_url_nonce'] ) ) {
			return;
		}
		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $_POST['adsbenedict_save_ad_url_nonce'], 'adsbenedict_save_ad_url' ) ) {
			return;
		}
		// If this is an autosave, our form has not been submitted, so we don't want to do anything.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		// Check the user's permissions.
		if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {
			if ( ! current_user_can( 'edit_page', $post_id ) ) {
				return;
			}
		} else {
			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return;
			}
		}
		/* OK, it's safe for us to save the data now. */
	
		// Make sure that it is set.
		if ( ! isset( $_POST['adsbenedict_url'] ) ) {
			return;
		}
		// Sanitize user input.
		$my_data = sanitize_text_field( $_POST['adsbenedict_url'] );
		
		// Update the meta field in the database.
		update_post_meta( $post_id, 'adsbenedict_url', $my_data );
}
add_action( 'save_post' , 'adsbenedict_save_ad_url');

function adsbenedict_init() {
	register_post_type( 'adsbenedict', array(
		'labels'            => array(
			'name'                => __( 'Ads Benedict', 'adsbenedict' ),
			'singular_name'       => __( 'Ad', 'adsbenedict' ),
			'all_items'           => __( 'Ads', 'adsbenedict' ),
			'new_item'            => __( 'New ad', 'adsbenedict' ),
			'add_new'             => __( 'Add New', 'adsbenedict' ),
			'add_new_item'        => __( 'Add New ad', 'adsbenedict' ),
			'edit_item'           => __( 'Edit ad', 'adsbenedict' ),
			'view_item'           => __( 'View ad', 'adsbenedict' ),
			'search_items'        => __( 'Search ads', 'adsbenedict' ),
			'not_found'           => __( 'No ads found', 'adsbenedict' ),
			'not_found_in_trash'  => __( 'No ads found in trash', 'adsbenedict' ),
			'parent_item_colon'   => __( 'Parent adsbenedict', 'adsbenedict' ),
			'menu_name'           => __( 'Ads Benedict', 'adsbenedict' ),
		),
		'public'            => false,
		'hierarchical'      => false,
		'show_ui'           => true,
		'show_in_nav_menus' => true,
		'supports'          => array( 'thumbnail', 'link' , 'zone', 'advertisers'),
		'has_archive'       => false,
		'rewrite'           => true,
		'query_var'         => true,
		'menu_icon'         => 'dashicons-megaphone',
	) );

}
add_action( 'init', 'adsbenedict_init' );

function adsbenedict_updated_messages( $messages ) {
	global $post;

	$permalink = get_permalink( $post );

	$messages['adsbenedict'] = array(
		0 => '', // Unused. Messages start at index 1.
		1 => sprintf( __('Adsbenedict updated. <a target="_blank" href="%s">View adsbenedict</a>', 'adsbenedict'), esc_url( $permalink ) ),
		2 => __('Custom field updated.', 'adsbenedict'),
		3 => __('Custom field deleted.', 'adsbenedict'),
		4 => __('Adsbenedict updated.', 'adsbenedict'),
		/* translators: %s: date and time of the revision */
		5 => isset($_GET['revision']) ? sprintf( __('Adsbenedict restored to revision from %s', 'adsbenedict'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6 => sprintf( __('Adsbenedict published. <a href="%s">View adsbenedict</a>', 'adsbenedict'), esc_url( $permalink ) ),
		7 => __('Adsbenedict saved.', 'adsbenedict'),
		8 => sprintf( __('Adsbenedict submitted. <a target="_blank" href="%s">Preview adsbenedict</a>', 'adsbenedict'), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
		9 => sprintf( __('Adsbenedict scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview adsbenedict</a>', 'adsbenedict'),
		// translators: Publish box date format, see http://php.net/date
		date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( $permalink ) ),
		10 => sprintf( __('Adsbenedict draft updated. <a target="_blank" href="%s">Preview adsbenedict</a>', 'adsbenedict'), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
	);

	return $messages;
}
add_filter( 'post_updated_messages', 'adsbenedict_updated_messages' );
