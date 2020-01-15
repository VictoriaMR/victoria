<?php

use Illuminate\Http\Request;

Route::group(['namespace'=>'Common', 'prefix'=>'common'], function (){
    Route::post('upload', 'FileController@upload');
});

/**
 * admin api
 */
Route::group(['prefix'=>'admin','namespace'=>'Admin'], function (){
    Route::post('login', 'UserController@login');
    Route::post('logout', 'UserController@logout');
    Route::post('user/add', 'UserController@add');
});
