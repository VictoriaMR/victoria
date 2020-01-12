<?php
/**
 * App push (aliyun mobile push)
 *
 * @date: 2019/08/22
 */

namespace App\Service\Utils\Message;

class Push
{
    const API_URL = "http://cloudpush.aliyuncs.com/";

    /**
     * 高级发送接口
     *
     * @param mix $targetValue 发送对象,支持string, array
     * @param string $title 消息的标题
     * @param string $body 消息的内容
     * @param array $extras 附加参数
     * @param string $deviceType 设备类型，具体见下方注释
     * @param string $target  推送目标，具体见下方注释
     * @param int $siteId 应用标识,默认为学生端
     * @return array 返加结果，见 https://help.aliyun.com/knowledge_detail/48089.html?spm=a2c4g.11186631.2.5.618e24e1w62iT8
     */
    public static function push($targetValue, $title, $body, $extras=[], $deviceType="ALL", $pushType="NOTICE", $target='DEVICE', $siteId=11)
    {
        if (is_array($targetValue)) $targetValue = join(',', $targetValue);
        
        $params = [
            'Action' => 'Push',
            
            // 推送目标
            'AppKey' => $deviceType == 'iOS' ? config("liuxuekw.apps.{$siteId}.ios.push.app_key") : config("liuxuekw.apps.{$siteId}.android.push.app_key"),
            'Target' => $target, // 推送目标: DEVICE:推送给设备; ACCOUNT:推送给指定帐号,TAG:推送给自定义标签; ALIAS: 按别名推送; ALL: 全推
            'TargetValue' => $targetValue, // 根据Target来设定，如Target=DEVICE, 则对应的值为 设备id1,设备id2. 多个值使用逗号分隔.(帐号与设备有一次最多100个的限制)
            'DeviceType' => $deviceType, // 设备类型deviceType, iOS设备: "iOS"; Android设备: "ANDROID"; 全部: "ALL", 这是默认值.


            // 推送配置
            'PushType' => $pushType, // MESSAGE:表示消息(默认), NOTICE:表示通知
            'Title' => $title, // 消息的标题
            'Body' => $body, // 消息的内容


            // 推送配置: iOS
            //'iOSBadge' => 5, // iOS应用图标右上角角标
            //'iOSBadgeAutoIncrement' => 'true', // 是否开启角标自增功能，默认为False，当该项为True时，iOSBadge必须为为空。角标自增功能由推送服务端维护每个设备的角标计数，需要用户使用1.9.5以上版本的sdk，并且需要用户主动同步角标数字到服务端。
            'iOSMusic' => 'default', // iOS通知声音
            'iOSApnsEnv' => 'PRODUCT', //iOS的通知是通过APNs中心来发送的，需要填写对应的环境信息。'DEV': 表示开发环境 'PRODUCT': 表示生产环境
            // 'iOSRemind' => "true", // 消息推送时设备不在线（既与移动推送的服务端的长连接通道不通），则这条推送会做为通知，通过苹果的APNs通道送达一次。注意：**离线消息转通知仅适用于`生产环境`**
            'iOSRemindBody' => 'PushRequest summary', // iOS消息转通知时使用的iOS通知内容，仅当iOSApnsEnv=`PRODUCT` && iOSRemind为true时有效
            // 'iOSExtParameters' => json_encode($extras), //通知的扩展属性(注意 : 该参数要以json map的格式传入,否则会解析出错)

            // 推送配置: Android
            'AndroidOpenType' => 'APPLICATION', // 点击通知后动作 'APPLICATION': 打开应用 'ACTIVITY': 打开应用AndroidActivity 'URL': 打开URL 'NONE': 无跳转
            'AndroidNotifyType' => 'BOTH', // 通知的提醒方式 ‘VIBRATE': 振动  'SOUND': 声音 'DEFAULT': 声音和振动 'NONE': 不做处理，用户自定义
            'AndroidOpenUrl' => '', //
            'AndroidMusic' => 'default', // Android通知声音
            //'AndroidActivity' => 'com.liuxuekw.student', // Android收到推送后打开对应的url,仅当`AndroidOpenType="URL"`有效
            'AndroidPopupActivity' => 'host.exp.exponent.MainActivity', // 设置该参数后启动辅助弹窗功能, 此处指定通知点击后跳转的Activity（辅助弹窗的前提条件：1. 集成第三方辅助通道；2. StoreOffline参数设为true） org.wonday.aliyun.push.ThirdPartMessageActivity
            'AndroidPopupTitle' => $title, // 设置辅助弹窗通知的标题
            'AndroidPopupBody' => $body, // 设置辅助弹窗通知的内容
            'AndroidNotificationBarType' => 50, // Android自定义通知栏样式，取值：1-100
            'AndroidNotificationBarPriority' => 2, // Android通知在通知栏展示时排列位置的优先级 -2 -1 0 1 2
            // 'AndroidExtParameters' => json_encode($extras), // 设定通知的扩展属性。(注意 : 该参数要以 json map 的格式传入,否则会解析出错)
            'AndroidNotificationChannel' => "1",

            // 推送控制
            // 'PushTime' => '', // 用于定时发送。不设置缺省是立即发送。时间格式按照ISO8601标准表示，并需要使用UTC时间，格式为`YYYY-MM-DDThh:mm:ssZ`。
            'StoreOffline' => "true", // 离线消息是否保存,若保存, 在推送时候，用户即使不在线，下一次上线则会收到
            'ExpireTime' => str_replace('+08:00', 'Z', date('c', time() + 3600 * 24 * 3 - 3600 * 8)), // 消息失效时间, 过期不会再发送

            // 短信融合通知, 暂时不需要

            // 公共参数
            'Format' => 'JSON', // 返回值的类型，支持JSON与XML，默认为XML。
            'RegionId' => 'cn-hangzhou', // 当前请设置为cn-hangzhou
            'Version' => '2016-08-01', // API版本号，为日期形式YYYY-MM-DD，移动推送OpenAPI2.0对应的版本号是2016-08-01。
            'AccessKeyId' => config("liuxuekw.apps.{$siteId}.access_key_id"), // 阿里云颁发给用户的访问服务所用的密钥ID。
            //'Signature' => '', //签名结果串，关于签名的计算方法，请参见 签名机制。
            'SignatureMethod' => 'HMAC-SHA1', //签名方式，目前支持HMAC-SHA1。
            'Timestamp' => str_replace('+08:00', 'Z', date('c', time() - 3600 * 8)), //请求的时间戳。日期格式按照ISO8601标准表示，并需要使用UTC时间，格式为YYYY-MM-DDThh:mm:ssZ，例如2016-02-25T12:00:00Z（为UTC时间2016年2月25日12点0分0秒）。
            'SignatureVersion' => '1.0', // 签名算法版本，目前版本是1.0。
            'SignatureNonce' => uniqid(), // 唯一随机数，用于防止网络重放攻击，用户在不同请求间要使用不同的随机数值。
        ];

        // 附加参数
        if (!empty($extras)) {
            $params['iOSExtParameters'] = $params['AndroidExtParameters'] = json_encode($extras);
        }

        $params['Signature'] = self::computeSignature($params, config("liuxuekw.apps.{$siteId}.access_key_secret"));

        try {
            $response = \App\Service\Utils\Http::get(self::API_URL, $params);
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            if ($e->getResponse()->getStatusCode() == '400') {
                $response = $e->getResponse();
            }
        } 

        $result = json_decode($response->getBody(), true);

        return $result;
    }

