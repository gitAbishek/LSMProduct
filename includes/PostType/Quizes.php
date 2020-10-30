<?php
/**
 * Quizes post type.
 *
 * @since 0.1.0
 *
 * @package ThemeGrill\PostType;
 */

namespace ThemeGrill\Masteriyo\PostType;

/**
 * Quizes post type.
 *
 * @since 0.1.0
 */
class Quizes extends PostType {
	/**
	 * Post slug.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected $slug = 'quiz';

	public function __construct() {
		$this->labels = array(
			'name'                  => _x( 'Quizes', 'Quize General Name', 'masteriyo' ),
			'singular_name'         => _x( 'Quiz', 'Quize Singular Name', 'masteriyo' ),
			'menu_name'             => __( 'Quizes', 'masteriyo' ),
			'name_admin_bar'        => __( 'Quiz', 'masteriyo' ),
			'archives'              => __( 'Quize Archives', 'masteriyo' ),
			'attributes'            => __( 'Quize Attributes', 'masteriyo' ),
			'parent_item_colon'     => __( 'Parent Quize:', 'masteriyo' ),
			'all_items'             => __( 'All Quizes', 'masteriyo' ),
			'add_new_item'          => __( 'Add New Item', 'masteriyo' ),
			'add_new'               => __( 'Add New', 'masteriyo' ),
			'new_item'              => __( 'New Quiz', 'masteriyo' ),
			'edit_item'             => __( 'Edit Quiz', 'masteriyo' ),
			'update_item'           => __( 'Update Quiz', 'masteriyo' ),
			'view_item'             => __( 'View Quiz', 'masteriyo' ),
			'view_items'            => __( 'View Quizes', 'masteriyo' ),
			'search_items'          => __( 'Search Quiz', 'masteriyo' ),
			'not_found'             => __( 'Not found', 'masteriyo' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'masteriyo' ),
			'featured_image'        => __( 'Featured Image', 'masteriyo' ),
			'set_featured_image'    => __( 'Set featured image', 'masteriyo' ),
			'remove_featured_image' => __( 'Remove featured image', 'masteriyo' ),
			'use_featured_image'    => __( 'Use as featured image', 'masteriyo' ),
			'insert_into_item'      => __( 'Insert into quiz', 'masteriyo' ),
			'uploaded_to_this_item' => __( 'Uploaded to this quiz', 'masteriyo' ),
			'items_list'            => __( 'Quizes list', 'masteriyo' ),
			'items_list_navigation' => __( 'Quizes list navigation', 'masteriyo' ),
			'filter_items_list'     => __( 'Filter quizes list', 'masteriyo' ),
		);

		$this->args = array(
			'label'                 => __ ( 'Quizes', 'masteriyo' ),
			'description'           => __( 'Quizes Description', 'masteriyo' ),
			'labels'                => $this->labels,
			'supports'              => false,
			'taxonomies'            => array( ),
			'hierarchical'          => false,
			'public'                => true,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'menu_position'         => 5,
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => true,
			'can_export'            => true,
			'show_in_rest'          => true,
			'has_archive'           => true,
			'map_meta_cap'          => true,
			'exclude_from_search'   => false,
			'publicly_queryable'    => true,
			'capability_type'       => 'post',
			'can_export'            => true,
			'delete_with_user'      => null,
			'rest_controller_class' => 'ThemeGrill\\Masteriyo\\RestApi\\Controllers\\Version1\\QuizesController',
		);
	}


}
