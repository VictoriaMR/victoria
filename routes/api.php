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
    Route::post('login', 'UserController@login') ;
});
