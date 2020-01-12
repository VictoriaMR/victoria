<?php
/**
 * 微信相关处理工具方法
 *
 * @date: 2018/05/04
 */

namespace App\Service\Utils;

class Weixin
{
    public static function parseMessage($message, $isLinkNewWindow=true)
    {
        //替换链接
        if ($isLinkNewWindow == true)
            $message = preg_replace("/([^\"]+|^)(http(|s):\/\/[\w\d\._\-#\/]+)/i", "$1 <a href=\"$2\" target=\"_blank\">$2</a>", $message);
        else
            $message = preg_replace("/([^\"]+|^)(http(|s):\/\/[\w\d\._\-#\/]+)/i", "$1 <a href=\"$2\">$2</a>", $message);

        //$message = parse_emoji($message);
        
        return $message;
    }
}
