<?php
/**
 * The Template for displaying single course.
 *
 * @version 0.1.0
 */

defined( 'ABSPATH' ) || exit;

global $course;

/**
 * masteriyo_before_single_course_content hook.
 */
do_action( 'masteriyo_before_single_course_content' );

?>

<div id="course-<?php the_ID(); ?>" class="mto-scourse">
    <div class="mto-scourse--main">
        <?php masteriyo_get_template( 'single-course/main-content.php' ); ?>
    </div>
    
    <aside class="mto-scourse--aside">
        <div class="mto-sticky mto-scourse--aside-wrap">
            <?php masteriyo_get_template( 'single-course/sidebar-content.php' ); ?>
        </div>
    </aside>
</div>

<?php
/**
 * masteriyo_after_single_course_content hook.
 */
do_action( 'masteriyo_after_single_course_content' );

