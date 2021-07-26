<?php
/**
 * Initialize form handlers.
 *
 * @package ThemeGrill\Masetriyo\Classes\
 */

namespace ThemeGrill\Masteriyo\FormHandler;

defined( 'ABSPATH' ) || exit;


/**
 * Form Handlers class.
 */
class FormHandlers {
	/**
	 * List of form handlers.
	 *
	 * @since 0.1.0
	 *
	 * @var FormHandler[]
	 */
	public static $form_handlers;

	/**
	 * Initialize the form handlers.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	public static function init() {
		$namespace = 'ThemeGrill\\Masteriyo\FormHandler';

		self::$form_handlers = apply_filters(
			'masteriyo_form_handlers',
			array(
				"{$namespace}\\RegistrationFormHandler",
				"{$namespace}\\RequestPasswordResetFormHandler",
				"{$namespace}\\PasswordResetFormHandler",
				"{$namespace}\\ChangePasswordFormHandler",
				"{$namespace}\\AddToCartFormHandler",
				"{$namespace}\\CheckoutFormHandler",
			)
		);

		foreach ( self::$form_handlers as $form_handler ) {
			$instance = new $form_handler();
		}
	}
}
