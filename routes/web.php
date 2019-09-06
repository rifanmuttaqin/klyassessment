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

// Auth controller
$router->group(['prefix' => 'auth', 'namespace'=>'Auth'], function () use ($router) {
    $router->get('/login',  ['uses' => 'LoginController@showLoginForm']);
    $router->post('/login',  ['as'=>'login', 'uses' => 'LoginController@login']);
    $router->get('/logout',  ['uses' => 'LoginController@logout']);
});

// Untuk Profile
Route::get('/profile', ['as'=>'profile', 'uses' => 'ProfileController@index']);
Route::post('/profile/update', ['as'=>'update-profile', 'uses' => 'ProfileController@update']);
Route::post('/profile/update-password', ['as'=>'update-password-profile', 'uses' => 'ProfileController@updatePassword']);
Route::post('/profile/delete-image', ['as'=>'delete-image', 'uses' => 'ProfileController@deleteImage']);

// Home
Route::get('/', ['as'=>'home', 'uses' => 'HomeController@index']);

// User
Route::get('/user', ['as'=>'index-user', 'uses' => 'UserController@index']);
Route::post('/user/get-detail', ['as'=>'detail', 'uses' => 'UserController@show']);
Route::post('/user/update', ['as'=>'update-user', 'uses' => 'UserController@update']);
Route::post('/user/store', ['as'=>'store-user', 'uses' => 'UserController@store']);
Route::get('/user/create', ['as'=>'create-user', 'uses' => 'UserController@create']);
Route::post('/user/update-password', ['as'=>'update-password-user', 'uses' => 'UserController@updatePassword']);
Route::post('/user/delete', ['as'=>'delete-user', 'uses' => 'UserController@delete']);