const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

// mix.js('resources/js/app.js', 'public/js')
//    .sass('resources/sass/app.scss', 'public/css');


mix.styles([
    'public/css/style.css',
    'public/css/app.css',
    'public/css/argon.min.css',
    // 'public/css/all.min.css'
	
], 'public/css/all.css')
.js([
    'public/js/script.js',
    'resources/js/app.js',
    // 'public/js/app.js',
    // 'public/js/argon.min.js'
    // 'node_modules/popper.js/dist/popper.js',
    // 'node_modules/bootstrap/dist/js/bootstrap.min.js',
    // 'public/vendor/chart.js/dist/Chart.min.js',
    // 'public/vendor/chart.js/dist/Chart.extension.js',
    // 'public/js/argon.js?'
], 'public/js/all.js')
.browserSync('http://vehicle.local');