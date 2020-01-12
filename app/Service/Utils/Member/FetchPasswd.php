<?php
/**
 * 动态获取用户登录密码
 *
 * @date: 2019/06/25
 */

namespace App\Service\Utils\Member;

use Illuminate\Support\Facades\Cache;

class FetchPasswd
{
    protected static function getCacheKey($mobile, $countryCode=86, $userType=1)
    {
        return 'FETCH_PASSWD_' . $userType . '_' . $countryCode . '_' . $mobile;
    }

    /**
     * 生成随机密码
     *
     * @author jason
     * @date 2019/06/25
     *
     * @param string $mobile 用户手机
     * @param int $countryCode 手机区号
     * @param int $userType 用户类型
     * @return string 生的的密码
     */
    public static function generate($mobile, $countryCode=86, $userType=1)
    {
        $cacheKey = self::getCacheKey($mobile, $countryCode, $userType);

        $passwd = sprintf("%08d", mt_rand(1, 99999999));

        Cache::put($cacheKey, $passwd, 1);

        return $passwd;
    }

    /**
     * 校验随机密码是否正确
     *
     * @author jason
     * @date 2019/06/25
     *
     * @param string $mobile 用户手机
     * @param string $passwd 密码
     * @param int $countryCode 手机区号
     * @param int $userType 用户类型
     * @return void
     */
    public static function check($mobile, $passwd, $countryCode=86, $userType=1)
    {
        $cacheKey = self::getCacheKey($mobile, $countryCode, $userType);

        $cachedPasswd = Cache::get($cacheKey);

        if (empty($cachedPasswd)) return false;

        Cache::forget($cacheKey);

        return $cachedPasswd == $passwd;
    }
}
