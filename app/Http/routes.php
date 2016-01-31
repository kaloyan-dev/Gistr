<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'PagesController@index');
Route::get('/gists', 'PagesController@fetchGists');
Route::post('/gists', 'PagesController@updateGists');

Route::get('auth/github', [ 'as' => 'github-login', 'uses' => 'Auth\AuthController@redirectToProvider' ]);
Route::get('auth/github/callback', 'Auth\AuthController@handleProviderCallback');
Route::get('auth/logout', 'Auth\AuthController@logout' );

Route::get('user', 'userController@getSettings' );
Route::post('user', 'userController@updateSettings');