<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\BaseController;

class IndexController extends BaseController
{
    /**
     * @method 首页入口
     * @author Victoria
     * @date   2020-01-12
     */
    public function index(Request $request)
    {
        $result = $this->checkLogin($request);
        if (!$result) {
            return redirect('admin/login');
        }
        $data = [
            'title' => '首页',
            'admin_url' => config('service.domain.admin')
        ];
        return view('admin.index', $data);
    }

    /**
     * @method 概述
     * @author Victoria
     * @date   2020-01-12
     * @param  Request    $request [description]
     * @return [type]              [description]
     */
    public function overview(Request $request)
    {
        $result = $this->checkLogin($request);
        if (!$result) {
            return redirect('admin/login');
        }
        $data = [];
        //管理员信息
        $userInfo = $this->baseService->getInfoCache($request->session()->get('user_id'));
        // 系统信息
        $systemInfo = $this->getSystemInfo();
        $data = array_merge($data, $userInfo->toArray(), $systemInfo);

        return view('admin.overview', $data);
    }


}
