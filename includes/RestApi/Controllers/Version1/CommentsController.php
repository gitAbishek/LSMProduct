<?php
/**
 * Abstract Rest Posts Controller Class
 *
 * @class CommentsController
 * @package ThemeGrill/Masteriyo/RestApi
 */

namespace ThemeGrill\Masteriyo\RestApi\Controllers\Version1;

defined( 'ABSPATH' ) || exit;

/**
 * CommentsController
 *
 * @package ThemeGrill/Masteriyo/RestApi
 * @version  0.1.0
 */
abstract class CommentsController extends CrudController {

	/**
	 * Retrieves the query params for collections.
	 *
	 * @since 0.1.0
	 *
	 * @return array Comments collection parameters.
	 */
	public function get_collection_params() {
		$params = parent::get_collection_params();

		$params['context']['default'] = 'view';

		$params['search'] = array(
			'description' => __( 'Limit results to those matching a string.', 'masteriyo' ),
			'type'        => 'string',
		);

		$params['after'] = array(
			'description' => __( 'Limit response to comments published after a given ISO8601 compliant date.', 'masteriyo' ),
			'type'        => 'string',
			'format'      => 'date-time',
		);

		$params['user'] = array(
			'description' => __( 'Limit result set to comments assigned to specific user IDs. Requires authorization.', 'masteriyo' ),
			'type'        => 'array',
			'items'       => array(
				'type' => 'integer',
			),
		);

		$params['user_exclude'] = array(
			'description' => __( 'Ensure result set excludes comments assigned to specific user IDs. Requires authorization.', 'masteriyo' ),
			'type'        => 'array',
			'items'       => array(
				'type' => 'integer',
			),
		);

		$params['user_email'] = array(
			'default'     => null,
			'description' => __( 'Limit result set to that from a specific author email. Requires authorization.', 'masteriyo' ),
			'format'      => 'email',
			'type'        => 'string',
		);

		$params['before'] = array(
			'description' => __( 'Limit response to comments published before a given ISO8601 compliant date.', 'masteriyo' ),
			'type'        => 'string',
			'format'      => 'date-time',
		);

		$params['after'] = array(
			'description' => __( 'Limit response to comments published before a given ISO8601 compliant date.', 'masteriyo' ),
			'type'        => 'string',
			'format'      => 'date-time',
		);

		$params['exclude'] = array(
			'description' => __( 'Ensure result set excludes specific IDs.', 'masteriyo' ),
			'type'        => 'array',
			'items'       => array(
				'type' => 'integer',
			),
			'default'     => array(),
		);

		$params['include'] = array(
			'description' => __( 'Limit result set to specific IDs.', 'masteriyo' ),
			'type'        => 'array',
			'items'       => array(
				'type' => 'integer',
			),
			'default'     => array(),
		);

		$params['offset'] = array(
			'description' => __( 'Offset the result set by a specific number of items.', 'masteriyo' ),
			'type'        => 'integer',
		);

		$params['order'] = array(
			'description' => __( 'Order sort attribute ascending or descending.', 'masteriyo' ),
			'type'        => 'string',
			'default'     => 'desc',
			'enum'        => array(
				'asc',
				'desc',
			),
		);

		$params['orderby'] = array(
			'description' => __( 'Sort collection by object attribute.', 'masteriyo' ),
			'type'        => 'string',
			'default'     => 'date_gmt',
			'enum'        => array(
				'date',
				'date_gmt',
				'id',
				'include',
				'parent',
			),
		);

		$params['parent'] = array(
			'default'     => array(),
			'description' => __( 'Limit result set to comments of specific parent IDs.', 'masteriyo' ),
			'type'        => 'array',
			'items'       => array(
				'type' => 'integer',
			),
		);

		$params['parent_exclude'] = array(
			'default'     => array(),
			'description' => __( 'Ensure result set excludes specific parent IDs.', 'masteriyo' ),
			'type'        => 'array',
			'items'       => array(
				'type' => 'integer',
			),
		);

		$params['post'] = array(
			'default'     => array(),
			'description' => __( 'Limit result set to comments assigned to specific post IDs.', 'masteriyo' ),
			'type'        => 'array',
			'items'       => array(
				'type' => 'integer',
			),
		);

		$params['status'] = array(
			'default'           => 'approve',
			'description'       => __( 'Limit result set to comments assigned a specific status. Requires authorization.', 'masteriyo' ),
			'sanitize_callback' => 'sanitize_key',
			'type'              => 'string',
			'validate_callback' => 'rest_validate_request_arg',
		);

		$params['password'] = array(
			'description' => __( 'The password for the post if it is password protected.', 'masteriyo' ),
			'type'        => 'string',
		);

		/**
		 * Filters REST API collection parameters for the comments controller.
		 *
		 * This filter registers the collection parameter, but does not map the
		 * collection parameter to an internal WP_Comment_Query parameter. Use the
		 * `rest_comment_query` filter to set WP_Comment_Query parameters.
		 *
		 * @since 4.7.0
		 *
		 * @param array $params JSON Schema-formatted collection parameters.
		 */
		return apply_filters( 'masteriyo_rest_comment_collection_params', $params );
	}

