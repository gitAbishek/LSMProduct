<?php
/**
 * Quiz builder REST API.
 *
 * Handles requests to the quizzes/builder endpoint.
 *
 * @author   mi5t4n
 * @category API
 * @package Masteriyo\RestApi
 * @since   1.0.0
 */

namespace Masteriyo\RestApi\Controllers\Version1;

defined( 'ABSPATH' ) || exit;

use Masteriyo\Helper\Permission;
use Masteriyo\RestApi\Controllers\Version1\PostsController;

/**
 * Quiz builder REST API. controller class.
 *
 * @package Masteriyo\RestApi
 * @extends CrudController
 */
class QuizBuilderController extends PostsController {

	/**
	 * Endpoint namespace.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	protected $namespace = 'masteriyo/v1';

	/**
	 * Route base.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	protected $rest_base = 'quizbuilder';

	/**
	 * Object type.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	protected $object_type = 'quiz_builder';

	/**
	 * Post type.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	protected $post_type = 'mto-quiz';

	/**
	 * Permission class.
	 *
	 * @since 1.0.0
	 *
	 * @var Masteriyo\Helper\Permission;
	 */
	protected $permission = null;

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 *
	 * @param Permission $permission
	 */
	public function __construct( Permission $permission = null ) {
		$this->permission = $permission;
	}

