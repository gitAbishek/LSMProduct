<?php
/**
 * Create the application.
 *
 * @since 0.1.0
 */

/**
 * Create an instance of the application.
 */
$masteriyo = new ThemeGrill\Masteriyo\Masteriyo();

/**
 * Enable the auto wiring.
 */
$masteriyo->delegate(
	new League\Container\ReflectionContainer()
);

return $masteriyo;
