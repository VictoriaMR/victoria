<?php

namespace App\Service\Utils;

use QrCode;
use \App\Service\Utils\Url as UrlUtils;
use Illuminate\Support\Facades\URL;

/**
 * 图片处理相关工具方法
 *
 */
class Image
{
    /**
     * 生成二维码
     *
     * @param  string  $content 二维码内容
     * @param bool $temporary 是否为临时，默认为永久(生成静态文件)
     * @return string 若$temporary为false则返回二维码url，否则直接输出图片内容到浏览器
     */
    public static function createQrcode($content, $temporary=false, $size=200, $merge='')
    {
        $output = QrCode::format('png')
                ->encoding('UTF-8')
                ->size($size);

        if (!empty($merge)) $output = $output->merge($merge, 0.15)->margin(0);

        $output = $output->generate($content);

        //直接返回图片内容
        if ($temporary !== false) {
            return 'data:image/png;base64,' . base64_encode($output);
        }
        
        $attachmentService = \App::make('App\Service\Common\AttachmentService'); //实例化对像
        if ($temp = $attachmentService->getInfoByChecksum(sha1($output))) {
            return UrlUtils::getMediaUrl($temp['file_url']);
        }
        
        list($result,) = FileUploader::uploadByString($output, "qrcode.png", "qrcode");

        if (!empty($result)) {
            $attachmentService->create(1,
                                       \App\Model\Common\SystemAttachmentEntity::ENTITY_TYPE_QRCODE,
                                       $result['attachment_id']); //调用接口
            
            return $result['url'];
        }
        
        return null;
    }

    /**
     * 文件保存的是按照原始文件名保存
     * @author  wq 2017-6-21
     * @param  [string] $srcImg   [需要加水印的图片]
     * @param  [string] $waterImg [水印图片]
     * @param  [string] $savepath [保存的文件路径]
     * @param  [int]    $alpha    [水印的透明度]
     * @return [string] $savepath [返回生成的文件名]
     */
    public static function addWaterMark($srcImg, $waterImg, $alpha = 20)
    {
        $fileUrl = "app/".Http::download($srcImg);
        $srcImg = storage_path($fileUrl);
        //判断文件/是否存在
        if (!is_file($srcImg)) return false;
        if (!is_file($waterImg)) return  false;
        $srcinfo = @getimagesize($srcImg);
        $waterinfo = @getimagesize($waterImg);

        $type = image_type_to_extension($srcinfo[2], false);//获取图片类型
        $srcImgObj = self::imageCreateFromExt($srcImg);
        $waterImgObj =  self::imageCreateFromExt($waterImg);
          
        // 水印的重复显示，形成底纹
        $m = ceil($srcinfo[0]/$waterinfo[0]);
        $n = ceil($srcinfo[1]/$waterinfo[1]);
        for ($i = 0; $i <= $m ; $i++) { 
            for ($j=0; $j <= $n ; $j++) { 
                imagecopymerge($srcImgObj, $waterImgObj, ($waterinfo[0])*$i, ($waterinfo[1])*$j, 0, 0, $waterinfo[0], $waterinfo[1], $alpha);
            }
        }
        $func = "image{$type}";
        $func($srcImgObj, $srcImg);
        // $func($srcImgObj);//图片显示
        imagedestroy($srcImgObj);

        return URL::previous()."/storage/".$fileUrl;

    }

    /**
     * 图片打开一预处理
     * @author  wq 2017-6-21
     * @param  [string] $imgfile [文件名及地址]
     * @return [source] $image  [返回一图像标识符]
     */
    public static function imageCreateFromExt($imgfile)
    {
        $info = getimagesize($imgfile);
        $image = null;
        $type = image_type_to_extension($info[2],false);
        $fun = "imagecreatefrom{$type}";
        $image = $fun($imgfile);

        return $image;
    }

    /**
    * 图片转base64
    * @param ImageFile String 图片路径
    * @return 转为base64的图片
    */
    public static function Base64EncodeImage($ImageFile) 
    {
        if(file_exists($ImageFile) || is_file($ImageFile)){
            $base64_image = '';
            $image_info = getimagesize($ImageFile);
            $image_data = fread(fopen($ImageFile, 'r'), filesize($ImageFile));
            $base64_image = 'data:' . $image_info['mime'] . ';base64,' . chunk_split(base64_encode($image_data));
            return $base64_image;
        }
        else{
            return false;
        }
    }

    public static function urlExists($url) 
    {
        $ch = curl_init(); 
        curl_setopt ($ch, CURLOPT_URL, $url); 
        //不下载
        curl_setopt($ch, CURLOPT_NOBODY, 1);
        //设置超时
        curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 3); 
        curl_setopt($ch, CURLOPT_TIMEOUT, 3);
        curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);  
        if($http_code == 200) {
            return true;
        }
        return false;
    }
}
