<?php

namespace App\Http\Controllers\Api\Common;

use App\Http\Controllers\Api\ApiController as BaseController;
use Illuminate\Http\Request;

class FileController extends BaseController
{
    /**
     * @method 文件上传
     * @author Victoria
     * @date   2020-01-14
     */
    public function upload(Request $request)
    {
    	$file = $request->file('file'); //长传文件
    	$cate = $request->input('cate', ''); //类型

    	if (empty($file))
    		return $this->getResult(100000, false, ['message' => '文件不能为空!']);

    	if (empty($cate))
    		return $this->getResult(100000, false, ['message' => '类型不能为空!']);

    	$result = \App\Service\Utils\FileUploader::upload($file, $cate);

    	if ($result)
    		return $this->getResult(200, $result, ['message' => '上传成功']);
    	else
    		return $this->getResult(100000, $result, ['message' => '上传失败']);

    }
}