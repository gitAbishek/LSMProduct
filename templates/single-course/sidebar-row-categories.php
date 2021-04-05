<?php
/**
 * Sidebar row - Categories.
 *
 * @version 0.1.0
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

?>

<div class="mto-py-4 mto-border-b mto-border-gray-200">

<?php foreach ( $categories as $index => $category ): ?>
	<?php if ( ($index + 1) % 2 === 1 ) : ?>
		<!-- Red Badge -->
		<a href="<?php echo esc_url( $category->get_permalink() ); ?>"
			class="mto-inline-block md:mto-block lg:mto-inline-block mto-bg-secondary mto-rounded-full mto-mb-3 lg:mto-mb-0 mto-px-4 mto-py-1 mto-text-xs mto-uppercase mto-font-medium mto-text-white mto-ml-2">
			<?php echo esc_html( $category->get_name() ); ?>
		</a>
	<?php else : ?>
		<!-- Green Badge -->
		<a href="<?php echo esc_url( $category->get_permalink() ); ?>"
			class="mto-inline-block md:mto-block lg:mto-inline-block mto-bg-green-400 mto-rounded-full mto-px-4 mto-py-1 mto-text-xs mto-uppercase mto-font-medium mto-text-white mto-ml-2">
			<?php echo esc_html( $category->get_name() ); ?>
		</a>
	<?php endif; ?>
<?php endforeach; ?>

</div>
