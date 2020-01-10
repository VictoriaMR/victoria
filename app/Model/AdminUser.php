<?php

namespace App\Model;

use App\Model\Base as BaseModel;

class AdminUser extends BaseModel
{
    //表名
    protected $table = 'admin_user';

    //主键
    protected $primary_key = 'user_id';

    //不能被重置的字段
    protected $guarded = ['user_id'];

    //隐藏字段
    protected $hidden = ['passage', 'remember_token'];

    /**
     * @method 创建
     * @author Victoria
     * @date   2020-01-09
     * @param  array     $data 
     * @return integer  用户ID
     */
    public function create($data)
    {
    	if (empty($data['moblie']) || empty($data['name'])) return false;

    	if ($this->isExistUser($data['moblie'])) return false;

    	$insertData = [
    		'moblie' => $data['moblie'],
    		'name' => $data['name'],
    		'nickname' => $data['nickname'] ?? '',
    		'sex' => $data['sex'] ?? 0,
    	];

    	return $this->insertGetId($insertData);
    }
	
	/**
	 * @method 检查用户
	 * @author Victoria
	 * @date   2020-01-09
	 * @param  string    $moblie 
	 * @return boolean 
	 */
    public function isExistUser($moblie)
    {
    	if (empty($moblie)) return false;

    	return $this->where('moblie', $moblie)->count() > 0;
    }
}
