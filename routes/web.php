<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'RouteHandler@index');
Route::get('/pages/{slug}', 'RouteHandler@page');

Auth::routes(['register' => false]);
Route::get('logout', 'Auth\LoginController@logout');

Route::middleware('admin')->group(function(){
    Route::get('/home', 'HomeController@index')->name('home');
    Route::get('/file-manager', 'HomeController@fileManager')->name('file-manager');

    Route::resource('/users', 'Admin\UserController');
    Route::get('/users/data/json', 'Admin\UserController@getData')->name('users.data');

    Route::resource('/page', 'Admin\PageController');
    Route::get('/page/data/json', 'Admin\PageController@getData')->name('page.data');

    Route::resource('/category', 'Admin\CategoryController');
    Route::get('/category/data/json', 'Admin\CategoryController@getData')->name('category.data');
    Route::get('category/{id}/up', 'Admin\CategoryController@upPosition')->name('category.up');
    Route::get('category/{id}/down', 'Admin\CategoryController@downPosition')->name('category.down');

});