	/**
	 * Register the routes for terms.
	 *
	 * @since 1.0.0
	 */
	public function register_routes() {

		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base . '/(?P<id>[\d]+)',
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
	 * @since 1.0.0
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

		if ( ! $this->permission->rest_check_post_permissions( 'mto-quiz', 'read' ) ) {
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
	 * Get the quiz builder schema, conforming to JSON Schema.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function get_item_schema() {
		$schema = array(
			'$schema'    => 'http://json-schema.org/draft-04/schema#',
			'title'      => $this->object_type,
			'type'       => 'object',
			'properties' => array(
				'contents' => array(
					'description' => __( 'Quiz contents (question)', 'masteriyo' ),
					'type'        => 'array',
					'required'    => false,
					'context'     => array( 'view', 'edit' ),
					'items'       => array(
						'type'       => 'object',
						'properties' => array(
							'id'         => array(
								'description' => __( 'Question ID', 'masteriyo' ),
								'type'        => 'integer',
								'context'     => array( 'view', 'edit' ),
								'readonly'    => true,
							),
							'name'       => array(
								'description' => __( 'Question name', 'masteriyo' ),
								'type'        => 'string',
								'context'     => array( 'view', 'edit' ),
							),
							'permalink'  => array(
								'description' => __( 'Question permalink', 'masteriyo' ),
								'type'        => 'string',
								'context'     => array( 'view', 'edit' ),
							),
							'type'       => array(
								'description' => __( 'Question type.', 'masteriyo' ),
								'type'        => 'string',
								'context'     => array( 'view', 'edit' ),
							),
							'menu_order' => array(
								'description' => __( 'Question menu order', 'masteriyo' ),
								'type'        => 'integer',
								'context'     => array( 'view', 'edit' ),
							),
							'parent_id'  => array(
								'description' => __( 'Quiz parent ID', 'masteriyo' ),
								'type'        => 'integer',
								'context'     => array( 'view', 'edit' ),
							),
						),
					),
				),
			),
		);

		return $schema;
	}

	/**
	 * Get the quiz contents.
	 *
	 * @since  1.0.0
	 * @param  WP_REST_Request $request Full details about the request.
	 * @return array
	 */
	public function get_item( $request ) {
		$response = array();
		$quiz     = get_post( absint( $request['id'] ) );

		if ( is_null( $quiz ) || $this->post_type !== $quiz->post_type ) {
			return new \WP_Error(
				"masteriyo_rest_{$this->post_type}_invalid_id",
				__( 'Invalid ID', 'masteriyo' ),
				array( 'status' => 400 )
			);
		}

		$objects = $this->get_quiz_contents( $request );

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
	 * Get quiz contents(Questions).
	 *
	 * @since 1.0.0
	 *
	 * @param  WP_REST_Request $request Full details about the request.
	 * @return Model[]
	 */
	protected function get_quiz_contents( $request ) {
		$questions = $this->get_objects(
			array(
				'post_parent' => $request['id'],
				'post_type'   => 'mto-question',
			)
		);

		return apply_filters( "masteriyo_{$this->object_type}_objects", $questions );
	}

	/**
	 * Get objects(sections, quizzes, lessons, etc.).
	 *
	 * @since 1.0.0
	 * @param string[] $query_args WP_Query args.
	 *
	 * @return Model[]
	 */
	protected function get_objects( $query_args ) {
		$query_args['posts_per_page'] = -1;
		$query                        = new \WP_Query( $query_args );

		return array_map( array( $this, 'get_object' ), $query->posts );
	}

	/**
	 * Get object.
	 *
	 * @since 1.0.0
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
	 * @since  1.0.0
	 * @param  Model         $object  Model object.
	 * @param  WP_REST_Request $request Request object.
	 * @return WP_Error|WP_REST_Response Response object on success, or WP_Error object on failure.
	 */
	protected function prepare_object_for_response( $object, $request ) {
		$context = ! empty( $request['context'] ) ? $request['context'] : 'view';
		$data    = $this->get_quiz_child_data( $object, $context );

		$data     = $this->add_additional_fields_to_object( $data, $request );
		$data     = $this->filter_response_by_context( $data, $context );
		$response = rest_ensure_response( $data );

		/**
		 * Filter the data for a response.
		 *
		 * The dynamic portion of the hook name, $this->object_type,
		 * refers to object type being prepared for the response.
		 *
		 * @since 1.0.0
		 *
		 * @param WP_REST_Response $response The response object.
		 * @param Model          $object   Object data.
		 * @param WP_REST_Request  $request  Request object.
		 */
		return apply_filters( "masteriyo_rest_prepare_{$this->object_type}_object", $response, $object, $request );
	}


	/**
	 * Get quiz child data.
	 *
	 * @param Model $quiz_item Quiz instance.
	 * @param string     $context Request context.
	 *                            Options: 'view' and 'edit'.
	 *
	 * @return array
	 */
	protected function get_quiz_child_data( $quiz_item, $context = 'view' ) {
		$data = array(
			'id'         => $quiz_item->get_id(),
			'name'       => $quiz_item->get_name( $context ),
			'permalink'  => $quiz_item->get_permalink( $context ),
			'type'       => $quiz_item->get_type( $context ),
			'menu_order' => $quiz_item->get_menu_order( $context ),
			'parent_id'  => $quiz_item->get_parent_id( $context ),
		);

		return $data;
	}

	/**
	 * Format the quiz items according to the builder format.
	 *
	 * @since 1.0.0
	 *
	 * @param Model[] $objects Quiz contents(sections, quizzes, lessons)
	 * @return Model[]
	 */
	protected function process_objects_collection( $objects ) {
		$results['contents'] = $objects;

		return $results;
	}

	/**
	 * Check permissions for an item.
	 *
	 * @since 1.0.0
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
	 * @since 1.0.0
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

		if ( ! $this->permission->rest_check_post_permissions( 'mto-quiz', 'update', $request['id'] ) ) {
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
	 * @since  1.0.0
	 * @param  WP_REST_Request $request  Full details about the request.
	 * @return Model|WP_Error
	 */
	public function update_item( $request ) {
		$quiz = get_post( $request['id'] );

		if ( is_null( $quiz ) || $this->post_type !== $quiz->post_type ) {
			return new \WP_Error(
				"masteriyo_rest_{$this->post_type}_invalid_id",
				__( 'Invalid ID', 'masteriyo' ),
				array( 'status' => 400 )
			);
		}

		// Save section order.
		$this->save_question_order( $request );

		return $this->get_item(
			array(
				'id' => $quiz->ID,
			)
		);
	}

	/**
	 * Filter question.
	 *
	 * @since 1.0.0
	 *
	 * @param int $question
	 * @return array
	 */
	protected function filter_questions( $question ) {
		$post = get_post( absint( $question ) );

		return $post && 'mto-question' === $post->post_type;
	}

	/**
	 * Save question order.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 */
	protected function save_question_order( $request ) {
		$questions = isset( $request['contents'] ) ? $request['contents'] : array();
		$questions = array_unique( wp_list_pluck( $questions, 'id' ) );
		$questions = array_filter( $questions, array( $this, 'filter_questions' ) );

		foreach ( $questions as $menu_order => $question ) {
			$this->update_post( $question, $menu_order, $request['id'] );
		}
	}

	/**
	 * Update post if the parent id or menu order is changed.
	 *
	 * @since 1.0.0
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
