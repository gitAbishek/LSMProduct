<?php

/**
 * The Template for displaying course archives, including the main course_list page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/masteriyo/archive-course.php.
 *
 * HOWEVER, on occasion Masteriyo will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @package Masteriyo\Templates
 * @version 0.1.0
 */

defined( 'ABSPATH' ) || exit;

get_header( 'course-list' );

/**
 * Wrapper div opening.
 *
 * @since 0.1.0
 */
echo '<div>';

/**
 * Hook: masteriyo_before_main_content.
 *
 * @hooked masteriyo_output_content_wrapper - 10 (outputs opening divs for the content)
 * @hooked masteriyo_breadcrumb - 20
 * @hooked MASTERIYO_Structured_Data::generate_website_data() - 30
 */
do_action( 'masteriyo_before_main_content' );

?>
<header class="masteriyo-courses-header">
	<?php if ( apply_filters( 'masteriyo_show_page_title', true ) ) : ?>
		<h1 class="masteriyo-course-list-header__title page-title">
			<?php masteriyo_page_title(); ?>
		</h1>
	<?php endif; ?>

	<?php
	do_action( 'masteriyo_archive_description' );
	?>
</header>

<?php
/**
 * Hook: masteriyo_after_archive_header.
 *
 * @hooked masteriyo_course_search_form - 10
 */
do_action( 'masteriyo_after_archive_header' );
?>

<?php
if ( masteriyo_course_loop() ) {

	do_action( 'masteriyo_before_course_list_loop' );

	masteriyo_course_loop_start();

	if ( masteriyo_get_loop_prop( 'total' ) ) {
		while ( have_posts() ) {
			the_post();

			/**
			 * Hook: masteriyo_course_list_loop.
			 */
			do_action( 'masteriyo_course_list_loop' );

			\masteriyo_get_template_part( 'content', 'course' );
		}
	}

	masteriyo_course_loop_end();

	/**
	 * Hook: masteriyo_after_course_list_loop.
	 *
	 * @hooked masteriyo_pagination - 10
	 */
	do_action( 'masteriyo_after_course_list_loop' );
} else {
	/**
	 * Hook: masteriyo_no_courses_found.
	 *
	 * @hooked masteriyo_no_courses_found - 10
	 */
	do_action( 'masteriyo_no_courses_found' );
}

/**
 * Hook: masteriyo_after_main_content.
 *
 * @hooked masteriyo_output_content_wrapper_end - 10 (outputs closing divs for the content)
 */
do_action( 'masteriyo_after_main_content' );

/**
 * Hook: masteriyo_sidebar.
 *
 * @hooked masteriyo_get_sidebar - 10
 */
do_action( 'masteriyo_sidebar' );

/**
 * Wrapper div closing.
 *
 * @since 0.1.0
 */
echo '</div>';

get_footer( 'course-list' );
