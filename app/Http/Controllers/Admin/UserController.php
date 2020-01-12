<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\BaseController;

class UserController extends BaseController
{
	/**
	 * @method 获取列表
	 * @author Victoria
	 * @date   2020-01-12
	 */
	public function getList(Request $request)
	{
		$result = $this->checkLogin($request);
        if (!$result) {
            return redirect('admin/login');
        }

		$name = $request->input('name', ''); // 名称
		$mobile = $request->input('mobile', ''); // 手机
		$page = $request->input('page', 1); // 当前页
		$pagesize = $request->input('pagesize', 20); // 每页数量

		$data = [];

		if (!empty($name)) {
			$data['name'] = $name;
		}

		if (!empty($mobile)) {
			$data['mobile'] = $mobile;
		}

		$list = $this->baseService->getList($data, $page, $pagesize);
		$list = array_merge($list, $request->all());

		$info = $this->baseService->getInfoCacheKey($request->session()->get('user_id'));

		return view('admin.user.list', $list);
	}

	/**
	 * @method 新增用户页面
	 * @author Victoria
	 * @date   2020-01-12
	 */
	public function addPage(Request $request)
	{
		$result = $this->checkLogin($request);
        if (!$result) {
            return redirect('admin/login');
        }

		return view('admin.user.add');
	}
}	