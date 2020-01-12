<?php 

namespace App\Service;

use App\Service\Base as BaseService;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

/**
 * 	用户公共类
 */
class UserBaseService extends BaseService
{	
	/**
	 * @method 创建用户
	 * @author Victoria
	 * @date   2020-01-12
	 * @return boolean
	 */
	public function create($data)
	{
		if (empty($data['password'])) return false;
		$data['salt'] = $this->getSalt();
		$data['password'] = \Hash::make($this->getPasswd($data['password'], $data['salt']));

		return $this->baseModel->create($data);
	}
	/**
	 * @method 获取随机数
	 * @author Victoria
	 * @date   2020-01-10
	 * @return string salt
	 */
	public function getSalt($len = 4)
	{
     	return Str::random($len);
	}

	/**
	 * @method 获取密码与随机值的组合
	 * @author Victoria
	 * @date   2020-01-10
	 * @return string password
	 */
	public function getPasswd($password, $salt)
	{
		$passwordArr = str_split($password);
		$saltArr = str_split($salt);
		$countpwd = count($passwordArr);
		$countSalt = count($saltArr);

		$password = '';
		if ($countSalt > $countpwd) {
			foreach ($saltArr as $key => $value) {
				$password .= $passwordArr[$key] ?? '' . $value;
			}
		} else {
			$i = 0;
			$sign = floor($countpwd / $countSalt);
			foreach ($passwordArr as $key => $value) {
				$password .= $value;
				if ($key % $sign == 0) {
					if (empty($saltArr[$i])) $i = 0;

					$password .= $saltArr[$i];
					$i ++;
				}
			}
		}

		return $password;
	}

	/**
	 * @method 登陆
	 * @author Victoria
	 * @date   2020-01-11
	 * @param  string     $name    	名称或者手机号码
	 * @param  string     $password 密码
	 * @return array
	 */
	public function login($name, $password, $isToken = false, $isAdmin = false)
	{
		if (empty($name) || empty($password)) return false;

		if ($isAdmin)
			$info = $this->getInfoByName($name);
		else
			$info = $this->getInfoByMobile($name);

		if (empty($info)) return false;

		$password = $this->getPasswd($password, $info['salt']);

		if (\Hash::check($password, $info['password'])) {
			if ($isToken) { //生成token 返回
				return $this->generateToken($info['user_id']);
			} else { //生成session
				$data = [
					'user_id' => $info['user_id'],
					'name' => $info['name'],
					'nickname' => $info['nickname'],
					'is_super' => $info['is_super'],
				];
				session($data);
				return true;
			}
		}

		return false;
	}

	/**
	 * @method 通过用户ID生成token
	 * @author Victoria
	 * @date   2020-01-11
	 * @param  integer       $userId   
	 * @param  integer       $loginType
	 * @return array token
	 */
    public function generateToken($userId, $loginType=0)
    {
        $token = $refreshToken = null;
        $maxTrayCount = 10;
        
        $counter = 0;
        do {
            $token = Str::random(32);
        } while (Cache::get($token) && ($counter++) < $maxTrayCount);

        $counter = 0;
        do {
            $refreshToken = Str::random(32);
        } while (Cache::get($refreshToken) && ($counter++) < $maxTrayCount);

        if (empty($token) || empty($refreshToken)) return [];
        
        $expiresIn = self::constant('TOKEN_EXPIRED'); //6小时
        $refreshExpiresIn = self::constant('REFRESH_TOKEN_EXPIRED'); //15天
               
        Cache::put($token, join(':', [$userId, $loginType, $expiresIn, $refreshToken, 0, \Carbon\Carbon::now()]), $expiresIn);
        Cache::put($refreshToken, join(':', [$userId, $loginType, $refreshExpiresIn, $token, 1, \Carbon\Carbon::now()]), $refreshExpiresIn);

        // 记录用户token记录
        $this->recordToken(
            $userId,
            [$token, $expiresIn],
            [$refreshToken, $refreshExpiresIn]
        );
        
        return [
            'access_token' => $token,
            'refresh_token' => $refreshToken,
            'expires_in' => $expiresIn * 60, // 换成秒
        ];
    }

