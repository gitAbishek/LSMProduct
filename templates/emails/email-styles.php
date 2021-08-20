<?php
/**
 * Email Styles
 *
 * This template can be overridden by copying it to yourtheme/masteriyo/emails/email-styles.php.
 *
 * HOWEVER, on occasion Masteriyo will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @package Masteriyo\Templates\Emails
 * @version 0.1.0
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly

?>

.email-template{
	width: 30%;
	border:1px solid #EBECF2;
	border-radius: 4px;
	padding:10px 30px;
}
.email-template p{
	line-height: 1.5;
}
.email-template--title{
	font-size: 20px;
	font-weight: 600;
	color: #07092F; 
}
.email-template--button{
	border-radius: 4px;
	background: #78A6FF;
	padding:12px 16px;
	margin: 10px 0px;
	color: #fff;
	text-decoration: none;
	display: inline-block;
}
<?php
