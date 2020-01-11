<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\ApiController as BaseController;
use Illuminate\Http\Request;
use App\Service\Admin\UserService;

class UserController extends BaseController
{
	public function __construct(UserService $service)
    {
        $this->baseService = $service;
    }

    public function login(Request $request)
    {
    	$name = $request->input('name', ''); //名称
    	$password = $request->input('password', ''); //密码

    	if (!$this->baseService->isExistUserByName($name)) {
    		return $this->getResult(100000, false, ['message' => '登陆失败, 用户不存在']);
    	}

    	$result = $this->baseService->login($name, $password, false, true);

    	if ($result)
    		return $this->getResult(200, $result, ['message' => '登陆成功']);
    	else
    		return $this->getResult(100000, $result, ['message' => '登陆失败, 密码不正确']);
    }
}
