<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Service\Admin\UserService;

class BaseController extends Controller
{
    public function __construct(UserService $service)
    {
        $this->baseService = $service;
    }

    /**
     * @method 检验登陆
     * @author Victoria
     * @date   2020-01-12
     */
    public function checkLogin($request)
    {
        if (empty($request->session()->get('user_id'))) {
            return false;
        } else {
        	return true;
        }
    }
}