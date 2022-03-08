'use strict';

require('dotenv').config();
const fs = require('fs');
const rename = require('gulp-rename');
const pkg = JSON.parse(fs.readFileSync('./package.json'));
const { dest, series, src, watch, parallel, task } = require('gulp');
const { exec } = require('child_process');
const zip = require('gulp-zip');
const { sass } = require('@mr-hope/gulp-sass');
const browserSync = require('browser-sync').create();
const uglify = require('gulp-uglify');
const autoprefixer = require('gulp-autoprefixer');
const imagemin = require('gulp-imagemin');

if (!process.env.WORDPRESS_URL && process.env.DEVELOPMENT) {
	console.error('Please set WORDPRESS_URL on your environment variable');
	process.exit(1);
}

const fileList = {
	database: {
		src: 'database/**/*',
		dest: `build/${pkg.name}/database`,
	},
	includes: {
		src: 'includes/**/*',
		dest: `build/${pkg.name}/includes`,
	},
	assets: {
		src: ['assets/**/*', '!assets/js/src/**/*', '!assets/scss/**/*'],
		dest: `build/${pkg.name}/assets`,
	},
	templates: {
		src: 'templates/**/*',
		dest: `build/${pkg.name}/templates`,
	},
	i18n: {
		src: 'i18n/**/*',
		dest: `build/${pkg.name}/i18n`,
	},
	config: {
		src: 'config/**/*',
		dest: `build/${pkg.name}/config`,
	},
	bootstrap: {
		src: 'bootstrap/**/*',
		dest: `build/${pkg.name}/bootstrap`,
	},
	composer: {
		src: ['composer.json', 'composer.lock'],
		dest: `build/${pkg.name}/`,
	},
	npm: {
		src: ['package.json', 'package.lock'],
		dest: `build/${pkg.name}/`,
	},
	other: {
		src: ['readme.txt', 'changelog.txt', 'lms.php'],
		dest: `build/${pkg.name}/`,
	},
};

// paths for the automation
const paths = {
	sass: {
		src: 'assets/scss/**/*.scss',
		dest: 'assets/css',
	},

	js: {
		src: ['assets/js/build/*.js', '!assets/js/build/*.min.js'],
		dest: 'assets/js/build',
	},

	frontEndJs: {
		src: ['assets/js/frontend/*.js', '!assets/js/frontend*.min.js'],
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
		.pipe(
			sass({
				outputStyle: 'compressed',
			}).on('error', sass.logError)
		)
		.pipe(autoprefixer())
		.pipe(browserSync.stream())
		.pipe(dest(paths.sass.dest))
		.pipe(rename({ suffix: `.${pkg.version}` }))
		.pipe(dest(paths.sass.dest))
		.pipe(rename({ suffix: `.min` }))
		.pipe(dest(paths.sass.dest));
}

function startBrowserSync(cb) {
	browserSync.init({
		proxy: process.env.WORDPRESS_URL,
	});
	cb();
}

function moveFrontEndJs() {
	return src(paths.frontEndJs.src)
		.pipe(rename({ suffix: `.${pkg.version}` }))
		.pipe(dest(paths.js.dest));
}

function minifyJs() {
	return src(paths.js.src)
		.pipe(uglify())
		.pipe(rename({ suffix: `.min` }))
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
	watch(paths.sass.src, series(compileSass));
	watch(
		paths.frontEndJs.src,
		series(moveFrontEndJs, minifyJs, reloadBrowserSync)
	);
	watch(paths.php.src, reloadBrowserSync);
	watch(paths.images.src, series(optimizeImages, reloadBrowserSync));
}

function removeBuild() {
	return exec('rm -rf build');
}

function removeRelease() {
	return exec('rm -rf release');
}

function removeCompiledAssets() {
	return exec('rm -rf assets/css');
}

function removeLanguageFiles() {
	return exec('rm i18n/languages/masteriyo-*');
}

const copyToBuild = [
	() => src(fileList.database.src).pipe(dest(fileList.database.dest)),
	() => src(fileList.includes.src).pipe(dest(fileList.includes.dest)),
	() => src(fileList.assets.src).pipe(dest(fileList.assets.dest)),
	() => src(fileList.templates.src).pipe(dest(fileList.templates.dest)),
	() => src(fileList.i18n.src).pipe(dest(fileList.i18n.dest)),
	() => src(fileList.bootstrap.src).pipe(dest(fileList.bootstrap.dest)),
	() => src(fileList.config.src).pipe(dest(fileList.config.dest)),
	() => src(fileList.composer.src).pipe(dest(fileList.composer.dest)),
	() => src(fileList.other.src).pipe(dest(fileList.other.dest)),
];

function runComposerInBuild() {
	return exec(
		`cd build/${pkg.name}/ && composer install --no-dev --optimize-autoloader`
	);
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

const build = series(
	removeBuild,
	removeCompiledAssets,
	moveFrontEndJs,
	compileSass,
	minifyJs,
	optimizeImages
);
const dev = series(startBrowserSync, watchChanges);
const release = series(
	removeRelease,
	build,
	copyToBuild,
	runComposerInBuild,
	parallel(compressBuildWithVersion, compressBuildWithoutVersion)
);

exports.clean = series(
	removeBuild,
	removeRelease,
	removeCompiledAssets,
	removeLanguageFiles
);
exports.dev = dev;
exports.build = build;
exports.release = release;