    /**
     * 记录token记录
     * @author Victoria
	 * @date   2020-01-11
	 * @param  integer       $userId   
	 * @return array token
	 */
    protected function recordToken($userId, $tokenInfo, $refreshTokenInfo)
    {
        if (empty($userId)) return ;
        
        $tokenKey = 'token:' . $userId;
        $refreshTokenKey = 'refreshToken:' . $userId;

        $tokenList = Cache::get($tokenKey);
        $refreshTokenList = Cache::get($refreshTokenKey);

        if (empty($tokenList)) $tokenList = [];
        if (empty($refreshTokenList)) $refreshTokenList = [];

        list($token, $tokenExpiresIn) = $tokenInfo;
        list($refreshToken, $refreshTokenExpiresIn) = $refreshTokenInfo;
        
        $tokenList[] = $token;
        $refreshTokenList[] = $refreshToken;

        Cache::put($tokenKey, $tokenList, $tokenExpiresIn);
        Cache::put($refreshTokenKey, $refreshTokenList, $refreshTokenExpiresIn);

        return true;
    }

	/**
	 * @method 根据名称或者手机号码获取信息
	 * @author Victoria
	 * @date   2020-01-11
	 * @return array
	 */
	public function getInfoByName($name)
	{
		if (empty($name)) return [];

		return $this->baseModel->getInfoByName($name);
	}

	/**
	 * @method 根据手机号码获取信息
	 * @author Victoria
	 * @date   2020-01-11
	 * @return array
	 */
	public function getInfoByMobile($name)
	{
		if (empty($name)) return [];

		return $this->baseModel->getInfoByMobile($name);
	}

	/**
	 * @method 检查用户存在
	 * @author Victoria
	 * @date   2020-01-11
	 * @return boolean
	 */
	public function isExistUserByName($name) 
	{
		return $this->baseModel->isExistUserByName($name);
	}

	/**
	 * @method 检查用户存在
	 * @author Victoria
	 * @date   2020-01-11
	 * @return boolean
	 */
	public function isExistUserByMobile($name) 
	{
		return $this->baseModel->isExistUserByMobile($name);
	}

	/**
     * 通过ID获取用户信息
     * 
     * @param string $userId
     * @return array 用户信息
     */
    public function getInfo($userId)
    {
        return $this->baseModel->getInfo($userId);
    }

    /**
     * @method 获取缓存数据
     * @author Victoria
     * @date   2020-01-12
     * @param  integer      $userId 
     * @return array
     */
    public function getInfoCache($userId)
    {
        $cacheKey = $this->getInfoCacheKey($userId);

        if ($info = Cache::get($cacheKey)) {
            return $info;
        } else {
            $info = $this->getInfo($userId);

            Cache::put($cacheKey, $info, self::constant('INFO_CACHE_TIMEOUT'));

            return $info;
        }
    }

    /**
     * @method 缓存key
     * @author Victoria
     * @date   2020-01-12
     * @param  string      $userId 
     * @return string cachekey
     */
    public function getInfoCacheKey($userId)
    {
        return 'MEMBER_INFO_CACHE_' . $userId;
    }

    /**
     * @method 清除缓存
     * @author Victoria
     * @date   2020-01-12
     * @param  integer      $userId 
     * @return boolean
     */
    public function clearCacheKey($userId)
    {
        $cacheKey = $this->getInfoCacheKey($userId);
        $info = $this->getInfo($userId);
        return Cache::foget($cacheKey);
    }

    /**
     * @method 获取用户列表
     * @author Victoria
     * @date   2020-01-12
     * @return array list
     */
    public function getList($data, $page = 1, $pagesize = 20)
    {
    	$filter = [];

    	if (!empty($data['name'])) {
    		$filter[] = ['name', 'like', '%'.$data['name'].'%'];
    	}
    	if (!empty($data['mobile'])) {
    		$filter[] = ['mobile', 'like', '%'.$data['mobile'].'%'];
    	}

    	$total = $this->getDataList(true, $filter);

    	if ($total > 0) {
    		$list = $this->getDataList(false, $filter, ['page' => (int) $page, 'pagesize' => (int) $pagesize], [['created_at', 'desc']]);
    	}

    	return $this->getPaginationList($list ?? [], $total, $page, $pagesize);
    }
}