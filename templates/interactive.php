<?php
/**
 * Interactive page.
 */

use ThemeGrill\Masteriyo\Constants;

?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<title>React App FrontEnt</title>
		<link
			href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap"
			rel="stylesheet"
		/>
		<style>
			body {
				background-color: #f8f8f8;
				font-family: 'Roboto', sans-serif;
			}
		</style>
		<?php wp_head(); ?>
	</head>

	<body>
		<div id="masteriyo-interactive-course"></div>
		<script>
			window._MASTERIYO_ = {
				rootApiUrl: 'http://localhost/masteriyo/wp-json',
				nonce: 'cafca5cd48',
				pageSlugs: {
					courseList: 'course-list',
					myaccount: 'myaccount',
					checkout: 'masteriyo-checkout',
				},
				currency: {
					code: 'USD',
					symbol: '&#36;',
					position: 'left',
				},
				imageSizes: [
					'thumbnail',
					'medium',
					'medium_large',
					'large',
					'1536x1536',
					'2048x2048',
					'post-thumbnail',
				],
			};
		</script>
		<?php wp_footer(); ?>
	</body>
</html>

<?php

