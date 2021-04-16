<?php
/**
 * Register form handlers.
 *
 * @package ThemeGrill\Masetriyo\Classes\
 */

namespace ThemeGrill\Masteriyo\FormHandler;

defined( 'ABSPATH' ) || exit;


/**
 * Class Forms
 */
class FormHandlers {
	/**
	 * Undocumented variable
	 *
	 * @var FormHandler[]
	 */
	private $form_handlers;

	public function __construct() {
		$namespace = 'ThemeGrill\\Masteriyo\FormHandler';

		$this->form_handlers = apply_filters(
			'masteriyo_form_handlers',
			array(
				"{$namespace}\\RegistrationFormHandler",
			)
		);
	}

	/**
	 * Register form handlers.
	 *
	 * @return void
	 */
	public function register() {
		foreach( $this->form_handlers as $form_handler ) {
			$instance = new $form_handler;
		}
	}
}
