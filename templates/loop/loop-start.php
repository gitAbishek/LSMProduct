<?php
/**
 * Product Loop Start
 *
 * This template can be overridden by copying it to yourtheme/masteriyo/loop/loop-start.php.
 *
 * HOWEVER, on occasion Masteriyo will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.masteriyo.com/document/template-structure/
 * @package     Masteriyo\Templates
 * @version     3.3.0
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="mto-course-list mto-courses columns-<?php echo esc_attr( masteriyo_get_loop_prop( 'columns' ) ); ?> mto-flex mto-flex-wrap mto-justify-center md:mto-justify-start md:mto-flex-row">
