<?php

namespace App\Helper;


class Helper
{
    /**
     * 随机产生数字字符串
     *
     * @param int $length 字符串长度
     * @return null|string
     */
    public static function createRandomNum($length)
    {
        return self::sjstr($length, 1);
    }

    /**
     * 随机产生字母和数字组合型字符串
     *
     * @param int $length 字符串长度
     * @return null|string
     */
    public static function createRandomCode($length)
    {
        return self::sjstr($length, 2);
    }

    /**
     * 自造随机字符串
     *
     * @param $length
     * @param $type
     * @return null|string
     */
    public static function sjstr($length, $type)
    {
        $str = null;
        if ($type == 1) {
            $strPol = "1234567890";
        }
        if ($type == 2) {
            $strPol = "123456789abcdefghijklmnopqrstuvwxyz";
        }
        $max = strlen($strPol) - 1;
        for ($i = 0; $i < $length; $i++) {
            $str .= $strPol[rand(0, $max)];
        }
        return $str;
    }

    /**
     * 验证手机号是否合法
     *
     * @param string $mobile 手机号
     * @param string $countryCode 国家区号
     * @return bool
     */
    public static function validMobile($mobile, $countryCode="86")
    {
        if (in_array($mobile, ['03057947646', '6132637957', '7760640957', '61000013117'])) {
            return true;
        }
        
        if ($countryCode == "86") {
            return (bool)preg_match("/^1\d{10}$/i", $mobile) || (bool)preg_match("/^81\d{9}$/i", $mobile);
        } else {
            return (bool)preg_match("/^\d{5,}$/i", $mobile);
        }
    }

    /**
     * 手机号混淆 比如 186****7495
     *
     * @param string $mobile 手机号
     * @param string $countryCode 国家区号
     * @return string 混淆后的手机号码
     */
    public static function maskMobile($mobile, $countryCode="86")
    {
        if (preg_match('/^(81|61)/', $mobile)) {
            return preg_replace('/(81|61)(\d+)(\d)/', '$1********$3', $mobile);
        } else {
            return preg_replace('/(\d{3})(\d+)(\d{4})/', "$1****$3", $mobile);
        }
    }

    /**
     * 获取中文拼音首字母
     *
     * @param $str
     * @return null|string
     */
    public static function getFirstCharter($str)
    {
        if (empty($str)) {return '';}

        if ($str == '重庆' || $str == '重庆市') return 'C';

        $fchar = ord($str{0});
        if ($fchar >= ord('A') && $fchar <= ord('z')) return strtoupper($str{0});
        $s1 = iconv('UTF-8', 'gb2312', $str);
        $s2 = iconv('gb2312', 'UTF-8', $s1);
        $s = $s2 == $str ? $s1 : $str;
        $asc = ord($s{0}) * 256 + ord($s{1}) - 65536;
        
        if ($asc >= -20319 && $asc <= -20284) return 'A';
        if ($asc >= -20283 && $asc <= -19776) return 'B';
        if ($asc >= -19775 && $asc <= -19219) return 'C';
        if ($asc >= -19218 && $asc <= -18711) return 'D';
        if ($asc >= -18710 && $asc <= -18527) return 'E';
        if ($asc >= -18526 && $asc <= -18240) return 'F';
        if ($asc >= -18239 && $asc <= -17923) return 'G';
        if ($asc >= -17922 && $asc <= -17418) return 'H';
        if ($asc >= -17417 && $asc <= -16475) return 'J';
        if ($asc >= -16474 && $asc <= -16213) return 'K';
        if ($asc >= -16212 && $asc <= -15641) return 'L';
        if ($asc >= -15640 && $asc <= -15166) return 'M';
        if ($asc >= -15165 && $asc <= -14923) return 'N';
        if ($asc >= -14922 && $asc <= -14915) return 'O';
        if ($asc >= -14914 && $asc <= -14631) return 'P';
        if ($asc >= -14630 && $asc <= -14150) return 'Q';
        if ($asc >= -14149 && $asc <= -14091) return 'R';
        if ($asc >= -14090 && $asc <= -13319) return 'S';
        if ($asc >= -13318 && $asc <= -12839) return 'T';
        if ($asc >= -12838 && $asc <= -12557) return 'W';
        if ($asc >= -12556 && $asc <= -11848) return 'X';
        if ($asc >= -11847 && $asc <= -11056) return 'Y';
        if ($asc >= -11055 && $asc <= -10247) return 'Z';
        
        return null;
    }

    /**
     * @method 获取数据库版本号
     * @author Victoria
     * @date   2020-01-12
     * @return string
     */
    public static function mysqlVersion()
    {
        return \DB::select("select version() as version")[0]->version ?? '';
    }
}
