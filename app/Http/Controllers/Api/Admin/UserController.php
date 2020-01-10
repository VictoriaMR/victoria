<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\ApiController as BaseController;
use Illuminate\Http\Request;

class UserController extends BaseController
{
    public function login (Request $request)
    {
    	$name = $request->input('name', ''); //名称
    	$passwd = $request->input('name', ''); //密码

    	print_r($request->input());dd();
    }
}
