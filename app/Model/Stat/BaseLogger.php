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

    const TYPE_ADMIN_USER_LOGGIN = 11; //系统管理员登陆日志

    /**
     * @method 新增日志
     * @author Victoria
     * @date   2020-01-12
     * @return boolean
     */
    public function create($data)
    {
        if (empty($data)) return false;

        $insert = [
            'target_id' => $data['target_id'],
            'entity_id' => $data['entity_id'] ?? '',
            'type_id' => $data['type_id'] ?? null,
            'raw_data' => !empty($data['raw_data']) ? json_encode($data['raw_data']) : null,
            'created_at' => \Carbon\Carbon::now()
        ];

        return $this->insertGetId($insert);
    }
}
