<?php

namespace App\Service\Utils;

use GuzzleHttp\Client;

/**
 * 翻译接口
 *
 * @date: 2018/05/04
 */
class Translation
{

    private $http=null;

    function __construct()
    {
        // 实例化 HTTP 客户端
        $this->http = new Client();
    }
    /**
     * @method 翻译接口 默认英译中
     *
     * @author MingRong
     * @date   2019-05-22
     *
     * @param  string     $text [待翻译文本]
     * @param  string     $from [文本语言] 
     * @param  string     $to   [需翻译语言]
     * @return string
     */
    public function translateBaidu($text = '', $from = 'en', $to = 'zh')
    {
        if (empty($text)) {
            return ['code'=>'400', 'message'=>'翻译文本为空'];
        }

        // 初始化配置信息
        $api = 'http://api.fanyi.baidu.com/api/trans/vip/translate?'; //请求链接
        $appid = config('services.baidu_translate.appid');
        $key = config('services.baidu_translate.key');

        $salt = time(); //随机数

        // 如果没有配置百度翻译，自动使用兼容的拼音方案
        if (empty($appid) || empty($key)) {
            return ['code'=>'400', 'message'=>'参数不正确'];
        }

        // appid+q+salt+密钥 的MD5值
        $sign = md5($appid. $text . $salt . $key);

        // 构建请求参数
        $query = http_build_query([
            "q"     => $text,
            "from"  => $from,
            "to"    => $to,
            "appid" => $appid,
            "salt"  => $salt,
            "sign"  => $sign,
        ]);

        // 发送 HTTP Get 请求
        $response = $this->http->get($api.$query);

        $result = json_decode($response->getBody(), true);


        /**
        获取结果，如果请求成功，dd($result) 结果如下：

        array:3 [▼
            "from" => "zh"
            "to" => "en"
            "trans_result" => array:1 [▼
                0 => array:2 [▼
                    "src" => "XSS 安全漏洞"
                    "dst" => "XSS security vulnerability"
                ]
            ]
        ]

        **/

        // 尝试获取获取翻译结果
        if (isset($result['trans_result'][0]['dst'])) {
            return ['code'=>'200', 'data'=>$result['trans_result'][0]['dst']];
        } else {
            return ['code'=>'400', 'message'=>'翻译出错'];
        }
    }

    /**
     * @method 翻译接口 默认英译中
     *
     * @author MingRong
     * @date   2019-05-22
     *
     * @param  string     $text [待翻译文本]
     * @param  string     $from [文本语言] 
     * @param  string     $to   [需翻译语言]
     * @return string
     */
    public function getTranslate($text = '', $from = 'en', $to = 'zh')
    {
        if (empty($text)) {
            return false;
        }

        // print_r($text);dd();

        //发送请求
        for ($i=0; $i < 10; $i++) { 
            $data = $this->translateBaidu($text, $from, $to);

            if ($data['code'] == 200)
                return $data;
            else if ($i == 10) {
                return ['code' => '100000', 'data' => '翻译出错'];
            }
        }

        return ['code' => '100000', 'data' => '翻译出错'];
    }

}
