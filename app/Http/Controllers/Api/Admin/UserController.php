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

    /**
     * @method 登陆
     * @author Victoria
     * @date   2020-01-12
     */
    public function login(Request $request)
    {
    	$name = $request->input('name', ''); //名称
    	$password = $request->input('password', ''); //密码

    	if (!$this->baseService->isExistUserByName($name)) {
    		return $this->getResult(100000, false, ['message' => '登陆失败, 用户不存在']);
    	}

    	$result = $this->baseService->login($name, $password, false, true);

    	if ($result){
            $data = [
                'target_id' => $request->session()->get('user_id'),
                'type_id' => \App\Service\Logger\BaseLoggerService::constant('TYPE_ADMIN_USER_LOGGIN'),
                'entity_id' => $request->session()->get('user_id'),
                'raw_data' => '登陆',
            ];
            //记录日志
            event(new \App\Events\Logger\CreateEvent(
                new \App\Service\Utils\SimpleDataObject($data)
            ));
    		return $this->getResult(200, $result, ['message' => '登陆成功']);
        }
    	else
    		return $this->getResult(100000, $result, ['message' => '登陆失败, 密码不正确']);
    }

    /**
     * @method 登出
     * @author Victoria
     * @date   2020-01-12
     */
    public function logout(Request $request)
    {
        //日志事件
        $data = [
            'target_id' => $request->session()->get('user_id'),
            'type_id' => \App\Service\Logger\BaseLoggerService::constant('TYPE_ADMIN_USER_LOGGIN'),
            'entity_id' => $request->session()->get('user_id'),
            'raw_data' => '退出登陆',
        ];

        $request->session()->flush();

        //记录日志
        event(new \App\Events\Logger\CreateEvent(
            new \App\Service\Utils\SimpleDataObject($data)
        ));
        return $this->getResult(200, true, ['message' => '登出成功']);
    }

    /**
     * @method 创建用户
     * @author Victoria
     * @date   2020-01-12
     */
    public function add(Request $request)
    {
        $name = $request->input('name', ''); //姓名
        $nickname = $request->input('nickname', ''); //昵称
        $mobile = $request->input('mobile', ''); //手机号码
        $isSuper = $request->input('is_super', 0); //是否添加超级管理员
        $password = $request->input('password', ''); //密码

        if ($isSuper == 'on')
            $isSuper = 1;

        if (empty($name))
            return $this->getResult(100000, false, ['message' => '姓名不能为空']);

        if (empty($mobile))
            return $this->getResult(100000, false, ['message' => '手机号码不能为空']);

        if (empty($password))
            return $this->getResult(100000, false, ['message' => '密码不能为空']);

        if (empty($request->session()->get('is_super'))) 
            return $this->getResult(100000, false, ['message' => '非超级管理员不能新增用户']);

        if ($this->baseService->isExistUserByName($name)) {
            return $this->getResult(100000, false, ['message' => '用户名已存在']);
        }

        if ($this->baseService->isExistUserByMobile($mobile)) {
            return $this->getResult(100000, false, ['message' => '手机号已存在']);
        }

        $data = [
            'name' => $name,
            'nickname' => $nickname,
            'mobile' => $mobile,
            'is_super' => $isSuper,
            'password' => $password,
        ];

        $result = $this->baseService->create($data);

        if ($result)
            return $this->getResult(200, $result, ['message' => '新增成功']);
        else
            return $this->getResult(100000, $result, ['message' => '新增失败']);
    }
}
