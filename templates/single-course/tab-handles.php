<?php
/**
 * Tab handles used in the main content of single course.
 *
 * @version 0.1.0
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

global $course;

?>

<div class="tab-menu mto-stab">
	<div id="tab1" class="active-tab" onClick="JavaScript:masteriyo_select_single_course_page_tab(1);"><?php echo esc_html__( 'Overview', 'masteriyo' ); ?></div>
	<div id="tab2" onClick="JavaScript:masteriyo_select_single_course_page_tab(2);"><?php echo esc_html__( 'Curriculum', 'masteriyo' ); ?></div>
	<?php if ( masteriyo_course_has_faqs( $course->get_id() ) ): ?>
		<div id="tab3" onClick="JavaScript:masteriyo_select_single_course_page_tab(3);"><?php echo esc_html__( 'FAQ', 'masteriyo' ); ?></div>
	<?php endif; ?>
	<div id="tab4" onClick="JavaScript:masteriyo_select_single_course_page_tab(4);"><?php echo esc_html__( 'Reviews', 'masteriyo' ); ?></div>
</div>

<?php
