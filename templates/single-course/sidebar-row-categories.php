<?php
/**
 * Sidebar row - Categories.
 *
 * @version 0.1.0
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

?>

<div class="mto-scourse--badges">
<?php foreach ( $categories as $index => $category ): ?>
	<?php if ( ($index + 1) % 2 === 1 ) : ?>
		<!-- Red Badge -->
		<a href="<?php echo esc_url( $category->get_permalink() ); ?>"
			class="mto-badge mto-badge-red">
			<?php echo esc_html( $category->get_name() ); ?>
		</a>
	<?php else : ?>

		<!-- Green Badge -->
		<a href="<?php echo esc_url( $category->get_permalink() ); ?>"
			class="mto-badge mto-badge-green">
			<?php echo esc_html( $category->get_name() ); ?>
		</a>
	<?php endif; ?>
<?php endforeach; ?>

</div>
