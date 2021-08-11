<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the 'web' middleware group. Now create something great!
|
*/

//Route::get('/refresh-captcha', 'Controller@refreshCaptcha')->name('refresh-captcha');

Route::group(['prefix' => '/', 'middleware' => 'frontEndMiddleware'], function () {

    //======================================= PAGES ========================================

    Route::get('/', 'PagesController@getPageView')->name('home');

    Route::get('/change-password', 'UserController@getChangePasswordView')->name('change-password');

    Route::post('/submit-change-password', 'UserController@submitChangePassword')->name('submit-change-password');

    Route::get('/sitemap', 'Controller@getSitemap')->name('sitemap');
});