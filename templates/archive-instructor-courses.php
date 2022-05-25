<?php
/**
 * The Template for displaying instructor archive page.
 *
 * This template can be overridden by copying it to yourtheme/masteriyo/instructor-courses.php.
 *
 * HOWEVER, on occasion Masteriyo will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @package Masteriyo\Templates
 * @version 1.0.0
 */

get_header( 'instructor-courses' );

/**
 * Wrapper div opening.
 *
 * @since 1.4.3
 */
echo '<div class="masteriyo-w-100 masteriyo-container">';

/**
 * Hook: masteriyo_before_instructor_archive_header.
 *
 * @since 1.4.3
 */
do_action( 'masteriyo_before_instructor_archive_header' );

?>
<header class="masteriyo-courses-header">
	<?php
	/**
	 * Filters boolean: true if page title should be shown.
	 *
	 * @since 1.0.0
	 *
	 * @param boolean $bool true if page title should be shown.
	 */
	if ( apply_filters( 'masteriyo_show_page_title', true ) ) :
		?>
		<h1 class="masteriyo-courses-header__title page-title">
			<?php masteriyo_page_title(); ?>
		</h1>
	<?php endif; ?>

	<?php
	/**
	 * Hook: masteriyo_instructor_archive_description.
	 *
	 * @since 1.4.3
	 */
	do_action( 'masteriyo_instructor_archive_description' );
	?>
</header>

<?php
/**
 * Hook: masteriyo_after_instructor_archive_header.
 *
 * @since 1.4.3
 */
do_action( 'masteriyo_after_instructor_archive_header' );
?>

<?php
if ( masteriyo_course_loop() ) {

	/**
	 * Hook: masteriyo_before_instructor_archive_loop.
	 *
	 * @since 1.4.3
	 */
	do_action( 'masteriyo_before_instructor_archive_loop' );

	masteriyo_course_loop_start();

	if ( masteriyo_get_loop_prop( 'total' ) ) {
		while ( have_posts() ) {
			the_post();

			/**
			 * Hook: masteriyo_instructor_archive_loop.
			 *
			 * @since 1.4.3
			 */
			do_action( 'masteriyo_instructor_archive_loop' );

			\masteriyo_get_template_part( 'content', 'course' );
		}
	}

	masteriyo_course_loop_end();

	/**
	 * Hook: masteriyo_after_instructor_archive_loop.
	 *
	 * @hooked masteriyo_pagination - 10
	 *
	 * @since 1.4.3
	 */
	do_action( 'masteriyo_after_instructor_archive_loop' );
} else {
	/**
	 * Hook: masteriyo_no_courses_found.
	 *
	 * @since 1.4.3
	 */
	do_action( 'masteriyo_no_courses_found' );
}

/**
 * Hook: masteriyo_after_instructor_archive_main_content.
 *
 * @since 1.4.3
 */
do_action( 'masteriyo_after_instructor_archive_main_content' );

/**
 * Wrapper div closing.
 *
 * @since 1.4.3
 */
echo '</div>';

get_footer( 'instructor-courses' );
