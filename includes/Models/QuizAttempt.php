<?php
/**
 * Quiz attempt model.
 *
 * @since 0.1.0
 *
 * @package ThemeGrill\Masteriyo\Models;
 */

namespace ThemeGrill\Masteriyo\Models;

use ThemeGrill\Masteriyo\Database\Model;
use ThemeGrill\Masteriyo\Repository\QuizAttemptRepository;
use ThemeGrill\Masteriyo\Helper\Utils;
use ThemeGrill\Masteriyo\Cache\CacheInterface;

defined( 'ABSPATH' ) || exit;

class QuizAttempt extends Model {

	/**
	 * This is the name of this object type.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected $object_type = 'quiz-attempt';

	/**
	 * Cache group.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected $cache_group = 'quiz-attempt';

	/**
	 * Stores quiz data.
	 *
	 * @since 0.1.0
	 *
	 * @var array
	 */
	protected $data = array(
		'course_id'                => 0,
		'quiz_id'                  => 0,
		'user_id'                  => 0,
		'total_questions'          => 0,
		'total_answered_questions' => null,
		'total_marks'              => null,
		'earned_marks'             => null,
		'info'                     => null,
		'status'                   => '',
		'started_at'               => null,
		'ended_at'                 => null,
	);

	/**
	 * Getters.
	 */

	/**
	 * Constructor.
	 *
	 * @since 0.1.0
	 *
	 * @param QuizAttemptRepository $quiz_attempt_repository Quiz Attempt Repository,
	 */
	public function __construct( QuizAttemptRepository $quiz_attempt_repository ) {
		$this->repository = $quiz_attempt_repository;
	}

	/**
	 * Get course id.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 *
	 * @return int
	 */
	public function get_course_id( $context = 'view' ) {
		return $this->get_prop( 'course_id', $context );
	}

	/**
	 * Get quiz id.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 *
	 * @return int
	 */
	public function get_quiz_id( $context = 'view' ) {
		return $this->get_prop( 'quiz_id', $context );
	}

	/**
	 * Get user id.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 *
	 * @return int
	 */
	public function get_user_id( $context = 'view' ) {
		return $this->get_prop( 'user_id', $context );
	}

	/**
	 * Get quiz total questions.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return int
	 */
	public function get_total_questions( $context = 'view' ) {
		return $this->get_prop( 'total_questions', $context );
	}

	/**
	 * Get quiz total answered questions.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return int
	 */
	public function get_total_answered_questions( $context = 'view' ) {
		return $this->get_prop( 'total_answered_questions', $context );
	}

	/**
	 * Get quiz total marks.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_total_marks( $context = 'view' ) {
		return $this->get_prop( 'total_marks', $context );
	}

	/**
	 * Get quiz earned marks by user.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_earned_marks( $context = 'view' ) {
		return $this->get_prop( 'earned_marks', $context );
	}

	/**
	 * Get quiz info.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_info( $context = 'view' ) {
		return $this->get_prop( 'info', $context );
	}

	/**
	 * Get quiz attempt status.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_status( $context = 'view' ) {
		return $this->get_prop( 'status', $context );
	}

	/**
	 * Get quiz attempt started time.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_started_at( $context = 'view' ) {
		return $this->get_prop( 'started_at', $context );
	}

	/**
	 * Returns quiz attempt ended time.
	 *
	 * @since 0.1.0
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_ended_at( $context = 'view' ) {
		return $this->get_prop( 'ended_at', $context );
	}

	/**
	 * Setters.
	 */

	/**
	 * Set course id.
	 *
	 * @since 0.1.0
	 *
	 * @param int $course_id course id.
	 */
	public function set_course_id( $course_id ) {
		$this->set_prop( 'course_id', absint( $course_id ) );
	}

	/**
	 * Set quiz id.
	 *
	 * @since 0.1.0
	 *
	 * @param int $quiz_id quiz id.
	 */
	public function set_quiz_id( $quiz_id ) {
		$this->set_prop( 'quiz_id', absint( $quiz_id ) );
	}

	/**
	 * Set user id.
	 *
	 * @since 0.1.0
	 *
	 * @param int $user_id user id.
	 */
	public function set_user_id( $user_id ) {
		$this->set_prop( 'user_id', absint( $user_id ) );
	}

	/**
	 * Set quiz total questions.
	 *
	 * @since 0.1.0
	 *
	 * @param int $total_questions quiz total questions.
	 */
	public function set_total_questions( $total_questions ) {
		$this->set_prop( 'total_questions', absint( $total_questions ) );
	}

	/**
	 * Set quiz total answered questions.
	 *
	 * @since 0.1.0
	 *
	 * @param int $total_answered_questions quiz total answered questions.
	 */
	public function set_total_answered_questions( $total_answered_questions ) {
		$this->set_prop( 'total_answered_questions', absint( $total_answered_questions ) );
	}

	/**
	 * Set quiz total marks.
	 *
	 * @since 0.1.0
	 *
	 * @param string $total_marks quiz total marks.
	 */
	public function set_total_marks( $total_marks ) {
		$this->set_prop( 'total_marks', $total_marks );
	}

	/**
	 * Set quiz earned marks.
	 *
	 * @since 0.1.0
	 *
	 * @param string $earned_marks quiz earned marks.
	 */
	public function set_earned_marks( $earned_marks ) {
		$this->set_prop( 'earned_marks', $earned_marks );
	}

	/**
	 * Set quiz info.
	 *
	 * @since 0.1.0
	 *
	 * @param string $info quiz info.
	 */
	public function set_info( $info ) {
		$this->set_prop( 'info', $info );
	}

	/**
	 * Set quiz attempt status.
	 *
	 * @since 0.1.0
	 *
	 * @param string $status Quiz status.
	 */
	public function set_status( $status ) {
		$this->set_prop( 'status', $status );
	}

	/**
	 * Set quiz attempt started time.
	 *
	 * @since 0.1.0
	 *
	 * @param string|integer|null $date UTC timestamp, or ISO 8601 DateTime. If the DateTime string has no timezone or offset, WordPress site timezone will be assumed. Null if their is no date.
	 */
	public function set_started_at( $date = null ) {
		$this->set_prop( 'started_at', $date );
	}

	/**
	 * Set the quiz attempt ended time.
	 *
	 * @since 0.1.0
	 *
	 * @param string|integer|null $date UTC timestamp, or ISO 8601 DateTime. If the DateTime string has no timezone or offset, WordPress site timezone will be assumed. Null if their is no date.
	 */
	public function set_ended_at( $date = null ) {
		$this->set_prop( 'ended_at', $date );
	}

}
