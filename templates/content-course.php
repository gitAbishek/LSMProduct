<?php
/**
 * The template for displaying course content within loops
 *
 * This template can be overridden by copying it to yourtheme/masteriyo/content-course.php.
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

global $course;

// Ensure visibility.
if ( empty( $course ) || ! $course->is_visible() ) {
	return;
}
?>
<div class="mto-course-item">
	<div class="mto-course--card">
		<a href="<?php echo esc_url( $course->get_permalink() ); ?>" title="<?php esc_attr( $course->get_name() ); ?>">
			<div class="mto-course--img-wrap">
				<span class="mto-course--price-tag">
					<?php echo masteriyo_price( $course->get_price() ); ?>
				</span>

				<!-- Featured Image -->
				<?php if ( empty( $course->get_featured_image_url() ) ) : ?>
					<img class="mto-course--img" src="https://via.placeholder.com/150" alt="Course featured image">
				<?php else : ?>
					<img
						class="mto-course--img"
						src="<?php echo esc_url( $course->get_featured_image_url() ); ?>"
						alt="Course featured image"
					>
				<?php endif; ?>
			</div>
		</a>

		<div class="mto-course--header">
			<div class="mto-rt">
				<span class="mto-icon-svg mto-flex mto-rating">
					<?php masteriyo_format_rating( wp_rand( 0, 5 ) / wp_rand( 0, 5 ), true ); ?>
				</span>
				<?php foreach ( $course->get_categories( 'name' ) as $category ) : ?>
					<span class="mto-badge mto-badge-pink mto-tag">
						<?php echo esc_html( $category->get_name() ); ?>
					</span>
				<?php endforeach; ?>
			</div>

			<h2 class="mto-title">
				<?php
				printf(
					'<a href="%s" title="%s">%s</a>',
					esc_url( $course->get_permalink() ),
					esc_html( $course->get_name() ),
					esc_html( $course->get_name() )
				);
				?>
			</h2>
			<div class="mto-time-btn">
				<span class="mto-duration">
					<span class="mto-icon-svg">
						<?php masteriyo_get_svg( 'clock', true ); ?>
					</span>

					<time class="mto-inline-block mto-text-sm">10:00 min</time>
				</span>
				<a href="?add-to-cart=<?php echo absint( $course->get_id() ); ?>" class="mto-course--btn mto-btn mto-btn-primary">
					<?php apply_filters( 'masteriyo_add_to_cart_text', esc_html_e( 'Enroll Now', 'masteriyo' ) ); ?>
				</a>
			</div>
		</div>
	</div>
</div>
