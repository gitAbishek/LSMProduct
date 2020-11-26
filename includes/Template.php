<?php
/**
 * Template functions wrapper class.
 *
 * @package ThemeGrill\Masteriyo
 *
 * @since 0.1.0
 */

namespace ThemeGrill\Masteriyo;

use ThemeGrill\Masteriyo\Helper\Utils;

defined( 'ABSPATH' ) || exit;

/**
 * Template functions wrapper class.
 */
class Template {
	/**
	 * Get template part.
	 *
	 * MASTERIYO_TEMPLATE_DEBUG_MODE will prevent overrides in themes from taking priority.
	 *
	 * @param mixed  $slug Template slug.
	 * @param string $name Template name (default: '').
	 */
	public static function get_template_part( $slug, $name = '' ) {
		$cache_key = sanitize_key( implode( '-', array( 'template-part', $slug, $name, Constants::get_constant( 'MASTERIYO_VERSION' ) ) ) );
		$template  = self::get_template_cache( $cache_key );

		if ( ! $template ) {
			if ( $name ) {
				$template = Constants::get_constant( 'MASTERIYO_TEMPLATE_DEBUG_MODE' ) ? '' : locate_template(
					array(
						"{$slug}-{$name}.php",
						Utils::template_path() . "{$slug}-{$name}.php",
					)
				);

				if ( ! $template ) {
					$fallback = MSYO()->plugin_path() . "/templates/{$slug}-{$name}.php";
					$template = file_exists( $fallback ) ? $fallback : '';
				}
			}

			if ( ! $template ) {
				// If template file doesn't exist, look in yourtheme/slug.php and yourtheme/masteriyo/slug.php.
				$template = Constants::get_constant( 'MASTERIYO_TEMPLATE_DEBUG_MODE' ) ? '' : locate_template(
					array(
						"{$slug}.php",
						Utils::template_path() . "{$slug}.php",
					)
				);
			}

			// Don't cache the absolute path so that it can be shared between web servers with different paths.
			$tokenized_template_path = Utils::tokenize_path( $template, Utils::get_path_define_tokens() );

			self::set_template_cache( $cache_key, $tokenized_template_path );
		} else {
			// Make sure that the absolute path to the template is resolved.
			$template = Utils::untokenize_path( $template, Utils::get_path_define_tokens() );
		}

		// Allow 3rd party plugins to filter template file from their plugin.
		$template = apply_filters( 'masteriyo_get_template_part', $template, $slug, $name );

		if ( $template ) {
			load_template( $template, false );
		}
	}

	/**
	 * Get other templates and include the file.
	 *
	 * @param string $template_name Template name.
	 * @param array  $args          Arguments. (default: array).
	 * @param string $template_path Template path. (default: '').
	 * @param string $default_path  Default path. (default: '').
	 */
	public static function get_template( $template_name, $args = array(), $template_path = '', $default_path = '' ) {
		$cache_key = sanitize_key( implode( '-', array( 'template', $template_name, $template_path, $default_path, Constants::get_constant( 'MASTERIYO_VERSION' ) ) ) );
		$template  = self::get_template_cache( $cache_key );

		if ( ! $template ) {
			$template = self::locate_template( $template_name, $template_path, $default_path );

			// Don't cache the absolute path so that it can be shared between web servers with different paths.
			$tokenized_template_path = Utils::tokenize_path( $template, Utils::get_path_define_tokens() );

			self::set_template_cache( $cache_key, $tokenized_template_path );
		} else {
			// Make sure that the absolute path to the template is resolved.
			$template = Utils::untokenize_path( $template, Utils::get_path_define_tokens() );
		}

		// Allow 3rd party plugin filter template file from their plugin.
		$filter_template = apply_filters( 'masteriyo_get_template', $template, $template_name, $args, $template_path, $default_path );

		if ( $filter_template !== $template ) {
			if ( ! file_exists( $filter_template ) ) {
				/* translators: %s template */
				Utils::doing_it_wrong( __FUNCTION__, sprintf( __( '%s does not exist.', 'masteriyo' ), '<code>' . $filter_template . '</code>' ), '0.1.0' );
				return;
			}
			$template = $filter_template;
		}

		$action_args = array(
			'template_name' => $template_name,
			'template_path' => $template_path,
			'located'       => $template,
			'args'          => $args,
		);

		if ( ! empty( $args ) && is_array( $args ) ) {
			if ( isset( $args['action_args'] ) ) {
				Utils::doing_it_wrong(
					__FUNCTION__,
					__( 'action_args should not be overwritten when calling get_template.', 'masteriyo' ),
					'0.1.0'
				);
				unset( $args['action_args'] );
			}
			extract( $args ); // @codingStandardsIgnoreLine
		}

		do_action( 'masteriyo_before_template_part', $action_args['template_name'], $action_args['template_path'], $action_args['located'], $action_args['args'] );

		include $action_args['located'];

		do_action( 'masteriyo_after_template_part', $action_args['template_name'], $action_args['template_path'], $action_args['located'], $action_args['args'] );
	}

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
	public static function get_template_html( $template_name, $args = array(), $template_path = '', $default_path = '' ) {
		ob_start();
		self::get_template( $template_name, $args, $template_path, $default_path );
		return ob_get_clean();
	}

	/**
	 * Add a template to the template cache.
	 *
	 * @since 0.1.0
	 * @param string $cache_key Object cache key.
	 * @param string $template Located template.
	 */
	public static function set_template_cache( $cache_key, $template ) {
		global $masteriyo_container;
		$cache = $masteriyo_container->get( \ThemeGrill\Masteriyo\Cache\CacheInterface::class );

		$cache->set( $cache_key, $template, 'masteriyo' );

		$cached_templates = $cache->get( 'cached_templates', 'masteriyo' );

		if ( is_array( $cached_templates ) ) {
			$cached_templates[] = $cache_key;
		} else {
			$cached_templates = array( $cache_key );
		}

		$cache->set( 'cached_templates', $cached_templates, 'masteriyo' );
	}

	/**
	 * Get template cache.
	 *
	 * @since 0.1.0
	 *
	 * @param string $cache_key Object cache key.
	 *
	 * @return string
	 */
	public static function get_template_cache( $cache_key ) {
		global $masteriyo_container;
		$cache = $masteriyo_container->get( \ThemeGrill\Masteriyo\Cache\CacheInterface::class );

		return (string) $cache->get( $cache_key, 'masteriyo' );
	}

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
	public static function locate_template( $template_name, $template_path = '', $default_path = '' ) {
		if ( ! $template_path ) {
			$template_path = Utils::template_path();
		}

		if ( ! $default_path ) {
			$default_path = MSYO()->plugin_path() . '/templates/';
		}

		// Look within passed path within the theme - this is priority.
		$template = locate_template(
			array(
				trailingslashit( $template_path ) . $template_name,
				$template_name,
			)
		);

		// Get default template/.
		if ( ! $template || Constants::get_constant( 'MASTERIYO_TEMPLATE_DEBUG_MODE' ) ) {
			$template = $default_path . $template_name;
		}

		// Return what we found.
		return apply_filters( 'masteriyo_locate_template', $template, $template_name, $template_path );
	}
}
