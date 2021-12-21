<?php
/**
 * Handles cli command initialization.
 *
 * @since 1.3.1
 * @package Masteriyo\Cli
 */

namespace Masteriyo\Cli;

class Cli {

	/**
	 * Namespace.
	 *
	 * @since 1.3.1
	 *
	 * @var string
	 */
	public static $namespace = 'Masteriyo\\Cli';

	/**
	 * Register CLI commands.
	 *
	 * @return void
	 */
	public static function register() {
		$commands = apply_filters(
			'masteriyo_cli_commands',
			array(
				'migration' => self::$namespace . '\\Migration',
			)
		);

		foreach ( $commands as $command => $class ) {
			\WP_CLI::add_command( "masteriyo {$command}", $class );
		}
	}
}
