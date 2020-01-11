<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function index(Request $request)
    {
    	if (!empty($request->session()->get('user_id'))) {
            $data = [
                'title' => '首页',
            ];
            return view('admin.index');
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
