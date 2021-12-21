<?php
/**
 * Create quiz attempts table.
 *
 * @since x.x.x
 */

use Masteriyo\Database\Migration;

/**
 * Create quiz attempts table.
 */
class CreateQuizAttemptsTable extends Migration {
	/**
	 * Run the migration.
	 *
	 * @since x.x.x
	 */
	public function up() {
		$sql = "CREATE TABLE {$this->prefix}masteriyo_quiz_attempts (
			id BIGINT UNSIGNED AUTO_INCREMENT,
			course_id BIGINT UNSIGNED NOT NULL,
			quiz_id BIGINT UNSIGNED NOT NULL,
			user_id CHAR(32) NOT NULL,
			total_questions BIGINT UNSIGNED NOT NULL,
			total_answered_questions BIGINT UNSIGNED NOT NULL,
			total_marks DECIMAL(9,2) DEFAULT NULL,
			total_attempts BIGINT UNSIGNED NOT NULL,
			total_correct_answers BIGINT UNSIGNED NOT NULL,
			total_incorrect_answers BIGINT UNSIGNED NOT NULL,
			earned_marks DECIMAL(9,2) DEFAULT NULL,
			answers TEXT,
			attempt_status VARCHAR(50) DEFAULT NULL,
			attempt_started_at DATETIME DEFAULT NULL,
			attempt_ended_at DATETIME DEFAULT NULL,
			PRIMARY KEY  (id),
			KEY course_id (course_id),
			KEY quiz_id (quiz_id),
			KEY user_id (user_id),
			KEY attempt_started_at (attempt_started_at),
			KEY attempt_ended_at (attempt_ended_at)
		) $this->charset_collate;";

		dbDelta( $sql );
	}

	/**
	 * Reverse the migrations.
	 *
	 * @since x.x.x
	 */
	public function down() {
		$this->connection->query( "DROP TABLE IF EXISTS {$this->prefix}masteriyo_quiz_attempts;" );
	}
}
