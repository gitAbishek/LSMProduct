<?php
/**
 * Shortcode abstract class.
 *
 * @since 0.1.0
 * @class Shortcode
 * @package ThemeGrill\Masteriyo\Shortcodes
 */

namespace ThemeGrill\Masteriyo\Shortcodes;

defined( 'ABSPATH' ) || exit;

/**
 * Shortcode abstract class.
 */
abstract class Shortcode {

	/**
	 * Shortcode tag.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected $tag = '';

	/**
	 * Shortcode attributes with default values.
	 *
	 * @since 0.1.0
	 *
	 * @var array
	 */
	protected $attributes = array();

	/**
	 * Get shortcode attributes.
	 *
	 * @since 0.1.0
	 *
	 * @return array
	 */
	public function get_attributes() {
		return $this->attributes;
	}

	/**
	 * Set shortcode attributes.
	 *
	 * @since 0.1.0
	 *
	 * @return $this
	 */
	public function set_attributes( $attributes ) {
		$this->attributes = $this->parse_attributes( $attributes );
		return $this;
	}

	/**
	 * Get shortcode tag.
	 *
	 * @since 0.1.0
	 *
	 * @return string
	 */
	public function get_tag() {
		return $this->tag;
	}

	/**
	 * Parse shortcode attributes.
	 *
	 * @since 0.1.0
	 *
	 * @param array $attributes Shortcode attributes.
	 *
	 * @return array
	 */
	protected function parse_attributes( $attributes ) {
		return shortcode_atts(
			$this->get_attributes(),
			$attributes,
			$this->get_tag()
		);
	}

	/**
	 * Register this shortcode.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	public function register() {
		add_shortcode( $this->get_tag(), array( $this, 'shortcode_callback' ) );
	}

	/**
	 * Shortcode callback.
	 *
	 * @since 0.1.0
	 *
	 * @param array $attributes
	 *
	 * @return string
	 */
	public function shortcode_callback( $attributes = array() ) {
		return $this->set_attributes( $attributes )->get_content();
	}

	/**
	 * Get shortcode content.
	 *
	 * @since 0.1.0
	 *
	 * @return string
	 */
	abstract public function get_content();

	/**
	 * Get rendered html after injecting the data.
	 *
	 * @since 0.1.0
	 *
	 * @param array $data Data to inject.
	 * @param string $file_path Path of the php file containing HTML.
	 *
	 * @return string
	 */
	protected function get_rendered_html( $data, $file_path ) {
		ob_start();
		extract( $data ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract
		include $file_path;
		return ob_get_clean();
	}
}
