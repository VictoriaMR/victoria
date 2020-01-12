<?php

namespace App\Model;

use App\Model\Base as BaseModel;

class BaseUser extends BaseModel
{
	const TOKEN_EXPIRED = 60 * 6 * 60; // 6小时
    const REFRESH_TOKEN_EXPIRED = 60 * 24 * 15 * 60; // 15天
    const INFO_CACHE_TIMEOUT = 60 * 60;//1天

	/**
     * @method 创建
     * @author Victoria
     * @date   2020-01-09
     * @param  array     $data 
     * @return integer  用户ID
     */
    public function create($data)
    {
    	if (empty($data['mobile']) || empty($data['name'])) return false;

    	if ($this->isExistUserByMobile($data['mobile'])) return false;

        $data['sex'] = $data['sex'] ?? 0;
    	$insertData = [
    		'mobile' => $data['mobile'],
    		'name' => $data['name'],
    		'nickname' => $data['nickname'] ?? '',
    		'sex' => (int) $data['sex'],
            'salt' => $data['salt'] ?? '',
            'password' => $data['password'] ?? '',
            'created_at' => \Carbon\Carbon::now(),
    	];

        if (!empty($data['is_super']))
            $insertData['is_super'] = (int) $data['is_super'];

    	return $this->insertGetId($insertData);
    }
	
	/**
	 * @method 检查用户
	 * @author Victoria
	 * @date   2020-01-09
	 * @param  string    $mobile 
	 * @return boolean 
	 */
    public function isExistUserByMobile($mobile)
    {
    	if (empty($mobile)) return false;

    	return $this->where('mobile', $mobile)->count() > 0;
    }

    /**
	 * @method 检查用户
	 * @author Victoria
	 * @date   2020-01-09
	 * @param  string    $name 
	 * @return boolean 
	 */
    public function isExistUserByName($name)
    {
    	if (empty($name)) return false;

    	return $this->where('name', $name)->orWhere('mobile', $name)->count() > 0;
    }

    /**
     * @method 根据名称或者手机号码获取信息
     * @author Victoria
     * @date   2020-01-11
     * @return array
     */
    public function getInfoByName($name)
    {
        return $this->where('name', $name)->orWhere('mobile', $name)->first();
    }

    /**
     * @method 根据手机号码获取信息
     * @author Victoria
     * @date   2020-01-11
     * @return array
     */
    public function getInfoByMobile($name)
    {
        return $this->where('mobile', $name)->first();
    }

    /**
     * @method 获取用户数据
     * @author Victoria
     * @date   2020-01-12
     * @return array
     */
    public function getInfo($userId)
    {
        if (empty($userId)) return [];

        return $this->loadData($userId);
    }
}