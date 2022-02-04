<?php
/**
 * Notification table migration class.
 *
 * @since x.x.x
 */

use Masteriyo\Database\Migration;

class CreateNotificationsTable extends Migration {
	/**
	 * Run the migration.
	 *
	 * @since x.x.x
	 */
	public function up() {
		$sql = "CREATE TABLE {$this->prefix}masteriyo_notifications (
			id BIGINT UNSIGNED AUTO_INCREMENT,
			user_id BIGINT UNSIGNED DEFAULT 0,
			created_by BIGINT UNSIGNED DEFAULT 0,
			title TEXT DEFAULT '',
			description LONGTEXT DEFAULT '',
			code VARCHAR(50) DEFAULT '',
			status VARCHAR(50) DEFAULT '',
			type VARCHAR(50) DEFAULT '',
			level VARCHAR(50) DEFAULT '',
			action_ok TEXT DEFAULT '',
			action_cancel TEXT DEFAULT '',
			action_1 TEXT DEFAULT '',
			action_2 TEXT DEFAULT '',
			action_3 TEXT DEFAULT '',
			created_at DATETIME DEFAULT NULL,
			modified_at DATETIME DEFAULT NULL,
			expire_at DATETIME DEFAULT NULL,
			PRIMARY KEY  (id),
			KEY code (code),
			KEY status (status),
			KEY type (type),
			KEY level (level),
			KEY user_id (user_id),
			KEY created_at (created_at),
			KEY modified_at (modified_at),
			KEY expire_at (expire_at)
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
