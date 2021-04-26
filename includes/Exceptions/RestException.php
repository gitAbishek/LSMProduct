<?php
/**
 * Masteriyo REST Exception Class
 *
 * Extends Exception to provide additional data.
 *
 * @package ThemeGrill\Masteriyo\RestApi
 * @since   2.6.0
 */

namespace ThemeGrill\Masteriyo\Exceptions;

defined( 'ABSPATH' ) || exit;

use ThemeGrill\Masteriyo\ModelException;

/**
 * RestException class.
 */
class RestException extends ModelException {}
