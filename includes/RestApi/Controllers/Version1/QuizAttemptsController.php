<?php
/**
 * Quiz attempt rest controller.
 */

namespace Masteriyo\RestApi\Controllers\Version1;

defined( 'ABSPATH' ) || exit;

use Masteriyo\Helper\Permission;
use Masteriyo\Models\QuizAttempt;
use Masteriyo\Query\QuizAttemptQuery;

class QuizAttemptsController extends CrudController {
	/**
	 * Endpoint namespace.
	 *
	 * @since 1.3.2
	 *
	 * @var string
	 */
	protected $namespace = 'masteriyo/v1';

	/**
	 * Route base.
	 *
	 * @since 1.3.2
	 *
	 * @var string
	 */
	protected $rest_base = 'quizes/attempts';

	/**
	 * If object is hierarchical.
	 *
	 * @since 1.3.2
	 *
	 * @var bool
	 */
	protected $hierarchical = true;

	/**
	 * Permission class.
	 *
	 * @since 1.3.2
	 *
	 * @var Masteriyo\Helper\Permission;
	 */
	protected $permission = null;

	/**
	 * Constructor.
	 *
	 * @since 1.3.2
	 *
	 * @param Permission $permission
	 */
	public function __construct( Permission $permission = null ) {
		$this->permission = $permission;
	}

