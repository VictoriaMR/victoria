<?php
/**
 * 字符处理相关封装
 *
 * @date: 2018/05/04
 */

namespace App\Service\Utils;

class Member
{
    public static function getInfo($externalMemberId)
    {
        return self::factory($externalMemberId)
            ->getInfo($externalMemberId);
    }

    public static function getInfoCache($externalMemberId)
    {
        return self::factory($externalMemberId)
            ->getInfoCache($externalMemberId);
    }

    public static function getSimpleInfo($externalMemberId)
    {
        return self::factory($externalMemberId)
            ->getSimpleInfo($externalMemberId);
    }

    public static function getSimpleInfoCache($externalMemberId)
    {
        return self::factory($externalMemberId)
            ->getSimpleInfoCache($externalMemberId);
    }

    public static function getUsertypeByExternalMemberId($externalMemberId)
    {
        return substr($externalMemberId, 0, 1);
    }

    public static function getServiceByExternalMemberId($externalMemberId)
    {
        return self::factory($externalMemberId);
    }

    public static function getServiceByUserType($userType)
    {
        return self::factory($userType);
    }

    public static function getIdListByKeyword($keyword, $limit=5)
    {
        $idList = [];

        foreach ([\App\Service\Member\MemberService::constant('TYPE_ID_STUDENT'),
                  \App\Service\Member\MemberService::constant('TYPE_ID_CONSULTANT'),
                  \App\Service\Member\MemberService::constant('TYPE_ID_INTERNAL'),
        ] as $typeId) {
            $memberService = self::factory($typeId);
            $temp = $memberService->getIdListByKeyword($keyword, $limit);

            $idList = array_merge($idList, $temp);
        }

        return $idList;
    }
    
    protected static function factory($externalMemberId)
    {
        $letter = substr($externalMemberId, 0, 1);

        if ($letter == \App\Service\Member\MemberService::constant('TYPE_ID_STUDENT'))
            return \App::make('App\Service\Member\MemberService');
        else if ($letter == \App\Service\Member\MemberService::constant('TYPE_ID_CONSULTANT'))
            return \App::make('App\Service\Consultant\ConsultantService');
        else if ($letter == \App\Service\Member\MemberService::constant('TYPE_ID_INTERNAL'))
            return \App::make('App\Service\User\UserService');
        else 
            return \App::make('App\Service\Member\MemberService');
    }
}
