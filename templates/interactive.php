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
		<?php wp_footer(); ?>
	</body>
</html>

<?php
