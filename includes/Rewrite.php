<?php
/**
 * Rewrite class.
 *
 * @package Masteriyo
 *
 * @since 1.4.3
 */

namespace Masteriyo;

defined( 'ABSPATH' ) || exit;

class Rewrite {

	/**
	 * Initialize rewrite handlers.
	 *
	 * @since 1.4.3
	 */
	public function init() {
		$this->init_hooks();
	}

	/**
	 * Initialize hooks.
	 *
	 * @since 1.4.3
	 */
	protected function init_hooks() {
		add_action( 'init', array( $this, 'add_rewrite_tags' ), 20 );
		add_action( 'init', array( $this, 'rewrite_overrides' ), 4 );
		add_action( 'init', array( $this, 'add_course_name_endpoint' ), 0 );
		add_filter( 'author_link', array( $this, 'author_link' ), 20, 2 );
	}

	/**
	 * Add course name endpoint to be used in learn page.
	 *
	 * @since 1.4.3
	 */
	public function add_course_name_endpoint() {
		add_rewrite_endpoint( 'course', EP_PAGES | EP_ROOT, 'course_name' );
	}

	/**
	 * Add WP rewrite tag for author slug.
	 *
	 * @since 1.4.3
	 */
	public function add_rewrite_tags() {
		add_rewrite_tag( '%masteriyo_author_role%', '(instructor|author)' );
	}

	/**
	 * Override WP rewrite for author slug.
	 *
	 * @since 1.4.3
	 */
	public function rewrite_overrides() {
		$GLOBALS['wp_rewrite']->author_base = '%masteriyo_author_role%';
	}

	/**
	 * Add 'instructor' to author slug.
	 *
	 * @since 1.4.3
	 *
	 * @param string $link    The author link with user role as author base.
	 * @param int    $user_id The user id.
	 */
	public function author_link( $link = '', $user_id = 0 ) {
		if ( false !== strpos( $link, '%masteriyo_author_role%' ) ) {
			$user = get_userdata( $user_id );
			$slug = 'author';

			if ( in_array( 'masteriyo_instructor', (array) $user->roles, true ) ) {
				$slug = 'instructor';
			}

			$link = str_replace( '%masteriyo_author_role%', $slug, $link );
		}
		return $link;
	}
}
