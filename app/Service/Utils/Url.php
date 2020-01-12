<?php
/**
 * url处理相关封装
 *
 * @date: 2018/05/04
 */

namespace App\Service\Utils;

class Url
{
    public static function getMediaUrl($url, $serverId=0)
    {
        if (preg_match("/^http/", $url) || preg_match("/^\/\/media\./", $url) || preg_match("/^\/\/staticm\./", $url)) return $url;

        if (!preg_match("/^lxkw\/upfile/", $url) && !preg_match("/^\/lxkw\/upfile/", $url)) {
            if (preg_match("/^lxkw\//", $url)) {
                $url = str_replace('lxkw/', '/lxkw/upfile/', $url);
            }

            if (preg_match("/^\/lxkw\//", $url)) {
                $url = str_replace('/lxkw/', '/lxkw/upfile/', $url);
            }
        }

        $url = preg_replace('/^\/media/', '', $url);
        
        return rtrim(env("MEDIA_URL"), '/') . $url;
    }

    /**
     * 生成小程序链接（带标签）
     *
     * @author jason
     * @date 2019/02/22
     *
     * @param string $title 链接文本
     * @param string $path 小程序path
     * @param string $defaultUrl 兼容模式下的h5链接
     * @param int $siteId 小程序标识
     * @return string
     */
    public static function buildSmallappUrl($title, $path, $defaultUrl=null,  $siteId=2)
    {
        if (empty($defaultUrl)) $defaultUrl = "https://sm.liuxuekw.com/auth.html";
        
        return "<a data-miniprogram-appid=\"" . config("weixin.sites.{$siteId}.appid") . "\" data-miniprogram-path=\"{$path}\" href=\"{$defaultUrl}\">{$title}</a>";
    }

    /**
     * 转换路径（从小程序格式转化为App格式）
     *
     * @author jason
     * @date 2019/11/20
     *
     * @param string $url 小程序路径 如 consultant/detail?id=133
     * @param array App格式
     */
    public static function parseUrlFromSmallapp($url)
    {
        if (empty($url)) return ['routeName' => ''];
        
        $temp = parse_url($url);

        parse_str($temp['query'] ?? '', $param);

        return [
            'routeName' => $temp['path'] ?? '',
            'param' => $param,
        ];
    }
}
