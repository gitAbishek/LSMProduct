<?php
/**
 * The Template for displaying course main contents like curriculum, reviews etc in single course page
 *
 * This template can be overridden by copying it to yourtheme/masteriyo/single-course/main-content.php.
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

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

do_action( 'masteriyo_before_single_course_content' );

?>
<div class="masteriyo-single-course--main__content">
	<?php do_action( 'masteriyo_single_course_content' ); ?>
</div>
<?php

do_action( 'masteriyo_after_single_course_content' );
