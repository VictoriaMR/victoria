<?php

namespace App\Model;

use App\Model\BaseUser as BaseModel;

class AdminUser extends BaseModel
{
    //表名
    protected $table = 'admin_user';

    //主键
    protected $primaryKey = 'user_id';

    //不能被重置的字段
    protected $guarded = ['user_id'];

    //隐藏字段
    protected $hidden = ['password'];


}
