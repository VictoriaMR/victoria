<?php
/**
 * Http工具包.
 *
 * @autor: qiongyue
 * @date: 2018/07/17
 */

namespace App\Service\Utils;

class Http
{
    const TIMEOUT = 30.0;

    /**
     * 发起HTTP GET请求
     *
     * @param string $url      请求url
     * @param array  $params   POST参数
     * @param array  $option   http初始化参数
     * @param string $dataType 默认为 multipart 另外还支持 form_params
     *
     * @return \GuzzleHttp\Reponse 响应对象 获取响应状态 $status = $response->getStatusCode();获取响应内容 $content = $reponse->getBody();
     */
    public static function get($url, $params = [], $options = [])
    {
        $client = new \GuzzleHttp\Client(array_merge([
            'timeout' => self::TIMEOUT,
        ], $options));

        if (!empty($params)) {
            $response = $client->request('GET', $url, [
                'query' => $params,
            ]);
        } else {
            $response = $client->request('GET', $url);
        }

        return $response;
    }

    /**
     * 发起HTTP POST请求
     *
     * @param string $url      请求url
     * @param array  $option   http初始化参数
     * @param array  $params   POST参数
     * @param string $dataType 默认为 multipart 另外还支持 form_params
     *
     * @return \GuzzleHttp\Reponse 响应对象 获取响应状态 $status = $response->getStatusCode();获取响应内容 $content = $reponse->getBody();
     */
    public static function post($url, $params = [], $options = [], $dataType = 'multipart')
    {
        $client = new \GuzzleHttp\Client(array_merge([
            'timeout' => self::TIMEOUT,
        ], $options));

        $response = $client->request('POST', $url, [
            'form_params' => $params,
        ]);

        return $response;
    }

    public static function postRaw($url, $params = '', $options = [])
    {
        $client = new \GuzzleHttp\Client(array_merge([
            'timeout' => self::TIMEOUT,
        ], $options));

        $response = $client->request('POST', $url, [
            'body' => $params,
        ]);

        return $response;
    }

    public static function download($url, $dir = 'temp', $ext = 'png')
    {
        $url = trim($url);
        if (empty($url)) return false;
        
        $filename = basename($url);
        $temp = explode('.', $filename);
        if (sizeof($temp) == 2) {
            $destFilename = uniqid().'.'.array_pop($temp);
        } else {
            $destFilename = uniqid();
        }

        if (strpos($destFilename, '.') === false) {
            $destFilename .= '.'.$ext;
        }

        $destFilename = $dir .'/' . $destFilename;

        $tryCount = 3;
        while ($tryCount--) {
            try {
                if (\Storage::disk('local')->put($destFilename, self::get($url)->getBody())) {
                    return $destFilename;
                }
            } catch(\Exception $e ) {
                
            }
        }

        return false;
    }
}
