<?php

// Bail early if the wp cli doesn't exists.
$version = shell_exec( 'wp --version' );
if ( false === strpos( $version, 'WP-CLI' ) ) {
	echo 'WP-CLI is not installed.';
	exit;
}


$users = array(
	array(
		'user_login'  => 'admin',
		'user_email'  => 'admin@the.com',
		'role'        => 'administrator',
		'password'    => 'admin',
		'user_url'    => 'https://admin.example.com',
		'first_name'  => 'Administrator',
		'description' => 'This is administrator',
	),
	array(
		'user_login'  => 'manager1',
		'user_email'  => 'manager1@the.com',
		'role'        => 'masteriyo_manager',
		'password'    => 'manager1',
		'user_url'    => 'https://manager1.example.com',
		'first_name'  => 'Manager 1',
		'description' => 'This is manager 1',
	),
	array(
		'user_login'  => 'manager2',
		'user_email'  => 'manager2@the.com',
		'role'        => 'masteriyo_manager',
		'password'    => 'manager2',
		'user_url'    => 'https://manager2.example.com',
		'first_name'  => 'Manager 2',
		'description' => 'This is manager2',
	),
	array(
		'user_login'  => 'admin',
		'user_email'  => 'admin@the.com',
		'role'        => 'administrator',
		'password'    => 'admin',
		'user_url'    => 'https://admin.example.com',
		'first_name'  => 'Administrator',
		'description' => 'This is administrator',
	),
	array(
		'user_login'  => 'student1',
		'user_email'  => 'student1@the.com',
		'role'        => 'masteriyo_student',
		'password'    => 'student1',
		'user_url'    => 'https://student1.example.com',
		'first_name'  => 'Student1',
		'description' => 'This is student 1',
	),
	array(
		'user_login'  => 'student2',
		'user_email'  => 'student2@the.com',
		'role'        => 'masteriyo_student',
		'password'    => 'student2',
		'user_url'    => 'https://student2.example.com',
		'first_name'  => 'Student2',
		'description' => 'This is student 2',
	),
	array(
		'user_login'  => 'instructor1',
		'user_email'  => 'instructor1@the.com',
		'role'        => 'masteriyo_instructor',
		'password'    => 'instructor1',
		'user_url'    => 'https://instructor1.example.com',
		'first_name'  => 'Instructor1',
		'description' => 'This is instructor 1',
	),
	array(
		'user_login'  => 'instructor2',
		'user_email'  => 'instructor2@the.com',
		'role'        => 'masteriyo_instructor',
		'password'    => 'instructor2',
		'user_url'    => 'https://instructor2.example.com',
		'first_name'  => 'Instructor2',
		'description' => 'This is instructor 2',
	),
);

foreach ( $users as $user ) {
	$user    = (object) $user;
	$command = "wp user create {$user->user_login} {$user->user_email} --role='{$user->role}' --user_pass='{$user->password}' --user_url='{$user->user_url}' --first_name='{$user->first_name}' --description='{$user->description}'";
	$output  = shell_exec( $command );

	echo $output . '\n';
}
