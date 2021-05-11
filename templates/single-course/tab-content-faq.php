<?php

/**
 * Tab content - FAQ.
 *
 * @version 0.1.0
 */

defined('ABSPATH') || exit; // Exit if accessed directly.

/**
 * masteriyo_before_single_course_faq_content hook.
 */
do_action('masteriyo_before_single_course_faq_content');

?>

<div class="mto-faq-accordion mto-relative">
	<?php foreach( $faqs as $faq ): ?>
		<div class="mto-faq-accordion-item">
			<div class="mto-faq-accordion-item-header">
				<?php echo esc_html( $faq->get_name() ); ?>
			</div>
			<div class="mto-faq-accordion-item-body">
				<div class="mto-faq-accordion-item-body-content">
					<p>
						<?php echo esc_html( $faq->get_description() ); ?>
					</p>
				</div>
			</div>
		</div>
	<?php endforeach; ?>
</div>

<?php

/**
 * masteriyo_after_single_course_faq_content hook.
 */
do_action('masteriyo_after_single_course_faq_content');