    /**
     * 测试iOS push
     *
     *
     */
    public static function pushNoticeToiOS($targetValue, $title, $body, $extra=[], $target="DEVICE")
    {
        $params = [
            'Action' => 'PushNoticeToiOS',
            
            // 推送目标
            'AppKey' => 27675649,
            'Target' => $target, // 推送目标: DEVICE:推送给设备; ACCOUNT:推送给指定帐号,TAG:推送给自定义标签; ALIAS: 按别名推送; ALL: 全推
            'TargetValue' => $targetValue, // 根据Target来设定，如Target=DEVICE, 则对应的值为 设备id1,设备id2. 多个值使用逗号分隔.(帐号与设备有一次最多100个的限制)
            

            // 推送配置
            'Title' => $title, // 消息的标题
            'Body' => $body, // 消息的内容
            
            'ApnsEnv' => 'DEV',
            'ExtParameters' => json_encode(['name' => 'qiongyue', 'routeName' => 'trial']), //通知的扩展属性(注意 : 该参数要以json map的格式传入,否则会解析出错)
            
            // 公共参数
            'Format' => 'JSON', // 返回值的类型，支持JSON与XML，默认为XML。
            'RegionId' => 'cn-hangzhou', // 当前请设置为cn-hangzhou
            'Version' => '2016-08-01', // API版本号，为日期形式YYYY-MM-DD，移动推送OpenAPI2.0对应的版本号是2016-08-01。
            'AccessKeyId' => 'LTAIrdyt0Z5UXzim', // 阿里云颁发给用户的访问服务所用的密钥ID。
            //'Signature' => '', //签名结果串，关于签名的计算方法，请参见 签名机制。
            'SignatureMethod' => 'HMAC-SHA1', //签名方式，目前支持HMAC-SHA1。
            'Timestamp' => str_replace('+08:00', 'Z', date('c', time() - 3600 * 8)), //请求的时间戳。日期格式按照ISO8601标准表示，并需要使用UTC时间，格式为YYYY-MM-DDThh:mm:ssZ，例如2016-02-25T12:00:00Z（为UTC时间2016年2月25日12点0分0秒）。
            'SignatureVersion' => '1.0', // 签名算法版本，目前版本是1.0。
            'SignatureNonce' => uniqid(), // 唯一随机数，用于防止网络重放攻击，用户在不同请求间要使用不同的随机数值。LTAIrdyt0Z5UXzim qnSu1xxNcUMWbfsCUXKJQjsbdqmTLg
        ];

        $params['Signature'] = self::computeSignature($params, 'qnSu1xxNcUMWbfsCUXKJQjsbdqmTLg');

        
        $response = \App\Service\Utils\Http::get(self::API_URL, $params);

        $result = json_decode($response->getBody(), true);

        return $result;
    }
    
    public static function percentEncode($str)  
    {  
        // 使用urlencode编码后，将"+","*","%7E"做替换即满足ECS API规定的编码规范  
        $res = urlencode($str);  
        $res = preg_replace('/\+/', '%20', $res);  
        $res = preg_replace('/\*/', '%2A', $res);  
        $res = preg_replace('/%7E/', '~', $res);
        
        return $res;  
    }

    public static function computeSignature($parameters, $accessKeySecret)  
    {  
        // 将参数Key按字典顺序排序  
        ksort($parameters);
        
        // 生成规范化请求字符串  
        $canonicalizedQueryString = '';  
        foreach($parameters as $key => $value) {  
            $canonicalizedQueryString .= '&' . self::percentEncode($key)  
                                      . '=' . self::percentEncode($value);  
        }  
        // 生成用于计算签名的字符串 stringToSign  
        $stringToSign = 'GET&%2F&' . self::percentencode(substr($canonicalizedQueryString, 1));  
        //echo "<br>".$stringToSign."<br>";  
        // 计算签名，注意accessKeySecret后面要加上字符'&'  
        $signature = base64_encode(hash_hmac('sha1', $stringToSign, $accessKeySecret . '&', true));
        
        return $signature;  
    }  
}