	public function register_routes() {
		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base,
			array(
				array(
					'methods'             => \WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_items' ),
					'permission_callback' => function() {
						return is_user_logged_in() || masteriyo( 'session' )->get_user_id();
					},
					'args'                => $this->get_collection_params(),
				),
			)
		);

		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base . '/(?P<id>[\d]+)',
			array(
				'args' => array(
					'id' => array(
						'description' => __( 'Unique identifier for the resource.', 'masteriyo' ),
						'type'        => 'integer',
					),
				),
				array(
					'methods'             => \WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_item' ),
					'permission_callback' => 'is_user_logged_in',
					'args'                => array(
						'context' => $this->get_context_param(
							array(
								'default' => 'view',
							)
						),
					),
				),
			)
		);
	}

	/**
	 * Get the query params for collections of attachments.
	 *
	 * @since 1.3.2
	 *
	 * @return array
	 */
	public function get_collection_params() {
		$params = array(
			'quiz_id'  => array(
				'description'       => __( 'Quiz ID', 'masteriyo' ),
				'type'              => 'integer',
				'sanitize_callback' => 'absint',
				'validate_callback' => 'rest_validate_request_arg',
			),
			'user_id'  => array(
				'description'       => __( 'User ID', 'masteriyo' ),
				'type'              => 'integer',
				'sanitize_callback' => 'absint',
				'validate_callback' => 'rest_validate_request_arg',
			),
			'status'   => array(
				'description'       => __( 'Quiz attempt status.', 'masteriyo' ),
				'type'              => 'string',
				'sanitize_callback' => 'sanitize_title',
				'validate_callback' => 'rest_validate_request_arg',
			),
			'orderby'  => array(
				'description'       => __( 'Sort collection by object attribute.', 'masteriyo' ),
				'type'              => 'string',
				'default'           => 'id',
				'enum'              => array(
					'id',
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
			'page'     => array(
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
		);

		return $params;
	}

	/**
	 * Get object.
	 *
	 * @since 1.3.2
	 *
	 * @param  int $id Object ID.
	 * @return object Model object or WP_Error object.
	 */
	protected function get_object( $id ) {
		try {
			$id           = $id instanceof \stdClass ? $id->id : $id;
			$id           = $id instanceof QuizAttempt ? $id->get_id() : $id;
			$quiz_attempt = masteriyo( 'quiz-attempt' );
			$quiz_attempt->set_id( $id );
			$quiz_attempt_repo = masteriyo( 'quiz-attempt.store' );
			$quiz_attempt_repo->read( $quiz_attempt );
		} catch ( \Exception $e ) {
			return false;
		}

		return $quiz_attempt;
	}

	/**
	 * Get objects.
	 *
	 * @since  1.0.6
	 *
	 * @param  array $query_args Query args.
	 * @return array
	 */
	protected function get_objects( $query_args ) {
		if ( is_user_logged_in() ) {
			$result = $this->get_quiz_attempts_from_db( $query_args );
		} else {
			$result = $this->get_quiz_attempts_from_session( $query_args );
		}

		return $result;
	}

	/**
	 * Get quiz attempts from session.
	 *
	 * @since 1.3.8
	 *
	 * @param array $query_args
	 * @return array
	 */
	protected function get_quiz_attempts_from_session( $query_args ) {
		$session = masteriyo( 'session' );

		$quiz_id      = absint( $query_args['quiz_id'] );
		$all_attempts = $session->get( 'quiz_attempts', array() );
		$attempts     = isset( $all_attempts[ $quiz_id ] ) ? $all_attempts[ $quiz_id ] : array();
		$total_items  = count( $attempts );

		$attempts = array_map(
			function( $attempt ) {
				$quiz_attempt = masteriyo( 'quiz-attempt' );
				$quiz_attempt->set_id( 0 );
				$quiz_attempt->set_props( $attempt );

				return $quiz_attempt;
			},
			$attempts
		);

		return array(
			'objects' => array_reverse( $attempts ),
			'total'   => (int) $total_items,
			'pages'   => (int) ceil( $total_items / (int) $query_args['per_page'] ),
		);
	}

	/**
	 * Get quiz attempts from database.
	 *
	 * @since 1.3.8
	 *
	 * @param array $query_args
	 * @return array
	 */
	protected function get_quiz_attempts_from_db( $query_args ) {
		global $wpdb;

		$query   = new QuizAttemptQuery( $query_args );
		$objects = $query->get_quiz_attempts();

		/**
		 * Query for counting all quiz attempts rows.
		 */
		if ( ! empty( $query_args['user_id'] ) && ! empty( $query_args['quiz_id'] ) ) {
			$total_items = $wpdb->get_var(
				$wpdb->prepare(
					"SELECT COUNT(*) FROM {$wpdb->prefix}masteriyo_quiz_attempts
					WHERE user_id = %d
					AND quiz_id = %d",
					$query_args['user_id'],
					$query_args['quiz_id']
				)
			);
		} elseif ( ! empty( $query_args['user_id'] ) ) {
			$total_items = $wpdb->get_var(
				$wpdb->prepare(
					"SELECT COUNT(*) FROM {$wpdb->prefix}masteriyo_quiz_attempts
					WHERE user_id = %d",
					$query_args['user_id']
				)
			);
		} elseif ( ! empty( $query_args['quiz_id'] ) ) {
			$total_items = $wpdb->get_var(
				$wpdb->prepare(
					"SELECT COUNT(*) FROM {$wpdb->prefix}masteriyo_quiz_attempts
					WHERE quiz_id = %d",
					$query_args['quiz_id']
				)
			);
		} else {
			$total_items = $wpdb->get_var( "SELECT COUNT( * ) FROM {$wpdb->prefix}masteriyo_quiz_attempts" );
		}

		return array(
			'objects' => array_filter( array_map( array( $this, 'get_object' ), $objects ) ),
			'total'   => (int) $total_items,
			'pages'   => (int) ceil( $total_items / (int) $query_args['per_page'] ),
		);
	}

	/**
	 * Process objects collection.
	 *
	 * @since 1.3.2
	 *
	 * @param array $objects Orders data.
	 * @param array $query_args Query arguments.
	 * @param array $query_results Orders query result data.
	 *
	 * @return array
	 */
	protected function process_objects_collection( $objects, $query_args, $query_results ) {
		return array(
			'data' => $objects,
			'meta' => array(
				'total'        => $query_results['total'],
				'pages'        => $query_results['pages'],
				'current_page' => $query_args['paged'],
				'per_page'     => $query_args['per_page'],
			),
		);
	}

	/**
	 * Check permissions for an item.
	 *
	 * @since 1.3.2
	 *
	 * @param string $post_type Post type.
	 * @param string $context   Request context.
	 * @param int    $object_id Post ID.
	 *
	 * @return bool
	 */
	protected function check_item_permission( $post_type, $context = 'read', $object_id = 0 ) {
		return true;
	}

	/**
	 * Prepare objects query.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 *
	 * @since  1.0.6
	 *
	 * @return array
	 */
	protected function prepare_objects_query( $request ) {
		$args = array(
			'per_page' => $request['per_page'],
			'paged'    => $request['page'],
			'order'    => $request['order'],
			'orderby'  => $request['orderby'],
		);

		if ( isset( $request['quiz_id'] ) ) {
			$args['quiz_id'] = absint( $request['quiz_id'] );
		}

		if ( isset( $request['user_id'] ) ) {
			$args['user_id'] = absint( $request['user_id'] );
		}

		/**
		 * Filter the query arguments for a request.
		 *
		 * Enables adding extra arguments or setting defaults for a post
		 * collection request.
		 *
		 * @param array           $args    Key value array of query var to query value.
		 * @param WP_REST_Request $request The request used.
		 */
		$args = apply_filters( 'masteriyo_rest_quiz_attempts_object_query', $args, $request );

		return $args;
	}

	/**
	 * Prepares the object for the REST response.
	 *
	 * @since  1.0.6
	 * @param  Model           $object  Model object.
	 * @param  WP_REST_Request $request Request object.
	 * @return WP_Error|WP_REST_Response Response object on success, or WP_Error object on failure.
	 */
	protected function prepare_object_for_response( $object, $request ) {
		$context = ! empty( $request['context'] ) ? $request['context'] : 'view';
		$data    = $this->get_quiz_attempt_data( $object, $context );

		$data     = $this->add_additional_fields_to_object( $data, $request );
		$data     = $this->filter_response_by_context( $data, $context );
		$response = rest_ensure_response( $data );

		/**
		 * Filter the data for a response.
		 *
		 * The dynamic portion of the hook name, $this->object_type,
		 * refers to object type being prepared for the response.
		 *
		 * @since 1.3.2
		 *
		 * @param WP_REST_Response $response The response object.
		 * @param Model          $object   Object data.
		 * @param WP_REST_Request  $request  Request object.
		 */
		return apply_filters( "masteriyo_rest_prepare_{$this->object_type}_object", $response, $object, $request );
	}

	/**
	 * Get quiz attempt data.
	 *
	 * @since 1.3.2
	 *
	 * @param QuizAttempt $quiz_attempt quiz attempt instance.
	 * @param string $context Request context.
	 *                        Options: 'view' and 'edit'.
	 *
	 * @return array
	 */
	protected function get_quiz_attempt_data( $quiz_attempt, $context = 'view' ) {

		$data = array(
			'id'                       => $quiz_attempt->get_id( $context ),
			'total_questions'          => $quiz_attempt->get_total_questions( $context ),
			'total_answered_questions' => $quiz_attempt->get_total_answered_questions( $context ),
			'total_marks'              => $quiz_attempt->get_total_marks( $context ),
			'total_attempts'           => $quiz_attempt->get_total_attempts( $context ),
			'total_correct_answers'    => $quiz_attempt->get_total_correct_answers( $context ),
			'total_incorrect_answers'  => $quiz_attempt->get_total_incorrect_answers( $context ),
			'earned_marks'             => $quiz_attempt->get_earned_marks( $context ),
			'answers'                  => maybe_unserialize( $quiz_attempt->get_answers( $context ) ),
			'attempt_status'           => $quiz_attempt->get_attempt_status( $context ),
			'attempt_started_at'       => masteriyo_rest_prepare_date_response( $quiz_attempt->get_attempt_started_at( $context ) ),
			'attempt_ended_at'         => masteriyo_rest_prepare_date_response( $quiz_attempt->get_attempt_ended_at( $context ) ),
		);

		$course = masteriyo_get_course( $quiz_attempt->get_course_id( $context ) );
		$quiz   = masteriyo_get_quiz( $quiz_attempt->get_quiz_id( $context ) );
		$user   = masteriyo_get_user( $quiz_attempt->get_user_id( $context ) );

		if ( $course ) {
			$data['course'] = array(
				'id'   => $course->get_id(),
				'name' => $course->get_name(),
			);
		} else {
			$data['course'] = null;
		}

		if ( $quiz ) {
			$data['quiz'] = array(
				'id'        => $quiz->get_id(),
				'name'      => $quiz->get_name(),
				'pass_mark' => $quiz->get_pass_mark(),
			);
		} else {
			$data['quiz'] = null;
		}

		if ( ! is_null( $user ) && ! is_wp_error( $user ) ) {
			$data['user'] = array(
				'id'           => $user->get_id(),
				'display_name' => $user->get_display_name(),
				'first_name'   => $user->get_first_name(),
				'last_name'    => $user->get_last_name(),
				'email'        => $user->get_email(),
			);
		} else {
			$data['user'] = null;
		}

		return $data;
	}

}
