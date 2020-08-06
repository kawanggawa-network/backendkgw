<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::post('/login', 'API\AuthenticationController@login');
Route::post('/register', 'API\AuthenticationController@register');
Route::post('/auth-social', 'API\AuthenticationController@authenticateSocialMedia');

Route::post('/forgot-password', 'API\ForgotPasswordController@requestResetPassword');
Route::post('/reset-password', 'API\ForgotPasswordController@updatePassword');

Route::get('/page/{slug}', 'API\ContentController@getPage');

Route::middleware(['auth:api'])->group(function () {
    Route::get('/me', 'API\UserController@getProfile');
    Route::post('/me', 'API\UserController@updateProfile');
});

Route::prefix('program')->group(function () {
    Route::get('/categories', 'API\ProgramController@getCategories');
});