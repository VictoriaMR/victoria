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

Route::get('/', 'IndexController@index');

Route::get('login', 'IndexController@login'); //登陆
Route::get('overview', 'IndexController@overview'); //首页统计

/**
 * 管理员
 */
Route::group(['prefix' => 'user'], function () {
	Route::get('list', 'UserController@getList');
	Route::get('addPage', 'UserController@addPage');
});