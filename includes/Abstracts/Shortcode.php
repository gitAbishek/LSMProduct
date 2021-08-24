<?php
/**
 * Shortcode abstract class.
 *
 * @since 0.1.0
 * @class Shortcode
 * @package Masteriyo\Abstracts
 */

namespace Masteriyo\Abstracts;

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
	 * Arguments to pass to the template.
	 *
	 * @since 0.1.0
	 *
	 * @var array
	 */
	protected $template_args = array();

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
	}

	/**
	 * Set template args.
	 *
	 * @since 0.1.0
	 *
	 * @return
	 */
	public function set_template_args( $template_args ) {
		$this->attributes = (array) $template_args;
	}

	/**
	 * Get template args.
	 *
	 * @since 0.1.0
	 *
	 * @return array
	 */
	public function get_template_args() {
		return $this->template_args;
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
		$this->set_attributes( $attributes );
		return $this->get_content();
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
