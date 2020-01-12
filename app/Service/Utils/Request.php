<?php
/**
 * 请求参数相关处理封装
 *
 * @date: 2019/11/13
 */

namespace App\Service\Utils;

use Illuminate\Http\Request as RequestObject;

class Request
{
    /**
     * 获取访客ip
     *
     * @author jason
     * @date 2019/11/14
     *
     * @param object Illuminate\Http\Request $request
     * @return string
     */
    public static function getIp(RequestObject $request)
    {
        $ip = '';
        
        foreach (['HTTP_X_REAL_IP'] as $item ) {
            $ip = $request->server($item);

            if (!empty($ip)) break;
        }

        if (empty($ip)) $ip = $request->ip();

        return $ip;
    }

    /**
     * 获取访客客户端标识
     *
     * @author jason
     * @date 2019/11/14
     *
     * @param object Illuminate\Http\Request $request
     * @return string
     */
    public static function getUserAgent(RequestObject $request)
    {
        $userAgent = $request->input('user_agent');

        if (empty($userAgent)) $userAgent = $request->server('HTTP_USER_AGENT');

        return $userAgent;
    }
}
