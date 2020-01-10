<?php

namespace App\Model\Stat;

use App\Model\Base as BaseModel;

class BaseLogger extends BaseModel
{
    //链接的数据库
    protected $connection = 'stat_db';

    //表名
    protected $table = 'base_logger';

    //主键
    protected $primary_key = 'log_id';

    //不能被重置的字段
    protected $guarded = ['log_id'];

    const TYPE_DEFAULT = 0; //默认类型 用户
    const TYPE_ADMIN_USER = 1; //系统管理员
}
