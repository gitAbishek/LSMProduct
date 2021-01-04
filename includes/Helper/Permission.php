<?php
/**
 * Permission functions.
 *
 * @since 0.1.0
 */

namespace ThemeGrill\Masteriyo\Helper;

defined( 'ABSPATH' ) || exit;
class Permission {
	/**
	 * Check permissions of posts on REST API.
	 *
	 * @since 0.1.0
	 * @param string $post_type Post type.
	 * @param string $context   Request context.
	 * @param int    $object_id Post ID.
	 * @return bool
	 */
	public function rest_check_post_permissions( $post_type, $context = 'read', $object_id = 0 ) {
		$contexts = array(
			'read'   => 'read',
			'create' => 'publish_posts',
			'update' => 'edit_post',
			'delete' => 'delete_post',
			'batch'  => 'edit_others_posts',
		);

		if ( 'revision' === $post_type ) {
			$permission = false;
		} else {
			$cap              = $contexts[ $context ];
			$post_type_object = get_post_type_object( $post_type );
			$permission       = current_user_can( $post_type_object->cap->$cap, $object_id );
		}

		return apply_filters( 'masteriyo_rest_check_permissions', $permission, $context, $object_id, $post_type );
	}

	/**
	 * Check permissions for terms on REST API.
	 *
	 * @since 0.1.0
	 * @param string $taxonomy  Taxonomy.
	 * @param string $context   Request context.
	 * @param int    $object_id Term ID.
	 * @return bool
	 */
	public function rest_check_term_permissions( $taxonomy, $context = 'read', $object_id = 0 ) {
		$contexts = array(
			'read'   => 'manage_terms',
			'create' => 'edit_terms',
			'edit'   => 'edit_terms',
			'delete' => 'delete_terms',
			'batch'  => 'edit_terms',
		);

		if ( 'revision' === $taxonomy ) {
			$permission = false;
		} else {
			$cap             = $contexts[ $context ];
			$taxonomy_object = get_taxonomy( $taxonomy );
			$permission      = current_user_can( $taxonomy_object->cap->$cap, $object_id );
		}

		return apply_filters( 'masteriyo_rest_check_permissions', $permission, $context, $object_id, $taxonomy );
	}

	/**
	 * Check permissions for manipulating users on REST API.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context Request context.
	 *
	 * @return bool
	 */
	public function rest_check_users_manipulation_permissions( $context = 'read' ) {
		$contexts   = array(
			'read'    => 'list_users',
			'create'  => 'create_users',
			'edit'    => 'edit_users',
			'delete'  => 'delete_users',
			'promote' => 'promote_users',
			'batch'   => 'edit_users',
		);
		$cap        = $contexts[ $context ];
		$permission = current_user_can( $cap );

		return apply_filters( 'masteriyo_rest_check_permissions', $permission, $context );
	}

	/**
	 * Check permissions for checking answers on REST API.
	 *
	 * @since 0.1.0
	 *
	 * @return bool
	 */
	public function rest_check_answer_check_permissions() {
		$permission = current_user_can( 'publish_posts' );

		return apply_filters( 'masteriyo_rest_check_permissions', $permission );
	}

	/**
	 * Check manager permissions on REST API.
	 *
	 * @since 0.1.0
	 * @param string $object  Object.
	 * @param string $context Request context.
	 * @return bool
	 */
	public function rest_check_manager_permissions( $object, $context = 'read' ) {
		$objects = array(
			'settings'         => 'manage_masteriyo',
			'system_status'    => 'manage_masteriyo',
			'payment_gateways' => 'manage_masteriyo',
		);

		$permission = current_user_can( $objects[ $object ] );

		return apply_filters( 'masteriyo_rest_check_permissions', $permission, $context, 0, $object );
	}

	/**
	 * Check course reviews permissions on REST API.
	 *
	 * @since 0.1.0
	 * @param string $context   Request context.
	 * @param string $object_id Object ID.
	 * @return bool
	 */
	public function rest_check_course_reviews_permissions( $context = 'read', $object_id = 0 ) {
		$permission = false;
		$contexts   = array(
			'read'   => 'moderate_comments',
			'create' => 'moderate_comments',
			'edit'   => 'moderate_comments',
			'delete' => 'moderate_comments',
			'batch'  => 'moderate_comments',
		);

		if ( isset( $contexts[ $context ] ) ) {
			$permission = current_user_can( $contexts[ $context ] );
		}

		return apply_filters( 'masteriyo_rest_check_permissions', $permission, $context, $object_id, 'course_review' );
	}
}
