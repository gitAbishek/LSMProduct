<?php
/**
 * Course's feature image.
 *
 * @version 0.1.0
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

global $course;
global $course_featured_image_url;

?>

<div class="mto-relative aspect-w-16 aspect-h-9">
	<img class="mto-h-480 mto-w-full" src="<?php echo $course_featured_image_url; ?>" alt="Developing your first impressive portfolio">
	<span class="mto-absolute mto-top-0 mto-left-0 mto-text-white mto-text-lg mto-font-medium mto-bg-primary mto-px-4 mto-py-2 mto-rounded-br-2xl">
		$<?php echo $course->get_price(); ?>
	</span>
	<a href="#">
		<span class="mto-transform hover:mto-scale-90 mto-absolute mto-right-0 mto-top-0 mto-bg-white mto-p-1.5 mto-m-4 mto-rounded-full">
			<svg class="mto-w-6 mto-h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
				<path d="M12 4.595a5.904 5.904 0 00-3.996-1.558 5.942 5.942 0 00-4.213 1.758c-2.353 2.363-2.352 6.059.002 8.412l7.332 7.332c.17.299.498.492.875.492a.99.99 0 00.792-.409l7.415-7.415c2.354-2.354 2.354-6.049-.002-8.416a5.938 5.938 0 00-4.209-1.754A5.906 5.906 0 0012 4.595zm6.791 1.61c1.563 1.571 1.564 4.025.002 5.588L12 18.586l-6.793-6.793c-1.562-1.563-1.561-4.017-.002-5.584.76-.756 1.754-1.172 2.799-1.172s2.035.416 2.789 1.17l.5.5a.999.999 0 001.414 0l.5-.5c1.512-1.509 4.074-1.505 5.584-.002z"/>
			  </svg>
		</span>
	</a>
</div>

<?php
