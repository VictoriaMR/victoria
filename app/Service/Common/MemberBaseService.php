<?php 

namespace App\Service\Common;

use App\Service\Base as BaseService;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;

/**
 * 	用户公共类
 */
class MemberBaseService extends BaseService
{	
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
}