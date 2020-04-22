// Load Gulp
var gulp       = require( 'gulp' );
var babelify   = require( 'babelify' );
var browserify = require( 'browserify' );
var buffer     = require( 'vinyl-buffer' );
var concat     = require( 'gulp-concat' );
var gulpif     = require( 'gulp-if' );
var notify     = require( 'gulp-notify' );
var options    = require( 'gulp-options' );
var rename     = require( 'gulp-rename' );
var source     = require( 'vinyl-source-stream' );
var sourcemaps = require( 'gulp-sourcemaps' );
var stripDebug = require( 'gulp-strip-debug' );
var uglify     = require( 'gulp-uglify' );

const { src, dest, task, series, watch, parallel } = require('gulp');

// Javascript
var jsColorPicker = 'color_picker.js';
var jsFrontend    = 'frontend.js';
var jsFiles       = [jsColorPicker, jsFrontend];
var jsSrc         = 'src/js/';
var jsDest        = 'dist/';

// Watches
var jsWatch    = 'src/js/**/*.js';

// Compile/Minify the JavaScript
function js(done) {
	jsFiles.map(function (entry) {
		return browserify({
			entries: [jsSrc + entry]
		})
		.transform( babelify, { presets: [ '@babel/preset-env' ] } )
		.bundle()
		.pipe( source( entry ) )
		.pipe( buffer() )
		.pipe( gulpif( options.has( 'production' ), stripDebug() ) )
		.pipe( sourcemaps.init({ loadMaps: true }) )
		.pipe( uglify() )
        .pipe( rename( { suffix: '.min' } ) )
		.pipe( sourcemaps.write( '.' ) )
		.pipe( dest( jsDest ) )
	});
	done();
}

// Watch for changes
function watch_files() {
	watch( jsWatch, js );
	src( '.' )
		.pipe( notify({ message: 'Gulp is Watching, Happy Coding!' }) );
};

// All the tasks
task("js", js);
task("default", js);
task("watch", series(js, watch_files));
