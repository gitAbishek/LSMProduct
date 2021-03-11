<?php
/**
 * Tab handles used in the main content of single course.
 *
 * @version 0.1.0
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

global $course;

?>

<div class="tab-menu mto-flex mto-font-bold mto-text-base mto-border-b-2 mto-border-gray-2 mto-mb-10">
	<div id="tab1" class="active-tab mto-pt-6 mto-pb-4 mto--mb-0.5 mto-cursor-pointer" onClick="JavaScript:selectTab(1);">Overview</div>
	<div id="tab2" class="mto-pt-6 mto-pb-4 mto--mb-0.5 mto-ml-12 mto-cursor-pointer" onClick="JavaScript:selectTab(2);">Curriculum</div>
	<div id="tab3" class="mto-pt-6 mto-pb-4 mto--mb-0.5 mto-ml-12 mto-cursor-pointer" onClick="JavaScript:selectTab(3);">FAQ</div>
	<div id="tab4" class="mto-pt-6 mto-pb-4 mto--mb-0.5 mto-ml-12 mto-cursor-pointer" onClick="JavaScript:selectTab(4);">Reviews</div>
</div>

<?php