	/**
	 * Checks if a given request has access to get a specific item.
	 *
	 * @since 0.1.0
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return boolean|WP_Error True if the request has read access for the item, WP_Error object otherwise.
	 */
	public function get_item_permissions_check( $request ) {
		if ( is_null( $this->permission ) ) {
			return new \WP_Error(
				'masteriyo_null_permission',
				__( 'Sorry, the permission object for this resource is null.', 'masteriyo' )
			);
		}

		$post = get_comment( (int) $request['id'] );

		if ( $post && ! $this->permission->rest_check_comment_permissions( 'read', $request['id'] ) ) {
			return new \WP_Error(
				'masteriyo_rest_cannot_read',
				__( 'Sorry, you are not allowed to read resources.', 'masteriyo' ),
				array(
					'status' => rest_authorization_required_code(),
				)
			);
		}

		return true;
	}

	/**
	 * Check if a given request has access to read items.
	 *
	 * @param  WP_REST_Request $request Full details about the request.
	 * @return WP_Error|boolean
	 */
	public function get_items_permissions_check( $request ) {
		if ( is_null( $this->permission ) ) {
			return new \WP_Error(
				'masteriyo_null_permission',
				__( 'Sorry, the permission object for this resource is null.', 'masteriyo' )
			);
		}

		if ( ! $this->permission->rest_check_comment_permissions( 'read' ) ) {
			return new \WP_Error(
				'masteriyo_rest_cannot_read',
				__( 'Sorry, you cannot list resources.', 'masteriyo' ),
				array(
					'status' => rest_authorization_required_code(),
				)
			);
		}

		return true;
	}

	/**
	 * Check if a given request has access to create an item.
	 *
	 * @since 0.1.0
	 *
	 * @param  WP_REST_Request $request Full details about the request.
	 * @return WP_Error|boolean
	 */
	public function create_item_permissions_check( $request ) {
		if ( is_null( $this->permission ) ) {
			return new \WP_Error(
				'masteriyo_null_permission',
				__( 'Sorry, the permission object for this resource is null.', 'masteriyo' )
			);
		}

		if ( ! $this->permission->rest_check_comment_permissions( 'create' ) ) {
			return new \WP_Error(
				'masteriyo_rest_cannot_create',
				__( 'Sorry, you are not allowed to create resources.', 'masteriyo' ),
				array(
					'status' => rest_authorization_required_code(),
				)
			);
		}

		return true;
	}

	/**
	 * Check if a given request has access to delete an item.
	 *
	 * @since 0.1.0
	 *
	 * @param  WP_REST_Request $request Full details about the request.
	 * @return WP_Error|boolean
	 */
	public function delete_item_permissions_check( $request ) {
		if ( is_null( $this->permission ) ) {
			return new \WP_Error(
				'masteriyo_null_permission',
				__( 'Sorry, the permission object for this resource is null.', 'masteriyo' )
			);
		}

		$post = get_comment( (int) $request['id'] );

		if ( $post && ! $this->permission->rest_check_comment_permissions( 'delete', $post->ID ) ) {
			return new \WP_Error(
				'masteriyo_rest_cannot_delete',
				__( 'Sorry, you are not allowed to delete resources.', 'masteriyo' ),
				array(
					'status' => rest_authorization_required_code(),
				)
			);
		}

		return true;
	}

	/**
	 * Check if a given request has access to update an item.
	 *
	 * @since 0.1.0
	 *
	 * @param  WP_REST_Request $request Full details about the request.
	 * @return WP_Error|boolean
	 */
	public function update_item_permissions_check( $request ) {
		if ( is_null( $this->permission ) ) {
			return new \WP_Error(
				'masteriyo_null_permission',
				__( 'Sorry, the permission object for this resource is null.', 'masteriyo' )
			);
		}

		$post = get_comment( (int) $request['id'] );

		if ( $post && ! $this->permission->rest_check_comment_permissions( 'edit', $post->ID ) ) {
			return new \WP_Error(
				'masteriyo_rest_cannot_update',
				__( 'Sorry, you are not allowed to update resources.', 'masteriyo' ),
				array(
					'status' => rest_authorization_required_code(),
				)
			);
		}

		return true;
	}

	/**
	 * Check permissions for an item.
	 *
	 * @since 0.1.0
	 *
	 * @param string $object_type Object type.
	 * @param string $context   Request context.
	 * @param int    $object_id Object ID.
	 *
	 * @return bool
	 */
	protected function check_item_permission( $object_type, $context = 'read', $object_id = 0 ) {
		return $this->permission->rest_check_comment_permissions( 'read', $object_id );
	}

