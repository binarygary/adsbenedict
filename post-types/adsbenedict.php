<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

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
		'public'            => true,
		'hierarchical'      => false,
		'show_ui'           => true,
		'show_in_nav_menus' => true,
		'supports'          => array( 'thumbnail', 'link' ),
		'has_archive'       => true,
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
