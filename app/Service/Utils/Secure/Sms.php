<?php
/**
 * 动态获取用户登录密码
 *
 * @date: 2019/06/25
 */

namespace App\Service\Utils\Secure;

use Illuminate\Support\Facades\Cache;

class Sms
{
    protected static function getCacheKey()
    {
        return 'SMS_SWITCH';
    }

    /**
     * 打开发送
     *
     * @author jason
     * @date 2019/06/25
     *
     */
    public static function on()
    {
        $cacheKey = self::getCacheKey();

        Cache::put($cacheKey, 0, 60 * 24);

        return true;
    }

    /**
     * 打开发送
     *
     * @author jason
     * @date 2019/06/25
     *
     */
    public static function off()
    {
        $cacheKey = self::getCacheKey();

        Cache::put($cacheKey, 1, 60 * 24);

        return true;
    }

     /**
      * 检查开关状态
     *
     * @author jason
     * @date 2019/06/25
     *
     */
    public static function check()
    {
        $cacheKey = self::getCacheKey();

        return !Cache::get($cacheKey);
    }

    
    
}