	/**
	 * Prepare objects query.
	 *
	 * @since  0.1.0
	 *
	 * @param  WP_REST_Request $request Full details about the request.
	 *
	 * @return array
	 */
	protected function prepare_objects_query( $request ) {
		$args = array(
			'search'          => $request['search'],
			'offset'          => $request['offset'],
			'order'           => $request['order'],
			'orderby'         => $request['orderby'],
			'paged'           => $request['page'],
			'comment__in'     => $request['include'],
			'comment__not_in' => $request['exclude'],
			'number'          => $request['per_page'],
			'parent__in'      => $request['parent'],
			'parent__not_in'  => $request['parent_exclude'],
			'author__in'      => $request['user'],
			'author__not_in'  => $request['user_exclude'],
			'author_email'    => $request['user_email'],
			'post__in'        => $request['course_id'],
		);

		if ( 'date' === $args['orderby'] ) {
			$args['orderby'] = 'date ID';
		}

		$args['date_query'] = array();
		// Set before into date query. Date query must be specified as an array of an array.
		if ( isset( $request['before'] ) ) {
			$args['date_query'][0]['before'] = $request['before'];
		}

		// Set after into date query. Date query must be specified as an array of an array.
		if ( isset( $request['after'] ) ) {
			$args['date_query'][0]['after'] = $request['after'];
		}

		/**
		 * Filter the query arguments for a request.
		 *
		 * Enables adding extra arguments or setting defaults for a post
		 * collection request.
		 *
		 * @since 0.1.0
		 *
		 * @param array           $args    Key value array of query var to query value.
		 * @param WP_REST_Request $request The request used.
		 */
		$args = apply_filters( "masteriyo_rest_{$this->object_type}_object_query", $args, $request );

		return $this->prepare_items_query( $args, $request );
	}

	/**
	 * Determine the allowed query_vars for a get_items() response and
	 * prepare for WP_Comment_Query.
	 *
	 * @since 0.1.0
	 *
	 * @param array           $prepared_args Prepared arguments.
	 * @param WP_REST_Request $request Request object.
	 *
	 * @return array          $query_args
	 */
	protected function prepare_items_query( $prepared_args = array(), $request = null ) {
		$query_args = array();
		$valid_vars = $this->get_allowed_query_vars();

		foreach ( $valid_vars as $index => $var ) {
			if ( isset( $prepared_args[ $var ] ) ) {
				/**
				 * Filter the query_vars used in `get_items` for the constructed query.
				 *
				 * The dynamic portion of the hook name, $var, refers to the query_var key.
				 *
				 * @param mixed $prepared_args[ $var ] The query_var value.
				 */
				$query_args[ $var ] = apply_filters( "masteriyo_rest_query_var-{$var}", $prepared_args[ $var ] ); // phpcs:ignore WordPress.NamingConventions.ValidHookName.UseUnderscores
			}
		}

		if ( 'include' === $query_args['orderby'] ) {
			$query_args['orderby'] = 'comment__in';
		} elseif ( 'id' === $query_args['orderby'] ) {
			$query_args['orderby'] = 'ID'; // ID must be capitalized.
		}

		return $query_args;
	}

		/**
	 * Get all the WP Query vars that are allowed for the API request.
	 *
	 * @since 0.1.0
	 *
	 * @return array
	 */
	protected function get_allowed_query_vars() {
		global $wp;

		/**
		 * Filter the publicly allowed query vars.
		 *
		 * Allows adjusting of the default query vars that are made public.
		 *
		 * @param array  Array of allowed WP_Query query vars.
		 */
		$valid_vars = apply_filters( 'query_vars', $wp->public_query_vars );

		$post_type_obj = get_post_type_object( $this->post_type );
		if ( null !== $post_type_obj && current_user_can( $post_type_obj->cap->edit_posts ) ) {
			/**
			 * Filter the allowed 'private' query vars for authorized users.
			 *
			 * If the user has the `edit_posts` capability, we also allow use of
			 * private query parameters, which are only undesirable on the
			 * frontend, but are safe for use in query strings.
			 *
			 * To disable anyway, use
			 * `add_filter( 'masteriyo_rest_private_query_vars', '__return_empty_array' );`
			 *
			 * @param array $private_query_vars Array of allowed query vars for authorized users.
			 * }
			 */
			$private    = apply_filters( 'masteriyo_rest_private_query_vars', $wp->private_query_vars );
			$valid_vars = array_merge( $valid_vars, $private );
		}
		// Define our own in addition to WP's normal vars.
		$rest_valid = array(
			'search',
			'date_query',
			'offset',
			'post__in',
			'parent__in',
			'parent__not_in',
			'author__in',
			'author__not_in',
			'comment__in',
			'comment__not_in',
			'number',
			'meta_query',
			'meta_key',
			'meta_value',
			'meta_compare',
			'meta_value_num',
		);
		$valid_vars = array_merge( $valid_vars, $rest_valid );

		/**
		 * Filter allowed query vars for the REST API.
		 *
		 * This filter allows you to add or remove query vars from the final allowed
		 * list for all requests, including unauthenticated ones. To alter the
		 * vars for editors only.
		 *
		 * @param array {
		 *    Array of allowed WP_Query query vars.
		 *
		 *    @param string $allowed_query_var The query var to allow.
		 * }
		 */
		$valid_vars = apply_filters( 'masteriyo_rest_query_vars', $valid_vars );
		$valid_vars = array_values( array_unique( $valid_vars ) );

		return $valid_vars;
	}
}
