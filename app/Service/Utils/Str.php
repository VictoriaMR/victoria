<?php
/**
 * 字符处理相关封装
 *
 * @date: 2018/05/04
 */

namespace App\Service\Utils;

class Str
{
    public static function formatPrice($price)
    {
        return sprintf('¥%s', number_format(floatval($price)));
    }

    public static function uniqid()
    {
        return uniqid();
    }

    public static function desensitization($str, $char = "*")
    {
    	$count = mb_strlen($str);
	    if ($count === 2) return mb_substr($str, 0 , 1).$char;
	    if ($count > 2) return mb_substr($str, 0 , 1).str_repeat($char, $count-2).mb_substr($str, -1 , 1);
    }

    public static function hexConvert($keyword)
    {
        return iconv("GBK","UTF-8", hex2bin(str_replace('\\x', '', $keyword)));
    }
}
