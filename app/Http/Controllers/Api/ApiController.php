<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\ErrorCode;

/**
 * Api基类
 *
 */
class ApiController extends Controller
{
    protected $baseService = null;

    protected $pagesize = 10;
    
    /**
     * 封装接口返回数据格式
     *
     * @param int $code 错误代码
     * @param mix $data 返回数据
     * @param array $options 扩展参数
     * @return array
     */
    protected function getResult($code, $data=[], $options=[])
    {
        return [
            "code" => $code,
            "message" => isset($options["message"])?$options["message"]:ErrorCode::getMessage($code),
            "data" => $data
        ];
    }
    
    /**
     * Get the token from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    protected function getTokenFromRequest(Request $request)
    {
        $token = $request->input('token') ?: $request->header('X-AUTH-ACCESS-TOKEN');

        if (empty($token)) $token = $request->input('access_token');
        
        return $token;
    }

    /**
     * Get the app version from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    protected function getVersionFromRequest(Request $request)
    {
        return $request->input('app_version') ?: $request->header('X-AGENT-VERSION', 0);
    }

    /**
     * Get the app version from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    protected function getDeviceIdFromRequest(Request $request)
    {
        return $request->input('device_id') ?: $request->header('X-AGENT-DEVICE-ID', 0);
    }
}
