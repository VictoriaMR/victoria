<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

/**
 * admin api
 */
Route::group(['prefix'=>'admin','namespace'=>'Admin'], function (){
    Route::post('login', 'UserController@login');
    Route::post('logout', 'UserController@logout');
    Route::post('user/add', 'UserController@add');
});
