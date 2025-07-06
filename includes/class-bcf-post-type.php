<?php
/**
 * Class BCF_Post_Type
 *
 * This class registers a custom post type for storing contact form submissions.
 */
defined( 'ABSPATH' ) || exit;
class BCF_Post_Type {
	public function __construct() {
		add_action( 'init', [ $this, 'register_post_type' ] );
	}

	public function register_post_type() {
		$args = [ 
			'label' => 'Contact Submissions',
			'labels' => [ 
				'name' => 'Contact Submissions',
				'singular_name' => 'Contact Submission',
				'add_new' => 'Add New',
				'add_new_item' => 'Add New Submission',
				'edit_item' => 'Edit Submission',
				'new_item' => 'New Submission',
				'view_item' => 'View Submission',
				'search_items' => 'Search Submissions',
				'not_found' => 'No submissions found',
				'not_found_in_trash' => 'No submissions found in trash'
			],
			'public' => false,
			'show_ui' => true,
			'show_in_menu' => true,
			'capability_type' => 'post',
			'capabilities' => [ 
				'create_posts' => false,
			],
			'map_meta_cap' => true,
			'supports' => [ 'title', 'editor' ],
			'menu_icon' => 'dashicons-email-alt'
		];
		register_post_type( 'bcf_submission', $args );
	}
}