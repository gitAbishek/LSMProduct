<?php


echo '<h1>Checkout Page sagar</h1>';

$html = '<ul>';
$courses_count = 0;
foreach( $cart->get_cart_contents() as $cart_content ) {
	if ( ! isset( $cart_content["course_id"] ) ) {
		continue;
	}

	$course = masteriyo_get_course( $cart_content['course_id'] );
	if ( is_null( $course ) ) {
		continue;
	}

	++$courses_count;

	$html .= '<li>' . $course->get_name() . ' - ' . masteriyo_price( $course->get_price() ) . '</li>';
}

if ( $courses_count > 0 ) {
	echo $html . '</ul>';
}
