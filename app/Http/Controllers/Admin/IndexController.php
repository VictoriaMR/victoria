<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function index(Request $request)
    {
        $data = [
            'title' => '首页',
        ];

        return redirect('admin/login');

    	if (\Auth::check()) {
            \Auth::login($user);
            return $user;
    	} else {
    		
    		return redirect('admin/login');
    	}
    }

    /**
     * 用户登陆
     * @author   Mingrong
     * @DateTime 2020-01-07
     * @return   
     */
    public function login(Request $request)
    {
        $data = [
            'title' => '管理员登陆',
        ];
    	return view('admin.login.login', $data);
    }
}
