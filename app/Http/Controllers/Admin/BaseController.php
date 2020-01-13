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

    /**
     * 用户登陆
     * @author   Mingrong
     * @DateTime 2020-01-07
     */
    public function login(Request $request)
    {
        $data = [
            'title' => '管理员登陆',
        ];
    	return view('admin.login.login', $data);
    }

    /**
     * @method 获取系统信息
     * @author Victoria
     * @date   2020-01-12
     * @return array
     */
    protected function getSystemInfo()
    {
        $returnData = [];
        $returnData['system_os'] = php_uname('s').php_uname('r');//获取系统类型
        $returnData['server_software'] = $_SERVER["SERVER_SOFTWARE"];//服务器版本
        $returnData['php_version'] = PHP_VERSION; //PHP版本
        $returnData['server_addr'] = $_SERVER['SERVER_ADDR'] ?? '0.0.0.0'; //服务器IP地址
        $returnData['server_name'] = $_SERVER['SERVER_NAME']; //服务器域名
        $returnData['server_port'] = $_SERVER['SERVER_PORT']; //服务器端口

        $returnData['php_sapi_name'] = php_sapi_name(); //PHP运行方式
        $returnData['mysql_version'] = \App\Helper\Helper::mysqlVersion(); //mysql 版本
        $returnData['max_execution_time'] = get_cfg_var('max_execution_time') . 's'; //最大执行时间
        $returnData['upload_max_filesize'] = get_cfg_var('upload_max_filesize'); //最大上传限制
        $returnData['memory_limit'] = get_cfg_var('memory_limit'); //最大内存限制
        $returnData['laravel_version'] = app()::VERSION; //Laravel版本
        $returnData['processor_identifier'] = $_SERVER['PROCESSOR_IDENTIFIER'] ?? ''; //服务器cpu 数
        $returnData['disk_used_rate'] = sprintf('%.2f', 1 - disk_free_space('/') / disk_total_space('/')) * 100 . '%'; //磁盘使用情况
        $returnData['disk_free_space'] = sprintf('%.2f', disk_free_space('/') / 1024 / 1024) . 'M'; 

        return $returnData;
    }
}