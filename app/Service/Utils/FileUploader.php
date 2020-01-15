<?php
/**
 * 文件上传接口
 *
 * @date: 2018/05/04
 */

namespace App\Service\Utils;

use \Illuminate\Filesystem\Filesystem;

class FileUploader
{
    const FILE_TYPE = ['avatar', 'product'];

    /**
     * 通过文件路径上传
     *
     * @param string $filePath 待上传文件路径
     * @param string $module 图片业务类别（可不传）,比如头像 avatar 商品 product
     * @return mix 失败返回 false
     */
    public static function upload($file, $cate)
    {
        if (empty($file) || !in_array($cate, self::FILE_TYPE)) return false;

        $originalName = $file->getClientOriginalName(); //源文件名称
        $extension = $file->getClientOriginalExtension(); //后缀
        $tmpFile = $file->getRealPath(); //上传文件路径

        $hash = hash_file('crc32', $tmpFile); //生成文件hash值

        $insert = [
            'filename' => $originalName,
            'filetype' => $extension,
            'file_url' => $data['file_url'],
            'crc32' => $hash,,
        ];

        $systemAttachmentService = \App::make('App\Service\Common\SystemAttachmentService');

        print_r($hash);dd();
        return $result['data'];
    }

    /**
     * 通过文件内容上传
     *
     * @param string $content
     * @param string $module 图片业务类别，比如头像 avatar 商品 product
     * @return mix 失败返回 false
     */
    public static function uploadByString($content, $filename, $module)
    {
        $client = new \GuzzleHttp\Client([
            'base_uri' => env('FILE_UPLOAD_URL'),
            'timeout'  => 30.0,
        ]);
        
        $response = $client->request('POST', 'file/upload/save_path/' . $module, [
            'multipart' => [
                [
                    'name'     => $filename,
                    'contents' => $content,
                    'filename' => $filename,
                ]  
            ]
        ]);

        $result = json_decode($response->getBody(), true);


        if (empty($result) || $result['code'] != '200') return false;

        return $result['data'];
    }

    /**
     * 通过base64编码上传
     *
     * @param mix $file
     */
    public static function uploadByBase64String($file, $module='')
    {
        $client = new \GuzzleHttp\Client([
            'base_uri' => env('FILE_UPLOAD_URL'),
            'timeout'  => 30.0,
        ]);

        $params = [
            'upload_method' => 'base64'
        ];
        
        if (!is_array($file)) {
            $params['file'] = $file;
        } else {
            foreach ($file as $item) {
                $params['file[]'] = $item;
            }
        }
        
        $response = $client->request('POST', 'file/upload/save_path/' . $module, ['form_params' => $params]);

        $result = json_decode($response->getBody(), true);

        if (!empty($result) || $result['code'] != 't') return false;

        return $result['data'];
    }

    public static function uploadByUrl($url, $module='avatar')
    {
        $filename = Http::download($url);
        
        if (empty($filename)) return false;
        
        return self::upload(storage_path('app/{$filename}'), $module);
    }
}
