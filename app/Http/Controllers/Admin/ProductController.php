<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\BaseController;

class ProductController extends BaseController
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

		return view('admin.product.add');
	}
}	