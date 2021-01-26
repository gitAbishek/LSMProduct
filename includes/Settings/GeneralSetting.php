<?php
/**
 * General setting.
 *
 * @since 0.1.0
 *
 * @package ThemeGrill\Masteriyo\Settings
 */

namespace ThemeGrill\Masteriyo\Settings;

defined( 'ABSPATH' ) || exit;

use ThemeGrill\Masteriyo\Settings;

/**
 * General Settings class.
 */
class GeneralSetting extends Setting {

	/**
	 * ID of the class extending the settings API. Used in option names.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	public $group = 'general';

	/**
	 * Form option fields.
	 *
	 * @since 0.1.0
	 *
	 * @var array
	 */
	public $form_fields = array();
}

