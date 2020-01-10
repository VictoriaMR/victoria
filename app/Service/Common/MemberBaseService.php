<?php 

namespace App\Service\Common;

use App\Service\Base as BaseService;

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
		$string = 'abcdefghijklnmopqretuvwxyz0123456789';

		$chars = str_split($string);
     	shuffle($chars);
		$charsLen = count($chars) - 1;

		$returnString = '';
     	for($i = 0; $i < $len; $i ++){
         	$returnString .= $chars[mt_rand(0, $charsLen)];  
     	}

     	return $returnString;
	}

	/**
	 * @method 获取密码与随机值的组合
	 * @author Victoria
	 * @date   2020-01-10
	 * @return string passwd
	 */
	public function getPasswd($passwd, $salt)
	{
		$passwdArr = str_split($passwd);
		$saltArr = str_split($salt);
		$countpwd = count($passwdArr);
		$countSalt = count($saltArr);

		$passwd = '';
		if ($countSalt > $countpwd) {
			foreach ($saltArr as $key => $value) {
				$passwd .= $passwdArr[$key] ?? '' . $value;
			}
		} else {
			$sign = floor($countpwd / $countSalt);
			foreach ($passwdArr as $key => $value) {
				$passwd .= $value;
				if ($key % $sign == 0) {
					$passwd .= $saltArr[$key % $sign -1];
				}
			}
		}

		return $passwd;
	}
}