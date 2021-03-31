/* jshint node:true */
module.exports = function (grunt) {
	'use strict';

	var svnFileList = [
		'*.php',
		'readme.txt',
		'changelog.txt',
		'assets/**/**',
		'bootstrap/**/**',
		'i18n/languages/**/**',
		'includes/**/**',
		'templates/**/**',
		'!assets/js/src/**',
		'!assets/css/**/**',
	];

	// Project configuration
	grunt.initConfig({
		pkg: grunt.file.readJSON('package.json'),

		// Setting folder templates.
		dirs: {
			js: 'assets/js',
			css: 'assets/css',
		},

		// Copy source files to build.
		copy: {
			build: {
				files: [
					{
						expand: true,
						src: [...svnFileList, 'composer.lock', 'composer.json'],
						dest: 'build/',
					},
				],
			},
		},

		// Clean.
		clean: {
			pre: [
				'build',
				'release',
				'<%= dirs.js %>/**/*.min.js',
				'<%= dirs.css %>/**/*.min.css',
				'<%= dirs.css %>/**/*-rtl.css',
				'*.zip',
			],
			post: ['build/composer.json', 'build/composer.lock'],
		},

		// Run composer in the build folder.
		exec: {
			composer: {
				cmd: 'composer install --no-dev',
				options: {
					cwd: 'build',
				},
			},
		},

		// Compress build folder.
		compress: {
			withVersion: {
				options: {
					archive: '<%= pkg.name %>-<%= pkg.version %>.zip',
				},
				files: [{
					src: [...svnFileList, 'vendor/**/**'],
					cwd: 'build/',
					dest: '<%= pkg.name %>',
					expand: true,
				}],
			},
			withoutVersion: {
				options: {
					archive: '<%= pkg.name %>.zip',
				},
				files: [{
					src: [...svnFileList, 'vendor/**/**'],
					cwd: 'build/',
					dest: '<%= pkg.name %>',
					expand: true,
				}],
			},
		},
	});

	// Load NPM tasks.
	grunt.loadNpmTasks('grunt-contrib-clean');
	grunt.loadNpmTasks('grunt-contrib-copy');
	grunt.loadNpmTasks('grunt-contrib-compress');
	grunt.loadNpmTasks('grunt-exec');

	grunt.registerTask('build', [
		'clean:pre',
		'copy',
		'exec:composer',
		'clean:post',
	]);

	grunt.registerTask('release', [
		'build',
		'compress:withVersion',
		'compress:withoutVersion',
	]);

	grunt.util.linefeed = '\n';
};
