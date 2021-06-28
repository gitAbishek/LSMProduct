<?php
/**
 * Quiz rest controller.
 */

namespace ThemeGrill\Masteriyo\RestApi\Controllers\Version1;

defined( 'ABSPATH' ) || exit;

use ThemeGrill\Masteriyo\Helper\Utils;
use ThemeGrill\Masteriyo\Helper\Permission;
use ThemeGrill\Masteriyo\RestApi\Controllers\Version1\QuestionsController;

class QuizesController extends PostsController {
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
	protected $rest_base = 'quizes';

	/**
	 * Object type.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected $object_type = 'quiz';

	/**
	 * Post type.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected $post_type = 'quiz';

	/**
	 * If object is hierarchical.
	 *
	 * @since 0.1.0
	 *
	 * @var bool
	 */
	protected $hierarchical = true;

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
			'/' . $this->rest_base . '/(?P<id>[\d]+)' . '/start_quiz',
			array(
				'args' => array(
					'id' => array(
						'description' => __( 'Unique identifier for the resource.', 'masteriyo' ),
						'type'        => 'integer',
					),
				),
				array(
					'methods'  => \WP_REST_Server::CREATABLE,
					'callback' => array( $this, 'start_quiz' ),
					// 'permission_callback' => array( $this, 'check_answer_permissions_check' ),
				),
			)
		);

		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base . '/(?P<id>[\d]+)' . '/check_answers',
			array(
				'args' => array(
					'id' => array(
						'description' => __( 'Unique identifier for the resource.', 'masteriyo' ),
						'type'        => 'integer',
					),
				),
				array(
					'methods'  => \WP_REST_Server::CREATABLE,
					'callback' => array( $this, 'check_answers' ),
					// 'permission_callback' => array( $this, 'check_answer_permissions_check' ),
				),
			)
		);

		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base . '/attempts',
			array(
				array(
					'methods'  => \WP_REST_Server::READABLE,
					'callback' => array( $this, 'get_attempts' ),
					// 'permission_callback' => array( $this, 'get_attempts_permissions_check' ),
					'args'     => array(
						'quiz_id'  => array(
							'description'       => __( 'Quiz ID.', 'masteriyo' ),
							'type'              => 'integer',
							'required'          => true,
							'sanitize_callback' => 'absint',
							'validate_callback' => 'rest_validate_request_arg',
						),
						'user_id'  => array(
							'description'       => __( 'User ID.', 'masteriyo' ),
							'type'              => 'integer',
							'required'          => true,
							'sanitize_callback' => 'absint',
							'validate_callback' => 'rest_validate_request_arg',
						),
						'orderby'  => array(
							'description'       => __( 'Sort collection by object attribute.', 'masteriyo' ),
							'type'              => 'string',
							'default'           => 'attempt_id',
							'enum'              => array(
								'attempt_id',
								'course_id',
								'quiz_id',
								'attempt_started_at',
								'attempt_ended_at',
							),
							'validate_callback' => 'rest_validate_request_arg',
						),
						'order'    => array(
							'description'       => __( 'Order sort attribute ascending or descending.', 'masteriyo' ),
							'type'              => 'string',
							'default'           => 'desc',
							'enum'              => array( 'asc', 'desc' ),
							'validate_callback' => 'rest_validate_request_arg',
						),
						'paged'    => array(
							'description'       => __( 'Paginate the quiz attempts.', 'masteriyo' ),
							'type'              => 'integer',
							'default'           => 1,
							'sanitize_callback' => 'absint',
							'validate_callback' => 'rest_validate_request_arg',
							'minimum'           => 1,
						),
						'per_page' => array(
							'description'       => __( 'Limit items per page.', 'masteriyo' ),
							'type'              => 'integer',
							'default'           => 10,
							'minimum'           => 1,
							'sanitize_callback' => 'absint',
							'validate_callback' => 'rest_validate_request_arg',
						),
					),
				),
			)
		);
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

		// The quizes should be order by menu which is the sort order.
		$params['order']['default']   = 'asc';
		$params['orderby']['default'] = 'menu_order';

		$params['slug']       = array(
			'description'       => __( 'Limit result set to quizes with a specific slug.', 'masteriyo' ),
			'type'              => 'string',
			'validate_callback' => 'rest_validate_request_arg',
		);
		$params['status']     = array(
			'default'           => 'any',
			'description'       => __( 'Limit result set to quizes assigned a specific status.', 'masteriyo' ),
			'type'              => 'string',
			'enum'              => array_merge( array( 'any', 'future' ), array_keys( get_post_statuses() ) ),
			'sanitize_callback' => 'sanitize_key',
			'validate_callback' => 'rest_validate_request_arg',
		);
		$params['course_id']  = array(
			'description'       => __( 'Limit results by course id.', 'masteriyo' ),
			'type'              => 'integer',
			'sanitize_callback' => 'absint',
			'validate_callback' => 'rest_validate_request_arg',
		);
		$params['category']   = array(
			'description'       => __( 'Limit result set to quizes assigned a specific category ID.', 'masteriyo' ),
			'type'              => 'string',
			'sanitize_callback' => 'wp_parse_id_list',
			'validate_callback' => 'rest_validate_request_arg',
		);
		$params['tag']        = array(
			'description'       => __( 'Limit result set to quizes assigned a specific tag ID.', 'masteriyo' ),
			'type'              => 'string',
			'sanitize_callback' => 'wp_parse_id_list',
			'validate_callback' => 'rest_validate_request_arg',
		);
		$params['difficulty'] = array(
			'description'       => __( 'Limit result set to quizes assigned a specific difficulty ID.', 'masteriyo' ),
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
	 * @param  int|WP_Post|ThemeGrill\Masteriyo\Models\Quiz $object Object ID or WP_Post or Quiz object.
	 *
	 * @return ThemeGrill\Masteriyo\Models\Quiz|WP_Error Quiz object or WP_Error object.
	 */
	protected function get_object( $object ) {
		try {
			if ( is_int( $object ) ) {
				$id = $object;
			} else {
				$id = is_a( $object, '\WP_Post' ) ? $object->ID : $object->get_id();
			}

			$quiz = masteriyo( 'quiz' );
			$quiz->set_id( $id );
			$quiz_repo = masteriyo( 'quiz.store' );
			$quiz_repo->read( $quiz );
		} catch ( \Exception $e ) {
			return false;
		}

		return $quiz;
	}

	/**
	 * Get Question object.
	 *
	 * @since 0.1.0
	 *
	 * @param  int|WP_Post|Model $object Object ID or WP_Post or Model.
	 *
	 * @return object Model object or WP_Error object.
	 */
	protected function get_question_object( $object ) {
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
	 * Collect data after starting quiz.
	 *
	 * @since 0.1.0
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 */
	public function start_quiz( $request ) {

		global $wpdb;

		if ( ! is_user_logged_in() ) {
			return new \WP_Error(
				'masteriyo_rest_user_not_logged_in',
				__( 'Please sign in to start the quiz.', 'masteriyo' ),
				array( 'status' => 404 )
			);
		}

		$user_id = (int) get_current_user_id();
		$quiz_id = (int) $request['id'];

		$course = get_course_by_quiz( $quiz_id );

		if ( empty( $course->ID ) ) {
			return new \WP_Error(
				'masteriyo_rest_course_empty_id',
				__( 'There is something went wrong with course, please check if quiz attached with a course', 'masteriyo' ),
				array( 'status' => 404 )
			);
		}

		$course_id = (int) $course->ID;
		$date      = date( 'Y-m-d H:i:s', time() );

		$attempt_data = array(
			'course_id'                => $course_id,
			'quiz_id'                  => $quiz_id,
			'user_id'                  => $user_id,
			'total_answered_questions' => 0,
			'attempt_status'           => 'attempt_started',
			'attempt_started_at'       => $date,
		);
		$wpdb->insert( $wpdb->prefix . 'masteriyo_quiz_attempts', $attempt_data );

		$response = array(
			'status_code' => 'started',
			'message'     => __( 'Quiz has been started.', 'masteriyo' ),
			'data'        => array(
				'quiz_id' => (int) $quiz_id,
				'user_id' => (int) $user_id,
			),
		);

		return apply_filters( 'masteriyo_start_quiz_rest_reponse', $response );

	}

	/**
	 * Check given answer and collect the results.
	 *
	 * @since 0.1.0
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 */
	public function check_answers( $request ) {

		global $wpdb;

		$parameters           = $request->get_params();
		$quiz_id              = (int) $request['id'];
		$user_id              = get_current_user_id();
		$total_earned_marks   = 0;
		$attempt_questions    = 0;
		$total_question_marks = 0;

		$attempt_data = is_quiz_started( $quiz_id );

		$previous_attempts = get_all_quiz_attempts( $quiz_id, $user_id );
		$attempted_count   = is_array( $previous_attempts ) ? count( $previous_attempts ) : 0;

		if ( is_array( $parameters ) && count( $parameters ) > 0 ) {
			foreach ( $parameters as $parameter ) {

				if ( is_array( $parameter ) && count( $parameters ) > 0 ) {
					foreach ( $parameter as $key => $value ) {

						if ( 'question_id' === $key ) {
							$object = $this->get_question_object( (int) $value );

							if ( ! $object || 0 === $object->get_id() ) {
								return new \WP_Error(
									"masteriyo_rest_{$this->post_type}_invalid_id",
									__( 'Invalid ID.', 'masteriyo' ),
									array( 'status' => 404 )
								);
							}

							$total_question_marks += $object->get_points();
						}
						if ( 'answers' === $key ) {
							$is_correct = $object->check_answer( $value, 'view' );

							$question_mark       = $is_correct ? $object->get_points() : 0;
							$total_earned_marks += $question_mark;
							$attempt_questions++;
						}
					}
				}
			}

			$quiz_questions = get_quiz_questions( $quiz_id, 'post_parent' );

			$attempt_detail = array(
				'total_marks'              => $total_question_marks,
				'earned_marks'             => $total_earned_marks,
				'total_questions'          => $quiz_questions->post_count,
				'total_answered_questions' => $attempt_questions,
				'total_attempts'           => $attempted_count,
				'attempt_status'           => 'attempt_ended',
				'attempt_ended_at'         => date( 'Y-m-d H:i:s', time() ),
			);

			$wpdb->update(
				$wpdb->prefix . 'masteriyo_quiz_attempts',
				$attempt_detail,
				array( 'attempt_id' => $attempt_data->attempt_id )
			);

			$attempt_datas = (array) get_quiz_attempts_data( $quiz_id, $attempt_data->attempt_id );

			$keys = array(
				'attempt_id',
				'course_id',
				'quiz_id',
				'user_id',
				'total_questions',
				'total_answered_questions',
				'total_attempts',
			);

			foreach ( $keys as $key ) {
				if ( array_key_exists( $key, $attempt_datas ) ) {
					$attempt_datas[ $key ] = (int) $attempt_datas[ $key ];
				}
			}

			return apply_filters( 'masteriyo_answer_check_rest_reponse', $attempt_datas );
		}
	}

	public function get_attempts( $request ) {
		$parameters = $request->get_params();

		$quiz_id = $parameters['quiz_id'];
		$user_id = $parameters['user_id'];

		$query_vars = array(
			'user_id'  => $user_id,
			'quiz_id'  => $quiz_id,
			'per_page' => $parameters['per_page'],
			'paged'    => $parameters['paged'],
			'orderby'  => $parameters['orderby'],
			'order'    => $parameters['order'],
		);

		$attempt_datas = (array) quiz_attempts_query( $query_vars );

		$response = $this->prepare_quiz_attempts_for_response( $attempt_datas );

		return $response;

	}

	/**
	 * Changed value from string to integer for response.
	 *
	 * @since 0.1.0
	 *
	 * @param array $attempt_data Quiz attempt data.
	 * @return array
	 */
	protected function prepare_quiz_attempts_for_response( $attempt_datas ) {
		// Keys which value should be changed to integer.
		$keys = array(
			'attempt_id',
			'course_id',
			'quiz_id',
			'user_id',
			'total_questions',
			'total_answered_questions',
			'total_attempts',
		);

		$response_data = array();

		if ( count( $attempt_datas ) > 1 ) {
			foreach ( $attempt_datas as $attempt_data ) {
				$attempt_data = (array) $attempt_data;
				foreach ( $keys as $key ) {
					if ( array_key_exists( $key, $attempt_data ) ) {
						$attempt_data[ $key ] = (int) $attempt_data[ $key ];
					}
				}
				$response_data[] = $attempt_data;
			}
			return $response_data;
		}

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
		$data    = $this->get_quiz_data( $object, $context );

		$data     = $this->add_additional_fields_to_object( $data, $request );
		$data     = $this->filter_response_by_context( $data, $context );
		$response = rest_ensure_response( $data );
		$response->add_links( $this->prepare_links( $object, $request ) );

		/**
		 * Filter the data for a response.
		 *
		 * The dynamic portion of the hook name, $this->object_type,
		 * refers to object type being prepared for the response.
		 *
		 * @since 0.1.0
		 *
		 * @param WP_REST_Response $response The response object.
		 * @param Model          $object   Object data.
		 * @param WP_REST_Request  $request  Request object.
		 */
		return apply_filters( "masteriyo_rest_prepare_{$this->object_type}_object", $response, $object, $request );
	}

	/**
	 * Get quiz data.
	 *
	 * @since 0.1.0
	 *
	 * @param Quiz   $quiz Quiz instance.
	 * @param string $context Request context.
	 *                        Options: 'view' and 'edit'.
	 *
	 * @return array
	 */
	protected function get_quiz_data( $quiz, $context = 'view' ) {
		$data = array(
			'id'                => $quiz->get_id(),
			'name'              => $quiz->get_name( $context ),
			'slug'              => $quiz->get_slug( $context ),
			'permalink'         => $quiz->get_permalink(),
			'parent_id'         => $quiz->get_parent_id( $context ),
			'course_id'         => $quiz->get_course_id( $context ),
			'menu_order'        => $quiz->get_menu_order( $context ),
			'status'            => $quiz->get_status( $context ),
			'description'       => 'view' === $context ? wpautop( do_shortcode( $quiz->get_description() ) ) : $quiz->get_description( $context ),
			'short_description' => 'view' === $context ? apply_filters( 'masteriyo_short_description', $quiz->get_short_description() ) : $quiz->get_short_description( $context ),
			'pass_mark'         => $quiz->get_pass_mark( $context ),
			'full_mark'         => $quiz->get_full_mark( $context ),
			'duration'          => $quiz->get_duration( $context ),
			'questions_count'   => $quiz->get_questions_count(),
			'navigation'        => $this->get_navigation_items( $quiz, $context ),
		);

		return $data;
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

		// Taxonomy query to filter quizes by type, category,
		// tag, shipping class, and attribute.
		$tax_query = array();

		// Map between taxonomy name and arg's key.
		$taxonomies = array(
			'quiz_cat'        => 'category',
			'quiz_tag'        => 'tag',
			'quiz_difficulty' => 'difficulty',
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

		// Build tax_query if taxonomies are set.
		if ( ! empty( $tax_query ) ) {
			if ( ! empty( $args['tax_query'] ) ) {
				$args['tax_query'] = array_merge( $tax_query, $args['tax_query'] ); // WPCS: slow query ok.
			} else {
				$args['tax_query'] = $tax_query; // WPCS: slow query ok.
			}
		}

		// Filter featured.
		if ( is_bool( $request['featured'] ) ) {
			$args['tax_query'][] = array(
				'taxonomy' => 'quiz_visibility',
				'field'    => 'name',
				'terms'    => 'featured',
				'operator' => true === $request['featured'] ? 'IN' : 'NOT IN',
			);
		}

		return $args;
	}

	/**
	 * Get the quizes'schema, conforming to JSON Schema.
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
				'id'                => array(
					'description' => __( 'Unique identifier for the resource.', 'masteriyo' ),
					'type'        => 'integer',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
				'name'              => array(
					'description' => __( 'Quiz name.', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'slug'              => array(
					'description' => __( 'Quiz slug.', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'parent_id'         => array(
					'description' => __( 'Quiz parent ID.', 'masteriyo' ),
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
					'description' => __( 'Menu order, used to custom sort quizes.', 'masteriyo' ),
					'type'        => 'integer',
					'required'    => true,
					'context'     => array( 'view', 'edit' ),
				),
				'permalink'         => array(
					'description' => __( 'Quiz URL.', 'masteriyo' ),
					'type'        => 'string',
					'format'      => 'uri',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
				'date_created'      => array(
					'description' => __( "The date the quiz was created, in the site's timezone.", 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'date_created_gmt'  => array(
					'description' => __( 'The date the quiz was created, as GMT.', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'date_modified'     => array(
					'description' => __( "The date the quiz was last modified, in the site's timezone.", 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
				'date_modified_gmt' => array(
					'description' => __( 'The date the quiz was last modified, as GMT.', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
				'status'            => array(
					'description' => __( 'Quiz status (post status).', 'masteriyo' ),
					'type'        => 'string',
					'default'     => 'publish',
					'enum'        => array_merge( array_keys( get_post_statuses() ), array( 'future' ) ),
					'context'     => array( 'view', 'edit' ),
				),
				'description'       => array(
					'description' => __( 'Quiz description.', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'short_description' => array(
					'description' => __( 'Quiz short description.', 'masteriyo' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'pass_mark'         => array(
					'description' => __( 'Quiz pass mark.', 'masteriyo' ),
					'type'        => 'integer',
					'required'    => false,
					'context'     => array( 'view', 'edit' ),
				),
				'full_mark'         => array(
					'description' => __( 'Quiz full mark..', 'masteriyo' ),
					'type'        => 'integer',
					'required'    => false,
					'context'     => array( 'view', 'edit' ),
				),
				'duration'          => array(
					'description' => __( 'Quiz duration (seconds).', 'masteriyo' ),
					'type'        => 'integer',
					'required'    => false,
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
	 * Prepare a single quiz for create or update.
	 *
	 * @since 0.1.0
	 *
	 * @param WP_REST_Request $request Request object.
	 * @param bool            $creating If is creating a new object.
	 *
	 * @return WP_Error|Model
	 */
	protected function prepare_object_for_database( $request, $creating = false ) {
		$id   = isset( $request['id'] ) ? absint( $request['id'] ) : 0;
		$quiz = masteriyo( 'quiz' );

		if ( 0 !== $id ) {
			$quiz->set_id( $id );
			$quiz_repo = masteriyo( 'quiz.store' );
			$quiz_repo->read( $quiz );
		}

		// Quiz title.
		if ( isset( $request['name'] ) ) {
			$quiz->set_name( wp_filter_post_kses( $request['name'] ) );
		}

		// Quiz content.
		if ( isset( $request['description'] ) ) {
			$quiz->set_description( wp_filter_post_kses( $request['description'] ) );
		}

		// Quiz excerpt.
		if ( isset( $request['short_description'] ) ) {
			$quiz->set_short_description( wp_filter_post_kses( $request['short_description'] ) );
		}

		// Quiz status.
		if ( isset( $request['status'] ) ) {
			$quiz->set_status( get_post_status_object( $request['status'] ) ? $request['status'] : 'draft' );
		}

		// Quiz slug.
		if ( isset( $request['slug'] ) ) {
			$quiz->set_slug( $request['slug'] );
		}

		// Quiz parent id.
		if ( isset( $request['parent_id'] ) ) {
			$quiz->set_parent_id( $request['parent_id'] );
		}

		// Course ID.
		if ( isset( $request['course_id'] ) ) {
			$quiz->set_course_id( $request['course_id'] );
		}

		// Quiz menu order.
		if ( isset( $request['menu_order'] ) ) {
			$quiz->set_menu_order( $request['menu_order'] );
		}

		// Quiz pass mark.
		if ( isset( $request['pass_mark'] ) ) {
			$quiz->set_pass_mark( $request['pass_mark'] );
		}

		// Quiz full mark.
		if ( isset( $request['full_mark'] ) ) {
			$quiz->set_full_mark( $request['full_mark'] );
		}

		// Quiz duration.
		if ( isset( $request['duration'] ) ) {
			$quiz->set_duration( $request['duration'] );
		}

		// Allow set meta_data.
		if ( isset( $request['meta_data'] ) && is_array( $request['meta_data'] ) ) {
			foreach ( $request['meta_data'] as $meta ) {
				$quiz->update_meta_data( $meta['key'], $meta['value'], isset( $meta['id'] ) ? $meta['id'] : '' );
			}
		}

		/**
		 * Filters an object before it is inserted via the REST API.
		 *
		 * The dynamic portion of the hook name, `$this->object_type`,
		 * refers to the object type slug.
		 *
		 * @since 0.1.0
		 *
		 * @param Model         $quiz  Object object.
		 * @param WP_REST_Request $request  Request object.
		 * @param bool            $creating If is creating a new object.
		 */
		return apply_filters( "masteriyo_rest_pre_insert_{$this->object_type}_object", $quiz, $request, $creating );
	}

	/**
	 * Prepare links for the request.
	 *
	 * @since 0.1.0
	 *
	 * @param Model           $object  Object data.
	 * @param WP_REST_Request $request Request object.
	 * @return array                   Links for the given post.
	 */
	protected function prepare_links( $object, $request ) {
		$links           = parent::prepare_links( $object, $request );
		$next_prev_links = $this->get_navigation_links( $object, $request );

		return $links + $next_prev_links;
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
					'status' => 404
				)
			);
		}

		if ( $course->get_author_id() !== get_current_user_id() ) {
			return new \WP_Error(
				'masteriyo_rest_cannot_create',
				__( 'Sorry, you are not allowed to create quiz for others course.', 'masteriyo' ),
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

		$id   = absint( $request['id'] );
		$quiz = masteriyo_get_quiz( $id );

		if ( is_null( $quiz ) ) {
			return new \WP_Error(
				"masteriyo_rest_{$this->post_type}_invalid_id",
				__( 'Invalid ID.', 'masteriyo' ),
				array(
					'status' => 404
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

		$id   = absint( $request['id'] );
		$quiz = masteriyo_get_quiz( $id );

		if ( is_null( $quiz ) ) {
			return new \WP_Error(
				"masteriyo_rest_{$this->post_type}_invalid_id",
				__( 'Invalid ID.', 'masteriyo' ),
				array(
					'status' => 404
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
