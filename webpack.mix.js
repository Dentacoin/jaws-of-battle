let mix = require('laravel-mix');
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
mix.disableSuccessNotifications();

/* ===== PUBLIC =====*/
mix.styles([
    /*'node_modules/bootstrap/dist/css/bootstrap.min.css',*/
    'public/assets/libs/bootstrap/css/bootstrap.min.css',
    'public/assets/libs/font-awesome/css/font-awesome.min.css',
    'node_modules/slick-carousel/slick/slick.css'
], 'public/dist/css/front-libs-style.css');

mix.styles([
    'public/assets/css/style.css',
], 'public/dist/css/front-style.css');

mix.scripts([
    'node_modules/jquery/dist/jquery.min.js',
    /*'node_modules/bootstrap/dist/js/bootstrap.min.js',*/
    'public/assets/libs/bootstrap/js/bootstrap.min.js',
    'node_modules/bootbox.js/bootbox.min.js',
    'node_modules/slick-carousel/slick/slick.min.js',
], 'public/dist/js/front-libs-script.js');

mix.scripts([
    'https://dentacoin.com/assets/js/basic.js',
    'public/assets/js/address.js',
    'public/assets/js/index.js'
], 'public/dist/js/front-script.js');
/* ===== /PUBLIC =====*/

/* ===== ADMIN =====*/
/*mix.scripts([
    'node_modules/jquery/dist/jquery.min.js',
    'node_modules/bootstrap/dist/js/bootstrap.min.js',
    'node_modules/bootbox.js/bootbox.min.js',
    'node_modules/datatables.net/js/jquery.dataTables.min.js',
    'node_modules/datatables.net-rowreorder/js/dataTables.rowReorder.min.js',
    'public/assets/libs/ckeditor-full/ckeditor.js',
    'node_modules/admin-lte/dist/js/app.min.js',
    'node_modules/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js',
    'node_modules/bootstrap-select/dist/js/bootstrap-select.min.js',
    'public/assets/libs/jquery-ui/jquery-ui.js',
], 'public/dist/js/admin-libs-script.js');

mix.scripts([
    'https://dentacoin.com/assets/js/basic.js',
    'public/assets/js/admin/index.js'
], 'public/dist/js/admin-script.js');

mix.styles([
    'node_modules/bootstrap/dist/css/bootstrap.min.css',
    'public/assets/libs/font-awesome/css/font-awesome.min.css',
    'node_modules/admin-lte/dist/css/AdminLTE.min.css',
    'node_modules/datatables.net-rowreorder-bs/css/rowReorder.bootstrap.min.css',
    'node_modules/admin-lte/dist/css/skins/_all-skins.min.css',
    'node_modules/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css',
    'node_modules/bootstrap-select/dist/css/bootstrap-select.min.css',
    'public/assets/libs/jquery-ui/jquery-ui.css',
], 'public/dist/css/admin-libs-style.css');*/
/* ===== /ADMIN =====*/