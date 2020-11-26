<?php
/**
 * Template handler class interface.
 *
 * @since 0.1.0
 *
 * @package ThemeGrill\Masteriyo
 */

namespace ThemeGrill\Masteriyo;

defined( 'ABSPATH' ) || exit;

interface TemplateInterface {
	/**
	 * Get template part.
	 *
	 * MASTERIYO_TEMPLATE_DEBUG_MODE will prevent overrides in themes from taking priority.
	 *
	 * @param mixed  $slug Template slug.
	 * @param string $name Template name (default: '').
	 */
	public static function get_template_part( $slug, $name = '' );

	/**
	 * Get other templates and include the file.
	 *
	 * @param string $template_name Template name.
	 * @param array  $args          Arguments. (default: array).
	 * @param string $template_path Template path. (default: '').
	 * @param string $default_path  Default path. (default: '').
	 */
	public static function get_template( $template_name, $args = array(), $template_path = '', $default_path = '' );

	/**
	 * Like get_template, but returns the HTML instead of outputting.
	 *
	 * @see get_template
	 * @since 0.1.0
	 * @param string $template_name Template name.
	 * @param array  $args          Arguments. (default: array).
	 * @param string $template_path Template path. (default: '').
	 * @param string $default_path  Default path. (default: '').
	 *
	 * @return string
	 */
	public static function get_template_html( $template_name, $args = array(), $template_path = '', $default_path = '' );

	/**
	 * Add a template to the template cache.
	 *
	 * @since 0.1.0
	 * @param string $cache_key Object cache key.
	 * @param string $template Located template.
	 */
	public static function set_template_cache( $cache_key, $template );

	/**
	 * Get template cache.
	 *
	 * @since 0.1.0
	 *
	 * @param string $cache_key Object cache key.
	 *
	 * @return string
	 */
	public static function get_template_cache( $cache_key );

	/**
	 * Locate a template and return the path for inclusion.
	 *
	 * This is the load order:
	 *
	 * yourtheme/$template_path/$template_name
	 * yourtheme/$template_name
	 * $default_path/$template_name
	 *
	 * @param string $template_name Template name.
	 * @param string $template_path Template path. (default: '').
	 * @param string $default_path  Default path. (default: '').
	 * @return string
	 */
	public static function locate_template( $template_name, $template_path = '', $default_path = '' );
}
