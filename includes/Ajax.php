<?php
/**
 * Ajax.
 *
 * @package ThemeGrill\Masteriyo
 *
 * @since 0.1.0
 */

namespace ThemeGrill\Masteriyo;

defined( 'ABSPATH' ) || exit;

/**
 * Aajx class.
 *
 * @class ThemeGrill\Masteriyo\Ajax
 */

class Ajax {

	/**
	 * Actions.
	 *
	 * @static
	 * @since 0.1.0
	 *
	 * @var array
	 */
	private static $actions = array();

	/**
	 * Initialize
	 *
	 * @static
	 * @since 0.1.0
	 */
	public static function init() {
		self::init_hooks();
	}

	/**
	 * Initialize hooks.
	 *
	 * @static
	 * @since 0.1.0
	 *
	 * @return void
	 */
	private static function init_hooks() {
		self::$actions = apply_filters( 'masteriyo_ajax_actions', array(
			'test' => array(
				'priv'   => array( __CLASS__, 'test' ),
				'nopriv' => array( __CLASS__, 'test' )
			)
		) );

		foreach ( self::$actions as $key => $action ) {
			foreach ( $action as $type => $callback ) {
				$type = 'priv' === $type ? '' : '_nopriv';
				$slug = MASTERIYO_SLUG;
				add_action( "wp_ajax{$type}_{$slug}_{$key}", $callback );
			}
		}
	}

	/**
	 * Test ajax function.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	public static function test() {
		global $masteriyo_container;
		$course = $masteriyo_container->get( 'course' );
		// $course->set_id( 40);
		// $course->delete( array(
		// 	'force_delete' => true
		// ));

		$course->set_id( 47 );
		$course_repo = $masteriyo_container->get( \ThemeGrill\Masteriyo\Repository\CourseRepository::class );
		$course_repo->read( $course );
		$course->set_featured_image(19);
		$course->save();


		// $course->set_id( 47 );
		// $course->set_name( 'Python development course' );
		// $course->set_featured_image( 11 );
		// $course->add_meta( 'city', 'biratnagar' );
		// $course->set_category_ids( array(1, 2, 3) );
		// $course->save();

		// $name = $course->get_name();
		// $city = $course->get_meta( 'city' );
		// $category_ids = $course->get_category_ids();
		// $price = $course->get_meta( 'price', false );
		// $phone_number = $course->get_meta( 'phone_numbers' );
		// $country = $course->add_meta( 'country', 'Nepal' );
		// $course->set_price( 101.4654 );
		// $course->save();
		$a = 1;
	}
}

Ajax::init();
