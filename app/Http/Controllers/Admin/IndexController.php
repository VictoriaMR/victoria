<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function index(Request $request)
    {
    	if (\Auth::check()) {

    	} else {
    		$data = [
    			'title' => '管理员登陆',
    		];
    		return view('admin.login.login', $data);
    	}
    	// $user = new User();
	     //    $user->name = $name;
	     //    $user->email = $email;
	     //    $user->password = bcrypt($password);
	     //    $user->save();

	     //    \Auth::login($user); // 注册的用户让其进行登陆状态

    	// return 'admin index';
    }

    /**
     * 用户登陆
     * @author   Mingrong
     * @DateTime 2020-01-07
     * @return   
     */
    public function login(Request $request)
    {
    	return view('admin.login.login');
    }
}
