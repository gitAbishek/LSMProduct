<?php
/**
 * Permission functions.
 *
 * @since 1.0.0
 */

namespace Masteriyo\Helper;

defined( 'ABSPATH' ) || exit;

class Permission {
	/**
	 * Check permissions of posts on REST API.
	 *
	 * @since 1.0.0
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

		/**
		 * Filters permission for a post.
		 *
		 * @since 1.0.0
		 *
		 * @param boolean $permission True if permission granted.
		 * @param string $context Permission context.
		 * @param integer $object_id Object ID which requires permission, if available.
		 * @param string $post_type Object's post type.
		 */
		return apply_filters( 'masteriyo_rest_check_permissions', $permission, $context, $object_id, $post_type );
	}

	/**
	 * Check permissions for terms on REST API.
	 *
	 * @since 1.0.0
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
			if ( 'edit' === $context && masteriyo_is_current_user_instructor() && ! masteriyo_is_current_user_manager() && ! masteriyo_is_current_user_admin() ) {
				$permission = false;
			} else {
				$cap             = $contexts[ $context ];
				$taxonomy_object = get_taxonomy( $taxonomy );
				$permission      = current_user_can( $taxonomy_object->cap->$cap, $object_id );
			}
		}

		/**
		 * Filters permission for a term.
		 *
		 * @since 1.0.0
		 *
		 * @param boolean $permission True if permission granted.
		 * @param string $context Permission context.
		 * @param integer $object_id Object ID which requires permission, if available.
		 * @param string $taxonomy Object's taxonomy.
		 */
		return apply_filters( 'masteriyo_rest_check_permissions', $permission, $context, $object_id, $taxonomy );
	}

	/**
	 * Check permissions for manipulating users on REST API.
	 *
	 * @since 1.0.0
	 *
	 * @param string $context Request context.
	 * @param int    $user_id User ID.

	 * @return bool
	 */
	public function rest_check_users_manipulation_permissions( $context = 'read', $user_id = 0 ) {
		$contexts   = array(
			'read'    => 'read_users',
			'create'  => 'create_users',
			'edit'    => 'edit_users',
			'delete'  => 'delete_users',
			'promote' => 'promote_users',
			'batch'   => 'edit_users',
		);
		$cap        = $contexts[ $context ];
		$permission = current_user_can( $cap );

		if ( false === $permission && $user_id ) {
			$permission = get_current_user_id() === $user_id;
		}

		/**
		 * Filters permission for a user data manipulation.
		 *
		 * @since 1.0.0
		 *
		 * @param boolean $permission True if permission granted.
		 * @param string $context Permission context.
		 */
		$permission = apply_filters( 'masteriyo_rest_check_permissions', $permission, $context );

		/**
		 * Users check permission
		 *
		 * @since 1.3.6
		 *
		 * @param boolean $permission True if permission granted.
		 * @param string $context Permission context.
		 * @param integer $user_id User ID which requires permission to be manipulated, if available.
		 */
		return apply_filters( 'masteriyo_rest_check_permissions', $permission, $context, $user_id, 'users' );
	}

	/**
	 * Check permissions for checking answers on REST API.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public function rest_check_answer_check_permissions() {
		$permission = current_user_can( 'publish_posts' );

		/**
		 * Filters permission for quiz answer.
		 *
		 * @since 1.0.0
		 *
		 * @param boolean $permission True if permission granted.
		 */
		return apply_filters( 'masteriyo_rest_check_permissions', $permission );
	}

	/**
	 * Check manager permissions on REST API.
	 *
	 * @since 1.0.0
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

		/**
		 * Filters permission for data object management.
		 *
		 * @since 1.0.0
		 *
		 * @param boolean $permission True if permission granted.
		 * @param string $context Permission context.
		 * @param integer $object_id Object ID which requires permission, if available.
		 * @param string $object Data object type being managed.
		 */
		return apply_filters( 'masteriyo_rest_check_permissions', $permission, $context, 0, $object );
	}

	/**
	 * Check course reviews permissions on REST API.
	 *
	 * @since 1.0.0
	 * @param string $context   Request context.
	 * @param string $object_id Object ID.
	 * @return bool
	 */
	public function rest_check_course_reviews_permissions( $context = 'read', $object_id = 0 ) {
		$permission = false;
		$contexts   = array(
			'read'   => 'read_course_reviews',
			'create' => 'publish_course_reviews',
			'edit'   => 'edit_course_reviews',
			'delete' => 'delete_course_reviews',
			'batch'  => 'moderate_comments',
		);

		if ( isset( $contexts[ $context ] ) ) {
			$permission = current_user_can( $contexts[ $context ] );
		}

		/**
		 * Filters permission for a course review.
		 *
		 * @since 1.0.0
		 *
		 * @param boolean $permission True if permission granted.
		 * @param string $context Permission context.
		 * @param integer $object_id Object ID which requires permission, if available.
		 * @param string $object_type Object's type.
		 */
		return apply_filters( 'masteriyo_rest_check_permissions', $permission, $context, $object_id, 'course_review' );
	}

	/**
	 * Check course question-answer permissions on REST API.
	 *
	 * @since 1.0.0
	 * @param string $context   Request context.
	 * @param string $object_id Object ID.
	 * @return bool
	 */
	public function rest_check_comment_permissions( $context = 'read', $object_id = 0 ) {
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

		/**
		 * Filters permission for a comment.
		 *
		 * @since 1.0.0
		 *
		 * @param boolean $permission True if permission granted.
		 * @param string $context Permission context.
		 * @param integer $object_id Object ID which requires permission, if available.
		 * @param string $object_type Object's type.
		 */
		return apply_filters( 'masteriyo_rest_check_permissions', $permission, $context, $object_id, 'course_review' );
	}

	/**
	 * Check course question-answer permissions on REST API.
	 *
	 * @since 1.0.0
	 * @param string $context   Request context.
	 * @param string $object_id Object ID.
	 * @return bool
	 */
	public function rest_check_course_qas_permissions( $context = 'read', $object_id = 0 ) {
		$permission = false;
		$contexts   = array(
			'read'   => 'read_course_qa',
			'create' => 'create_course_qa',
			'edit'   => 'edit_course_qa',
			'delete' => 'delete_course_qa',
			'batch'  => 'edit_others_course_qas',
		);

		if ( isset( $contexts[ $context ] ) ) {
			$permission = current_user_can( $contexts[ $context ], $object_id );
		}

		/**
		 * Filters permission for a course question-answer.
		 *
		 * @since 1.0.0
		 *
		 * @param boolean $permission True if permission granted.
		 * @param string $context Permission context.
		 * @param integer $object_id Object ID which requires permission, if available.
		 * @param string $object_type Object's type.
		 */
		return apply_filters( 'masteriyo_rest_check_permissions', $permission, $context, $object_id, 'course_qa' );
	}

	/**
	 * Check order permissions.
	 *
	 * @since 1.0.0
	 * @param string $context   Request context.
	 * @param string $object_id Object ID.
	 * @return bool
	 */
	public function rest_check_order_permissions( $context = 'read', $object_id = 0 ) {
		$object_id = absint( $object_id );
		$post_type = 'mto-order';
		$cap       = $context;
		$contexts  = array(
			'read'   => 'read',
			'create' => 'publish_posts',
			'update' => 'edit_post',
			'delete' => 'delete_post',
			'batch'  => 'edit_others_posts',
		);

		if ( isset( $contexts[ $context ] ) ) {
			$post_type_object = get_post_type_object( $post_type );
			$cap              = $contexts[ $context ];
			$cap              = $post_type_object->cap->$cap;
		}

		$permission = current_user_can( $cap, $object_id );

		/**
		 * Filters permission for an order.
		 *
		 * @since 1.0.0
		 *
		 * @param boolean $permission True if permission granted.
		 * @param string $context Permission context.
		 * @param integer $object_id Object ID which requires permission, if available.
		 * @param string $post_type Object's post type.
		 */
		return apply_filters( 'masteriyo_rest_check_permissions', $permission, $context, $object_id, $post_type );
	}

	/**
	 * Check permissions of course progress on REST API.
	 *
	 * @since 1.0.0
	 * @param string $context   Request context.
	 * @param int    $object_id Course progress ID.
	 * @return bool
	 */
	public function rest_check_course_progress_permissions( $context = 'read', $object_id = 0 ) {
		$contexts = array(
			'read'   => 'read',
			'create' => 'publish_course_progresses',
			'update' => 'edit_course_progress',
			'delete' => 'delete_course_progress',
			'batch'  => 'edit_others_course_progresses',
		);

		$cap        = $contexts[ $context ];
		$permission = current_user_can( $cap, $object_id );

		/**
		 * Filters permission for a course progress.
		 *
		 * @since 1.3.1
		 *
		 * @param boolean $permission True if permission granted.
		 * @param string $context Permission context.
		 * @param integer $object_id Object ID which requires permission, if available.
		 */
		return apply_filters( 'masteriyo_rest_check_course_progress_permissions', $permission, $context, $object_id );
	}

	/**
	 * Check permissions of user course on REST API.
	 *
	 * @since 1.3.1
	 * @param string $context   Request context.
	 * @param int    $object_id User course ID.
	 * @return bool
	 */
	public function rest_check_user_course_permissions( $context = 'read', $object_id = 0 ) {
		$contexts = array(
			'read'   => 'read_user_courses',
			'create' => 'publish_user_courses',
			'update' => 'edit_user_course',
			'delete' => 'delete_user_course',
			'batch'  => 'edit_others_user_courses',
		);

		$cap        = $contexts[ $context ];
		$permission = current_user_can( $cap, $object_id );

		/**
		 * Filters permission for a user course.
		 *
		 * @since 1.3.1
		 *
		 * @param boolean $permission True if permission granted.
		 * @param string $context Permission context.
		 * @param integer $object_id Object ID which requires permission, if available.
		 */
		return apply_filters( 'masteriyo_rest_check_user_course_permissions', $permission, $context, $object_id );
	}
}
