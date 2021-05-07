<?php
/**
 * Course builder REST API.
 *
 * Handles requests to the courses/builder endpoint.
 *
 * @author   mi5t4n
 * @category API
 * @package Masteriyo\RestApi
 * @since    0.1.0
 */

namespace ThemeGrill\Masteriyo\RestApi\Controllers\Version1;

defined( 'ABSPATH' ) || exit;

use ThemeGrill\Masteriyo\Helper\Permission;
use ThemeGrill\Masteriyo\RestApi\Controllers\Version1\RestController;

/**
 * Course builder REST API. controller class.
 *
 * @package Masteriyo\RestApi
 * @extends CrudController
 */
class CourseBuilderController extends RestController {

	/**
	 * Endpoint namespace.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected $namespace = 'masteriyo/v1';

	/**
	 * Route base.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected $rest_base = 'courses';

	/**
	 * Object type.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected $object_type = 'course_builder';

	/**
	 * Post type.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected $post_type = 'course';

	/**
	 * Permission class.
	 *
	 * @since 0.1.0
	 *
	 * @var ThemeGrill\Masteriyo\Helper\Permission;
	 */
	protected $permission = null;

	/**
	 * Constructor.
	 *
	 * @since 0.1.0
	 *
	 * @param Permission $permission
	 */
	public function __construct( Permission $permission = null ) {
		$this->permission = $permission;
	}

