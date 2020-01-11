<?php 

namespace App\Service\Admin;

use App\Service\Common\MemberBaseService as BaseService;
use App\Model\AdminUser;

/**
 * 	后台用户类
 */
class UserService extends BaseService
{	
	protected static $constantMap = [
        'base' => AdminUser::class,
    ];

    public function __construct(AdminUser $model = null)
    {
        $this->baseModel = $model;
    }
}