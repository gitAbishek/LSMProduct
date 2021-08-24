<?php
/**
 * Question rest controller.
 */

namespace Masteriyo\RestApi\Controllers\Version1;

defined( 'ABSPATH' ) || exit;

use Masteriyo\Helper\Utils;
use Masteriyo\Helper\Permission;

class QuestionsController extends PostsController {
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
	protected $rest_base = 'questions';

	/**
	 * Post type.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected $post_type = 'question';

	/**
	 * Object type.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected $object_type = 'question';


	/**
	 * If object is hierarchical.
	 *
	 * @since 0.1.0
	 *
	 * @var bool
	 */
	protected $hierarchical = true;

	/**
	 * Question types.
	 *
	 * @var array
	 */
	protected $types = array(
		'true-false',
		'single-choice',
		'multiple-choice',
		'short-answer',
	);

	/**
	 * Permission class.
	 *
	 * @since 0.1.0
	 *
	 * @var Masteriyo\Helper\Permission;
	 */
	protected $permission = null;

	/**
	 * Constructor.
	 *
	 * @since 0.1.0
	 *
	 * @param Permission $permission
	 */
	public function __construct( Permission $permission ) {
		$this->permission = $permission;
	}

	/**
	 * Register routes.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	public function register_routes() {
		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base,
			array(
				array(
					'methods'             => \WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_items' ),
					'permission_callback' => array( $this, 'get_items_permissions_check' ),
					'args'                => $this->get_collection_params(),
				),
				array(
					'methods'             => \WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'create_item' ),
					'permission_callback' => array( $this, 'create_item_permissions_check' ),
					'args'                => $this->get_endpoint_args_for_item_schema( \WP_REST_Server::CREATABLE ),
				),
			)
		);

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
				array(
					'methods'             => \WP_REST_Server::DELETABLE,
					'callback'            => array( $this, 'delete_item' ),
					'permission_callback' => array( $this, 'delete_item_permissions_check' ),
				),
				'schema' => array( $this, 'get_public_item_schema' ),
			)
		);

		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base . '/check_answer',
			array(
				array(
					'methods'             => \WP_REST_Server::READABLE,
					'callback'            => array( $this, 'check_answer' ),
					'permission_callback' => array( $this, 'check_answer_permissions_check' ),
					'args'                => array(
						'id'            => array(
							'description'       => __( 'Question ID.', 'masteriyo' ),
							'type'              => 'integer',
							'required'          => true,
							'sanitize_callback' => 'absint',
							'validate_callback' => 'rest_validate_request_arg',
						),
						'chosen_answer' => array(
							'description' => __( 'Chosen answer or answers.', 'masteriyo' ),
							'type'        => 'any',
							'required'    => true,
						),
					),
				),
			)
		);
	}

	/**
	 * Check if a given request has access to check correct answers.
	 *
	 * @since 0.1.0
	 *
	 * @param  WP_REST_Request $request Full details about the request.
	 * @return WP_Error|boolean
	 */
	public function check_answer_permissions_check( $request ) {
		if ( is_null( $this->permission ) ) {
			return new \WP_Error(
				'masteriyo_null_permission',
				__( 'Sorry, the permission object for this resource is null.', 'masteriyo' )
			);
		}

		if ( ! $this->permission->rest_check_answer_check_permissions() ) {
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
	 * Check given answer if it's correct or not.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_Error|WP_REST_Response
	 */
	public function check_answer( $request ) {
		$object = $this->get_object( (int) $request['id'] );

		if ( ! $object || 0 === $object->get_id() ) {
			return new \WP_Error( "masteriyo_rest_{$this->post_type}_invalid_id", __( 'Invalid ID.', 'masteriyo' ), array( 'status' => 404 ) );
		}

		$chosen_answer        = isset( $request['chosen_answer'] ) ? $request['chosen_answer'] : null;
		$is_correct           = $object->check_answer( $chosen_answer, 'view' );
		$correct_answer_msg   = __( 'The answer is correct.', 'masteriyo' );
		$incorrect_answer_msg = __( 'The answer is incorrect.', 'masteriyo' );
		$response             = array(
			'is_correct' => $is_correct,
			'message'    => $is_correct ? $correct_answer_msg : $incorrect_answer_msg,
		);

		return apply_filters( 'masteriyo_answer_check_rest_reponse', $response );
	}

	/**
	 * Get the query params for collections of attachments.
	 *
	 * @since 0.1.0
	 *
	 * @return array
	 */
	public function get_collection_params() {
		$params = parent::get_collection_params();

		$params['course_id'] = array(
			'description'       => __( 'Limit result by course id.', 'masteriyo' ),
			'type'              => 'integer',
			'sanitize_callback' => 'absint',
			'validate_callback' => 'rest_validate_request_arg',
		);

		$params['status'] = array(
			'default'           => 'any',
			'description'       => __( 'Limit result set to questiones assigned a specific status.', 'masteriyo' ),
			'type'              => 'string',
			'enum'              => array_merge( array( 'any', 'future' ), array_keys( get_post_statuses() ) ),
			'sanitize_callback' => 'sanitize_key',
			'validate_callback' => 'rest_validate_request_arg',
		);

		$params['type'] = array(
			'description'       => __( 'Limit result set to questiones assigned a specific type.', 'masteriyo' ),
			'type'              => 'string',
			'enum'              => apply_filters( 'masteriyo_question_types', $this->get_types() ),
			'sanitize_callback' => 'sanitize_key',
			'validate_callback' => 'rest_validate_request_arg',
		);

		$params['category'] = array(
			'description'       => __( 'Limit result set to courses assigned a specific category ID.', 'masteriyo' ),
			'type'              => 'string',
			'sanitize_callback' => 'wp_parse_id_list',
			'validate_callback' => 'rest_validate_request_arg',
		);
		$params['tag']      = array(
			'description'       => __( 'Limit result set to courses assigned a specific tag ID.', 'masteriyo' ),
			'type'              => 'string',
			'sanitize_callback' => 'wp_parse_id_list',
			'validate_callback' => 'rest_validate_request_arg',
		);

		return $params;
	}

	/**
	 * Get object.
	 *
	 * @since 0.1.0
	 *
	 * @param  int|WP_Post|Model $object Object ID or WP_Post or Model.
	 *
	 * @return object Model object or WP_Error object.
	 */
	protected function get_object( $object ) {
		try {
			if ( is_int( $object ) ) {
				$id = $object;
			} else {
				$id = is_a( $object, '\WP_Post' ) ? $object->ID : $object->get_id();
			}
			$type     = get_post_meta( $id, '_type', true );
			$question = masteriyo( "question.${type}" );
			$question->set_id( $id );
			$question_repo = masteriyo( 'question.store' );
			$question_repo->read( $question );
		} catch ( \Exception $e ) {
			return false;
		}

		return $question;
	}


	/**
	 * Prepares the object for the REST response.
	 *
	 * @since  0.1.0
	 *
	 * @param  Model           $object  Model object.
	 * @param  WP_REST_Request $request Request object.
	 *
	 * @return WP_Error|WP_REST_Response Response object on success, or WP_Error object on failure.
	 */
	protected function prepare_object_for_response( $object, $request ) {
		$context = ! empty( $request['context'] ) ? $request['context'] : 'view';
		$data    = $this->get_question_data( $object, $context );

		$data     = $this->add_additional_fields_to_object( $data, $request );
		$data     = $this->filter_response_by_context( $data, $context );
		$response = rest_ensure_response( $data );
		$response->add_links( $this->prepare_links( $object, $request ) );

		/**
		 * Filter the data for a response.
		 *
		 * The dynamic portion of the hook name, $this->post_type,
		 * refers to object type being prepared for the response.
		 *
		 * @since 0.1.0
		 *
		 * @param WP_REST_Response $response The response object.
		 * @param Model          $object   Object data.
		 * @param WP_REST_Request  $request  Request object.
		 */
		return apply_filters( "masteriyo_rest_prepare_{$this->post_type}_object", $response, $object, $request );
	}

	/**
	 * Get question data.
	 *
	 * @since 0.1.0
	 *
	 * @param Question $question Question instance.
	 * @param string   $context Request context.
	 *                          Options: 'view' and 'edit'.
	 *
	 * @return array
	 */
	protected function get_question_data( $question, $context = 'view' ) {
		$data = array(
			'id'                => $question->get_id(),
			'name'              => $question->get_name( $context ),
			'permalink'         => $question->get_permalink(),
			'status'            => $question->get_status( $context ),
			'description'       => 'view' === $context ? apply_filters( 'masteriyo_description', $question->get_description() ) : $question->get_description( $context ),
			'type'              => $question->get_type( $context ),
			'parent_id'         => $question->get_parent_id( $context ),
			'course_id'         => $question->get_course_id( $context ),
			'menu_order'        => $question->get_menu_order( $context ),
			'answer_required'   => $question->get_answer_required( $context ),
			'randomize'         => $question->get_randomize( $context ),
			'points'            => $question->get_points( $context ),
			'positive_feedback' => $question->get_positive_feedback( $context ),
			'negative_feedback' => $question->get_negative_feedback( $context ),
			'feedback'          => $question->get_feedback( $context ),
		);

		$data['answers'] = $question->get_answers( $context );
		if ( 'view' === $context ) {
			$data['answers'] = $this->process_answers( $question->get_answers( $context ), $question );
		}

		return $data;
	}

	/**
	 * Process answers based on user roles.
	 *
	 * @param mixed    $answers Available answer(s).
	 * @param Question $question Question object.
	 */
	protected function process_answers( $answers, $question ) {
		switch ( $question->get_type( 'edit' ) ) { // phpcs:ignore Generic.CodeAnalysis.EmptyStatement.DetectedSwitch
			// TODO: Filter the correct answers data according to the user role.
		}

		return $answers;
	}

	/**
	 * Prepare objects query.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 *
	 * @since  0.1.0
	 *
	 * @return array
	 */
	protected function prepare_objects_query( $request ) {
		$args = parent::prepare_objects_query( $request );

		// Set post_status.
		$args['post_status'] = $request['status'];

		if ( ! empty( $request['course_id'] ) ) {
			$args['meta_query'] = array(
				'relation' => 'AND',
				array(
					'key'     => '_course_id',
					'value'   => absint( $request['course_id'] ),
					'compare' => '=',
				),
			);
		}

		// Taxonomy query to filter questiones by type, category,
		// tag, shipping class, and attribute.
		$tax_query = array();

		// Map between taxonomy name and arg's key.
		$taxonomies = array(
			'question_cat'        => 'category',
			'question_tag'        => 'tag',
			'question_difficulty' => 'difficulty',
		);

		// Set tax_query for each passed arg.
		foreach ( $taxonomies as $taxonomy => $key ) {
			if ( ! empty( $request[ $key ] ) ) {
				$tax_query[] = array(
					'taxonomy' => $taxonomy,
					'field'    => 'term_id',
					'terms'    => $request[ $key ],
				);
			}
		}

		// Filter question type by slug.
		if ( ! empty( $request['type'] ) ) {
			$tax_query[] = array(
				'taxonomy' => 'question_type',
				'field'    => 'slug',
				'terms'    => $request['type'],
			);
		}

		// Filter featured.
		if ( is_bool( $request['featured'] ) ) {
			$args['tax_query'][] = array(
				'taxonomy' => 'question_visibility',
				'field'    => 'name',
				'terms'    => 'featured',
				'operator' => true === $request['featured'] ? 'IN' : 'NOT IN',
			);
		}

		return $args;
	}

	/**
	 * Get the questiones'schema, conforming to JSON Schema.
	 *
	 * @since 0.1.0
	 *
	 * @return array
	 */
	public function get_item_schema() {
		$schema = array(
			'$schema'    => 'http://json-schema.org/draft-04/schema#',
			'title'      => $this->post_type,
			'type'       => 'object',
			'properties' => array(
				'id'                => array(
					'description' => __( 'Unique identifier for the resource.', 'masteriyo' ),
					'type'        => 'integer',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
				'name'              => array(
					'description' => __( 'Question name.', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'slug'              => array(
					'description' => __( 'Question slug.', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'permalink'         => array(
					'description' => __( 'Question URL.', 'masteriyo' ),
					'type'        => 'string',
					'format'      => 'uri',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
				'date_created'      => array(
					'description' => __( "The date the question was created, in the site's timezone.", 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'date_created_gmt'  => array(
					'description' => __( 'The date the question was created, as GMT.', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'date_modified'     => array(
					'description' => __( "The date the question was last modified, in the site's timezone.", 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
				'date_modified_gmt' => array(
					'description' => __( 'The date the question was last modified, as GMT.', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
				'status'            => array(
					'description' => __( 'Question status (post status).', 'masteriyo' ),
					'type'        => 'string',
					'default'     => 'publish',
					'enum'        => array_merge( array_keys( get_post_statuses() ), array( 'future' ) ),
					'context'     => array( 'view', 'edit' ),
				),
				'parent_id'         => array(
					'description' => __( 'Course parent ID.', 'masteriyo' ),
					'type'        => 'integer',
					'required'    => true,
					'context'     => array( 'view', 'edit' ),
				),
				'course_id'         => array(
					'description' => __( 'Course ID.', 'masteriyo' ),
					'type'        => 'integer',
					'required'    => true,
					'context'     => array( 'view', 'edit' ),
				),
				'menu_order'        => array(
					'description' => __( 'Menu order, used to custom sort questions.', 'masteriyo' ),
					'type'        => 'integer',
					'context'     => array( 'view', 'edit' ),
				),
				'description'       => array(
					'description' => __( 'Question description.', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'short_description' => array(
					'description' => __( 'Question short description.', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'type'              => array(
					'description' => __( 'Question type.', 'masteriyo' ),
					'type'        => 'string',
					'enum'        => apply_filters( 'masteriyo_question_types', $this->get_types() ),
					'context'     => array( 'view', 'edit' ),
				),
				'answer_required'   => array(
					'description' => __( 'Whether the question is required or not.', 'masteriyo' ),
					'type'        => 'boolean',
					'context'     => array( 'view', 'edit' ),
				),
				'answers'           => array(
					'description' => __( 'Given answer list for the question.', 'masteriyo' ),
					'type'        => 'object',
					'context'     => array( 'view', 'edit' ),
				),
				'points'            => array(
					'description' => __( 'Points for the correct answer.', 'masteriyo' ),
					'type'        => 'number',
					'context'     => array( 'view', 'edit' ),
				),
				'randomize'         => array(
					'description' => __( 'Whether to the answers.', 'masteriyo' ),
					'type'        => 'boolean',
					'context'     => array( 'view', 'edit' ),
				),
				'meta_data'         => array(
					'description' => __( 'Meta data.', 'masteriyo' ),
					'type'        => 'array',
					'context'     => array( 'view', 'edit' ),
					'items'       => array(
						'type'       => 'object',
						'properties' => array(
							'id'    => array(
								'description' => __( 'Meta ID.', 'masteriyo' ),
								'type'        => 'integer',
								'context'     => array( 'view', 'edit' ),
								'readonly'    => true,
							),
							'key'   => array(
								'description' => __( 'Meta key.', 'masteriyo' ),
								'type'        => 'string',
								'context'     => array( 'view', 'edit' ),
							),
							'value' => array(
								'description' => __( 'Meta value.', 'masteriyo' ),
								'type'        => 'mixed',
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
	 * Prepare a single question for create or update.
	 *
	 * @since 0.1.0
	 *
	 * @param WP_REST_Request $request Request object.
	 * @param bool            $creating If is creating a new object.
	 *
	 * @return WP_Error|Model
	 */
	protected function prepare_object_for_database( $request, $creating = false ) {
		$id       = isset( $request['id'] ) ? absint( $request['id'] ) : 0;
		$type     = isset( $request['type'] ) ? $request['type'] : 'true-false';
		$question = masteriyo( "question.${type}" );

		if ( 0 !== $id ) {
			$question->set_id( $id );
			$question_repo = masteriyo( 'question.store' );
			$question_repo->read( $question );
		}

		// Post title.
		if ( isset( $request['name'] ) ) {
			$question->set_name( wp_filter_post_kses( $request['name'] ) );
		}

		// Post content.
		if ( isset( $request['description'] ) ) {
			$question->set_description( wp_filter_post_kses( $request['description'] ) );
		}

		// Course ID.
		if ( isset( $request['course_id'] ) ) {
			$question->set_course_id( $request['course_id'] );
		}

		// Post answers.
		if ( isset( $request['answers'] ) ) {
			$question->set_answers( $request['answers'] );
		}

		// Post status.
		if ( isset( $request['status'] ) ) {
			$question->set_status( get_post_status_object( $request['status'] ) ? $request['status'] : 'draft' );
		}

		// Post slug.
		if ( isset( $request['slug'] ) ) {
			$question->set_slug( $request['slug'] );
		}

		// Menu order.
		if ( isset( $request['menu_order'] ) ) {
			$question->set_menu_order( $request['menu_order'] );
		}

		// Post type.
		if ( isset( $request['type'] ) ) {
			$question->set_type( $request['type'] );
		}

		// Question parent ID.
		if ( isset( $request['parent_id'] ) ) {
			$question->set_parent_id( $request['parent_id'] );
		}

		// Post answer required.
		if ( isset( $request['answer_required'] ) ) {
			$question->set_answer_required( $request['answer_required'] );
		}

		// Post randomize.
		if ( isset( $request['randomize'] ) ) {
			$question->set_randomize( $request['randomize'] );
		}

		// Post points.
		if ( isset( $request['points'] ) ) {
			$question->set_points( $request['points'] );
		}

		// Post positive feedback.
		if ( isset( $request['positive_feedback'] ) ) {
			$question->set_positive_feedback( $request['positive_feedback'] );
		}

		// Post negative feedback.
		if ( isset( $request['negative_feedback'] ) ) {
			$question->set_negative_feedback( $request['negative_feedback'] );
		}

		// Post feedback.
		if ( isset( $request['feedback'] ) ) {
			$question->set_feedback( $request['feedback'] );
		}

		// Allow set meta_data.
		if ( isset( $request['meta_data'] ) && is_array( $request['meta_data'] ) ) {
			foreach ( $request['meta_data'] as $meta ) {
				$question->update_meta_data( $meta['key'], $meta['value'], isset( $meta['id'] ) ? $meta['id'] : '' );
			}
		}

		/**
		 * Filters an object before it is inserted via the REST API.
		 *
		 * The dynamic portion of the hook name, `$this->post_type`,
		 * refers to the object type slug.
		 *
		 * @param Model         $question  Object object.
		 * @param WP_REST_Request $request  Request object.
		 * @param bool            $creating If is creating a new object.
		 */
		return apply_filters( "masteriyo_rest_pre_insert_{$this->post_type}_object", $question, $request, $creating );
	}

	/**
	 * Save taxonomy terms.
	 *
	 * @since 0.1.0
	 *
	 * @param Question $question  Question instance.
	 * @param array    $terms    Terms data.
	 * @param string   $taxonomy Taxonomy name.
	 *
	 * @return Question
	 */
	protected function save_taxonomy_terms( $question, $terms, $taxonomy = 'cat' ) {
		$term_ids = wp_list_pluck( $terms, 'id' );

		if ( 'cat' === $taxonomy ) {
			$question->set_category_ids( $term_ids );
		} elseif ( 'tag' === $taxonomy ) {
			$question->set_tag_ids( $term_ids );
		} elseif ( 'difficulty' === $taxonomy ) {
			$question->set_difficulty_ids( $term_ids );
		}

		return $question;
	}

	/**
	 * Get question types.
	 *
	 * @since 0.1.0
	 *
	 * @return array
	 */
	protected function get_types() {
		return $this->types;
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

		if ( masteriyo_is_current_user_admin() || masteriyo_is_current_user_manager() ) {
			return true;
		}

		if ( ! $this->permission->rest_check_post_permissions( $this->post_type, 'create' ) ) {
			return new \WP_Error(
				'masteriyo_rest_cannot_create',
				__( 'Sorry, you are not allowed to create resources.', 'masteriyo' ),
				array(
					'status' => rest_authorization_required_code(),
				)
			);
		}

		$course_id = absint( $request['course_id'] );
		$course    = masteriyo_get_course( $course_id );

		if ( is_null( $course ) ) {
			return new \WP_Error(
				"masteriyo_rest_{$this->post_type}_invalid_id",
				__( 'Invalid course ID.', 'masteriyo' ),
				array(
					'status' => 404,
				)
			);
		}

		if ( $course->get_author_id() !== get_current_user_id() ) {
			return new \WP_Error(
				'masteriyo_rest_cannot_create',
				__( 'Sorry, you are not allowed to create question for others course.', 'masteriyo' ),
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

		if ( masteriyo_is_current_user_admin() || masteriyo_is_current_user_manager() ) {
			return true;
		}

		$id       = absint( $request['id'] );
		$question = masteriyo_get_question( $id );

		if ( is_null( $question ) ) {
			return new \WP_Error(
				"masteriyo_rest_{$this->post_type}_invalid_id",
				__( 'Invalid ID.', 'masteriyo' ),
				array(
					'status' => 404,
				)
			);
		}

		if ( ! $this->permission->rest_check_post_permissions( $this->post_type, 'delete', $id ) ) {
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

		if ( masteriyo_is_current_user_admin() || masteriyo_is_current_user_manager() ) {
			return true;
		}

		$id       = absint( $request['id'] );
		$question = masteriyo_get_question( $id );

		if ( is_null( $question ) ) {
			return new \WP_Error(
				"masteriyo_rest_{$this->post_type}_invalid_id",
				__( 'Invalid ID.', 'masteriyo' ),
				array(
					'status' => 404,
				)
			);
		}

		if ( ! $this->permission->rest_check_post_permissions( $this->post_type, 'update', $id ) ) {
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
}
