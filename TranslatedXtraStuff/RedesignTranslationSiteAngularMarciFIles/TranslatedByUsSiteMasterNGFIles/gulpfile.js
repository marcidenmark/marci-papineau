///////////////
// Variables //
///////////////
var gulp         = require( 'gulp' ),
	sass         = require( 'gulp-sass' ),
	sourcemaps   = require( 'gulp-sourcemaps' ),
	autoprefixer = require( 'gulp-autoprefixer' ),
	uglify       = require( 'gulp-uglify' ),
	concat       = require( 'gulp-concat' ),
	order        = require( 'gulp-order' ),
    browserSync  = require( 'browser-sync' ),
    reload       = browserSync.reload;

var style_sources = 'src/sass/**/*.scss',
	style_target  = 'assets/css';

var script_sources = 'src/js/**/*.js',
	script_target  = 'assets/js',
	script_order   = [
		'vendor/jquery/jquery-1.11.3.min.js',
		'vendor/jquery/jquery-migrate-1.2.1.min.js',
		'vendor/jquery/*.js',
		'vendor/bootstrap/bootstrap.js',
		'vendor/c3/*.js',
		'vendor/dropzone/*.js',
		'vendor/angular/angular.min.js',
		'vendor/angular/*.js',
		'main.js',
		'controllers/*.js',
	];

///////////
// Tasks //
///////////
gulp.task( 'styles', function() {
    gulp.src( style_sources )
    	.pipe( sourcemaps.init() )
        .pipe( sass( { outputStyle: 'compressed' } ).on( 'error', sass.logError ) )
        .pipe( autoprefixer() )
        .pipe( sourcemaps.write( '../maps' ) )
        .pipe( gulp.dest( style_target ) )
        .pipe( reload( { stream: true } ) );
} );

gulp.task( 'scripts', function() {
    gulp.src( script_sources )
    	.pipe( order( script_order, { base: 'src/js/' } ) )
		.pipe( concat( 'script.js' ) )
		.pipe( uglify( { mangle: false } ) )
        .pipe( gulp.dest( script_target ) )
        .pipe( reload( { stream: true } ) );
} );

////////////////////////////////////
// Default - Browser Sync & Watch //
////////////////////////////////////
gulp.task( 'default', function() {

    browserSync({
        proxy: 'https://translatedbyus.dev',
        browser: ['google chrome'],
    });

    gulp.watch( style_sources, ['styles'] );
    gulp.watch( script_sources, ['scripts'] );
    gulp.watch( ['views/*.html', 'main.php'], reload );

} );
