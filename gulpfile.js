require('dotenv').config();
const { dest, series, src, watch } = require('gulp');
const sass = require('@mr-hope/gulp-sass');
const browserSync = require('browser-sync').create();
const uglify = require('gulp-uglify');
const rename = require('gulp-rename');
const autoprefixer = require('gulp-autoprefixer');
const imagemin = require('gulp-imagemin');

if (!process.env.WORDPRESS_URL) {
	console.error('Please set WORDPRESS_URL on your environment variable');
	process.exit(1);
}

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

function compileSass() {
	return src(paths.sass.src)
		.pipe(sass().on('error', sass.logError))
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

exports.dev = series(startBrowserSync, watchChanges);
