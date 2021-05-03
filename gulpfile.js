'use strict';

require('dotenv').config();
var fs = require('fs')
var pkg = JSON.parse(fs.readFileSync('./package.json'))
const { dest, series, src, watch, parallel, task } = require('gulp');
const { exec } = require('child_process');
const zip = require('gulp-zip');
const { sass } = require('@mr-hope/gulp-sass');
const browserSync = require('browser-sync').create();
const uglify = require('gulp-uglify');
const rename = require('gulp-rename');
const autoprefixer = require('gulp-autoprefixer');
const imagemin = require('gulp-imagemin');

if (!process.env.WORDPRESS_URL && process.env.DEVELOPMENT) {
	console.error('Please set WORDPRESS_URL on your environment variable');
	process.exit(1);
}

const fileList = {
	includes: {
		src: 'includes/**/*',
		dest: 'build/includes'
	},
	assets: {
		src: [
			'assets/**/*',
			'!assets/js/src/**/*',
			'!assets/scss/**/*',
		],
		dest: 'build/assets'
	},
	templates: {
		src: 'templates/**/*',
		dest: 'build/templates'
	},
	i18n: {
		src: 'i18n/**/*',
		dest: 'build/i18n'
	},
	bootstrap: {
		src: 'bootstrap/**/*',
		dest: 'build/bootstrap'
	},
	composer: {
		src: [
			'composer.json',
			'composer.lock'
		],
		dest: 'build'
	},
	npm: {
		src: [
			'package.json',
			'package.lock'
		],
		dest: 'build'
	},
	other: {
		src: [
			'readme.txt',
			'changelog.txt',
			'masteriyo.php'
		],
		dest: 'build'
	}
};

// paths for the automation
const paths = {
	sass: {
		src: 'assets/scss/**/*.scss',
		dest: 'assets/css',
	},

	js: {
		src: 'assets/js/*.js',
		dest: 'assets/js',
	},

	images: {
		src: ['assets/img/*.png', 'assets/img/*.jpg'],
		dest: 'assets/img',
	},
	php: {
		src: 'templates/**/*.php',
	},
};

function removePreviousMinifiedAssets() {
	return exec('find assets/ -name "*min*" -type f -delete');
}

function compileSass() {
	return src(paths.sass.src)
		.pipe(
			sass({
				outputStyle: 'compressed',
			}).on('error', sass.logError)
		)
		.pipe(autoprefixer())
		.pipe(browserSync.stream())
		.pipe(dest(paths.sass.dest));
}

function startBrowserSync(cb) {
	browserSync.init({
		proxy: process.env.WORDPRESS_URL,
	});
	cb();
}

function minifyJs() {
	return src(paths.js.src)
		.pipe(uglify())
		.pipe(rename({ suffix: '.min' }))
		.pipe(dest(paths.js.dest));
}

function optimizeImages() {
	return src(paths.images.src).pipe(imagemin()).pipe(dest(paths.images.dest));
}

function reloadBrowserSync(cb) {
	browserSync.reload();
	cb();
}

function watchChanges() {
	watch(paths.sass.src, compileSass);
	watch(paths.js.src, series(minifyJs, reloadBrowserSync));
	watch(paths.php.src, reloadBrowserSync);
	watch(paths.images.src, series(optimizeImages, reloadBrowserSync));
}

function removeBuild() {
	return exec( 'rm -rf build');
}

function removeRelease() {
	return exec( 'rm -rf release');
}

const copyToBuild = [
	() => src(fileList.includes.src).pipe(dest( fileList.includes.dest)),
	() => src(fileList.assets.src).pipe(dest(fileList.assets.dest)),
	() => src(fileList.templates.src).pipe(dest(fileList.templates.dest)),
	() => src(fileList.i18n.src).pipe(dest(fileList.i18n.dest)),
	() => src(fileList.bootstrap.src).pipe(dest(fileList.bootstrap.dest)),
	() => src(fileList.composer.src).pipe(dest(fileList.composer.dest)),
	() => src(fileList.other.src).pipe(dest(fileList.other.dest)),
];

function runComposerInBuild() {
	return exec( 'cd build && composer install --no-dev')
}

function compressBuildWithoutVersion() {
	return src('build/**/*')
		.pipe(zip(`${pkg.name}.zip`))
		.pipe(dest('release'));
}

function compressBuildWithVersion() {
	return src('build/**/*')
		.pipe(zip(`${pkg.name}-${pkg.version}.zip`))
		.pipe(dest('release'));
}

const compileAssets = series(removePreviousMinifiedAssets, parallel(compileSass, minifyJs, optimizeImages));
const build         = series(removeBuild, compileAssets, parallel(copyToBuild), runComposerInBuild);
const dev           = series(removePreviousMinifiedAssets, startBrowserSync, watchChanges);
const release       = series(removeRelease, build, parallel(compressBuildWithVersion, compressBuildWithoutVersion));

exports.dev = dev;
exports.build = build;
exports.release = release;