	/**
	 * Register the routes for terms.
	 *
	 * @since 0.1.0
	 */
	public function register_routes() {

		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base . '/(?P<id>[\d]+)/builder',
			array(
				'args'   => array(
					'id' => array(
						'description' => __( 'Unique identifier for the resource.', 'masteriyo' ),
						'type'        => 'integer',
					),
				),
				array(
					'methods'             => \WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_item' ),
					'permission_callback' => array( $this, 'get_item_permissions_check' ),
					'args'                => array(
						'context' => $this->get_context_param(
							array(
								'default' => 'view',
							)
						),
					),
				),
				array(
					'methods'             => \WP_REST_Server::EDITABLE,
					'callback'            => array( $this, 'update_item' ),
					'permission_callback' => array( $this, 'update_item_permissions_check' ),
					'args'                => $this->get_endpoint_args_for_item_schema( \WP_REST_Server::EDITABLE ),
				),
				'schema' => array( $this, 'get_public_item_schema' ),
			)
		);
	}

	/**
	 * Check if a given request has access to read the terms.
	 *
	 * @since 0.1.0
	 *
	 * @param  WP_REST_Request $request Full details about the request.
	 * @return WP_Error|boolean
	 */
	public function get_item_permissions_check( $request ) {
		if ( is_null( $this->permission ) ) {
			return new \WP_Error(
				'masteriyo_null_permission',
				__( 'Sorry, the permission object for this resource is null.', 'masteriyo' )
			);
		}

		if ( ! $this->permission->rest_check_post_permissions( 'course', 'read' ) ) {
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
	 * Get the course builder schema, conforming to JSON Schema.
	 *
	 * @since 0.1.0
	 *
	 * @return array
	 */
	public function get_item_schema() {
		$schema = array(
			'$schema'    => 'http://json-schema.org/draft-04/schema#',
			'title'      => $this->object_type,
			'type'       => 'object',
			'properties' => array(
				'contents'      => array(
					'description' => __( 'Course contents(quiz, lesson)', 'masteriyo' ),
					'type'        => 'array',
					'required'    => false,
					'context'     => array( 'view', 'edit' ),
					'items'       => array(
						'type'       => 'object',
						'properties' => array(
							'id'         => array(
								'description' => __( 'Course content ID.', 'masteriyo' ),
								'type'        => 'integer',
								'context'     => array( 'view', 'edit' ),
								'readonly'    => true,
							),
							'name'       => array(
								'description' => __( 'Course content name.', 'masteriyo' ),
								'type'        => 'string',
								'context'     => array( 'view', 'edit' ),
							),
							'permalink'  => array(
								'description' => __( 'Course content permalink.', 'masteriyo' ),
								'type'        => 'string',
								'context'     => array( 'view', 'edit' ),
							),
							'type'       => array(
								'description' => __( 'Course content type.', 'masteriyo' ),
								'type'        => 'string',
								'context'     => array( 'view', 'edit' ),
							),
							'menu_order' => array(
								'description' => __( 'Course content menu order.', 'masteriyo' ),
								'type'        => 'integer',
								'context'     => array( 'view', 'edit' ),
							),
							'parent_id'  => array(
								'description' => __( 'Course content parent id.', 'masteriyo' ),
								'type'        => 'integer',
								'context'     => array( 'view', 'edit' ),
							),
						),
					),
				),
				'sections'      => array(
					'description' => __( 'Course sections.', 'masteriyo' ),
					'type'        => 'array',
					'required'    => true,
					'context'     => array( 'view', 'edit' ),
					'items'       => array(
						'type'       => 'object',
						'properties' => array(
							'id'         => array(
								'description' => __( 'Course section ID.', 'masteriyo' ),
								'type'        => 'integer',
								'context'     => array( 'view', 'edit' ),
								'readonly'    => true,
							),
							'name'       => array(
								'description' => __( 'Course section name.', 'masteriyo' ),
								'type'        => 'string',
								'context'     => array( 'view', 'edit' ),
							),
							'permalink'  => array(
								'description' => __( 'Course section permalink.', 'masteriyo' ),
								'type'        => 'string',
								'context'     => array( 'view', 'edit' ),
							),
							'type'       => array(
								'description' => __( 'Course section type.', 'masteriyo' ),
								'type'        => 'string',
								'context'     => array( 'view', 'edit' ),
							),
							'menu_order' => array(
								'description' => __( 'Course section menu order.', 'masteriyo' ),
								'type'        => 'integer',
								'context'     => array( 'view', 'edit' ),
							),
							'parent_id'  => array(
								'description' => __( 'Course section parent id.', 'masteriyo' ),
								'type'        => 'integer',
								'context'     => array( 'view', 'edit' ),
							),
						),
					),
				),
				'section_order' => array(
					'description' => __( 'Course section order.', 'masteriyo' ),
					'type'        => 'array',
					'required'    => true,
					'context'     => array( 'view', 'edit' ),
					'items'       => array(
						'type' => 'integer',
					),
				),
			),
		);

		return $schema;
	}

	/**
	 * Get the course contents.
	 *
	 * @since  0.1.0
	 * @param  WP_REST_Request $request Full details about the request.
	 * @return array
	 */
	public function get_item( $request ) {
		$course = get_post( absint( $request['id'] ) );

		if ( is_null( $course ) || $this->post_type !== $course->post_type ) {
			return new \WP_Error(
				"masteriyo_rest_{$this->post_type}_invalid_id",
				__( 'Invalid ID.', 'masteriyo' ),
				array( 'status' => 400 )
			);
		}

		$objects = $this->get_course_contents( $request );

		foreach ( $objects as $object ) {
			if ( ! $this->check_item_permission( $object->get_object_type(), 'read', $object->get_id() ) ) {
				continue;
			}

			$data       = $this->prepare_object_for_response( $object, $request );
			$response[] = $this->prepare_response_for_collection( $data );
		}

		return $this->process_objects_collection( $response );
	}

	/**
	 * Get course contents(sections, lessons, quizes).
	 *
	 * @since 0.1.0
	 *
	 * @param  WP_REST_Request $request Full details about the request.
	 * @return Model[]
	 */
	protected function get_course_contents( $request ) {
		$sections = $this->get_objects(
			array(
				'post_parent' => $request['course'],
				'post_type'   => 'section',
			),
			$request
		);

		$objects = array_merge( array(), $sections );

		foreach ( $sections as $section ) {
			$contents = $this->get_objects(
				array(
					'post_parent' => $section->get_id(),
					'post_type'   => 'any',
				),
				$request
			);

			if ( 0 < count( $contents ) ) {
				$objects = array_merge( $objects, $contents );
			}
		}

		return apply_filters( "masteriyo_{$this->object_type}_objects", $objects );
	}

	/**
	 * Get objects(sections, quizes, lessons, etc.).
	 *
	 * @since 0.1.0
	 * @param string[] $query_args WP_Query args.
	 * @param  WP_REST_Request $request Full details about the request.
	 *
	 * @return Model[]
	 */
	protected function get_objects( $query_args, $request ) {
		$query_args['posts_per_page'] = -1;
		$query                        = new \WP_Query( $query_args );

		return array_map( array( $this, 'get_object' ), $query->posts );
	}

	/**
	 * Get object.
	 *
	 * @since 0.1.0
	 *
	 * @param  WP_Post $post Post object.
	 * @return object Model object or WP_Error object.
	 */
	protected function get_object( $post ) {
		try {
			$item = masteriyo( $post->post_type );
			$item->set_id( $post->ID );
			$item_repo = masteriyo( "{$post->post_type}.store" );
			$item_repo->read( $item );
		} catch ( \Exception $e ) {
			return false;
		}

		return $item;
	}

	/**
	 * Prepares the object for the REST response.
	 *
	 * @since  0.1.0
	 * @param  Model         $object  Model object.
	 * @param  WP_REST_Request $request Request object.
	 * @return WP_Error|WP_REST_Response Response object on success, or WP_Error object on failure.
	 */
	protected function prepare_object_for_response( $object, $request ) {
		$context = ! empty( $request['context'] ) ? $request['context'] : 'view';
		$data    = $this->get_course_child_data( $object, $context );

		$data     = $this->add_additional_fields_to_object( $data, $request );
		$data     = $this->filter_response_by_context( $data, $context );
		$response = rest_ensure_response( $data );
		// $response->add_links( $this->prepare_links( $object, $request ) );

		/**
		 * Filter the data for a response.
		 *
		 * The dynamic portion of the hook name, $this->object_type,
		 * refers to object type being prepared for the response.
		 *
		 * @param WP_REST_Response $response The response object.
		 * @param Model          $object   Object data.
		 * @param WP_REST_Request  $request  Request object.
		 */
		return apply_filters( "masteriyo_rest_prepare_{$this->object_type}_object", $response, $object, $request );
	}


	/**
	 * Get course child data.
	 *
	 * @param Model $course_item Course instance.
	 * @param string     $context Request context.
	 *                            Options: 'view' and 'edit'.
	 *
	 * @return array
	 */
	protected function get_course_child_data( $course_item, $context = 'view' ) {
		$data = array(
			'id'         => $course_item->get_id(),
			'name'       => $course_item->get_name( $context ),
			'permalink'  => $course_item->get_permalink(),
			'type'       => $course_item->get_object_type(),
			'menu_order' => $course_item->get_menu_order(),
			'parent_id'  => $course_item->get_parent_id(),
		);

		return $data;
	}

	/**
	 * Format the course items according to the builder format.
	 *
	 * @since 0.1.0
	 *
	 * @param Model[] $objects Course contents(sections, quizes, lessons)
	 * @return Model[]
	 */
	protected function process_objects_collection( $objects ) {
		$contents = $this->filter_section_contents( $objects );

		$sections        = $this->filter_sections( $objects );
		$section_ids     = wp_list_pluck( $sections, 'id' );
		$section_ids_map = array_flip( $section_ids );

		foreach ( $contents as $content ) {
			if ( in_array( $content['parent_id'], $section_ids, true ) ) {
				$index                            = $section_ids_map[ $content['parent_id'] ];
				$sections[ $index ]['contents'][] = $content['id'];
			}
		}

		$results['contents']      = $contents;
		$results['sections']      = $sections;
		$results['section_order'] = $section_ids;

		return $results;
	}

	/**
	 * Filters sections from objects and order be menu order in ascending order.
	 *
	 * @since 0.1.0
	 *
	 * @param array $objects
	 * @return void
	 */
	protected function filter_sections( $objects ) {
		$sections = array_values(
			array_filter(
				$objects,
				function( $object ) {
					return isset( $object['type'] ) && 'section' === $object['type'];
				}
			)
		);

		usort(
			$sections,
			function( $a, $b ) {
				$c = 1;
				return $a['menu_order'] > $b['menu_order'];
			}
		);

		return $sections;
	}

	/**
	 * Filters sections items (quiz, lesson) from objects and order be menu order in ascending order.
	 *
	 * @since 0.1.0
	 *
	 * @param array $objects
	 * @return void
	 */
	protected function filter_section_contents( $objects ) {
		$items = array_values(
			array_filter(
				$objects,
				function( $object ) {
					return isset( $object['type'] ) && 'section' !== $object['type'];
				}
			)
		);

		usort(
			$items,
			function( $a, $b ) {
				$c = 1;
				return $a['menu_order'] > $b['menu_order'];
			}
		);

		return $items;
	}

	/**
	 * Check permissions for an item.
	 *
	 * @since 0.1.0
	 * @param string $object_type Object type.
	 * @param string $context   Request context.
	 * @param int    $object_id Post ID.
	 * @return bool
	 */
	protected function check_item_permission( $object_type, $context = 'read', $object_id = 0 ) {
		return $this->permission->rest_check_post_permissions( $object_type, 'read', $object_id );
	}

	/**
	 * Check if a given request has access to create an item.
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

		if ( ! $this->permission->rest_check_post_permissions( 'course', 'update', $request['id'] ) ) {
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
	 * Save an object data.
	 *
	 * @since  0.1.0
	 * @param  WP_REST_Request $request  Full details about the request.
	 * @return Model|WP_Error
	 */
	public function update_item( $request ) {
		$course = get_post( $request['id'] );

		if ( is_null( $course ) || $this->post_type !== $course->post_type ) {
			return new \WP_Error(
				"masteriyo_rest_{$this->post_type}_invalid_id",
				__( 'Invalid ID.', 'masteriyo' ),
				array( 'status' => 400 )
			);
		}

		// Save section order.
		$this->save_section_order( $request );

		// Save section contents order(quizes, lessons)
		$this->save_section_contents( $request );

		return $this->get_item(
			array(
				'id' => $course->ID,
			)
		);
	}

	/**
	 * Save section order.
	 *
	 * @since 0.1.0
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 */
	protected function save_section_order( $request ) {
		$sections = array_unique( (array) $request['section_order'] );

		foreach ( $sections as $menu_order => $section ) {
			$this->update_post( $section, $menu_order, $request['id'] );
		}
	}

	/**
	 * Save section contents menu order and parent id(section).
	 *
	 * @since 0.1.0
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 */
	protected function save_section_contents( $request ) {
		$sections = (array) $request['sections'];

		foreach ( $sections as $section ) {
			$contents = array_unique( (array) $section['contents'] );

			if ( empty( $contents ) ) {
				continue;
			}

			foreach ( $contents as $menu_order => $content ) {
				$this->update_post( $content, $menu_order, $section['id'] );
			}
		}
	}

	/**
	 * Update post if the parent id or menu order is changed.
	 *
	 * @since 0.1.0
	 *
	 * @param int $id Post ID.
	 */
	private function update_post( $id, $menu_order, $parent_id ) {
		$post = get_post( $id );

		if ( is_null( $post ) ) {
			return;
		}

		if ( $post->menu_order !== $menu_order || $post->post_parent !== $parent_id ) {
			wp_update_post(
				array(
					'ID'          => $post->ID,
					'menu_order'  => $menu_order,
					'post_parent' => $parent_id,
				)
			);
		}
	}

}