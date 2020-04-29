<?php

namespace App\Http\Middleware;

use App\Exceptions\TokenMismatchException;
use App\Exceptions\MemberAgreementException;
use App\Exceptions\MemberPremissionException;
use App\Service\UserBaseService;
use Closure;

/**
 * 验证token.
 */
class VerifyToken
{
    /**
     * The URIs that should be excluded from token verification.
     *
     * @var array
     */
    protected $except = [
    ];

    protected $exceptNotToken = [
    ];

    protected $exceptNotAgreement = [
    ];

    protected $memberService;

    public function __construct(UserBaseService $member)
    {
        $this->memberService = $member;
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        return;
        // if ($this->inExceptArray($request) || $this->tokenMatch($request)) {
        //     // 获取用户ID
        //     $token = $this->getTokenFromRequest($request);
        //     $externalMemberId = 0;
        //     if (!$this->inExceptArray($request) && !empty($token) && $token != 'null') list($externalMemberId) = $this->memberService->checkToken($token);

        //     if (!empty($externalMemberId) && (substr($externalMemberId, 0, 1) == 1)) {
        //         if (!$this->inExceptByNotAgreementArray($request)) {
        //             $memberService = \App::make('App\Service\Member\MemberService');
        //             $agreementStatus = $memberService->getAgreementStatus($externalMemberId);
        //             if ($agreementStatus !== 1)  throw new MemberAgreementException();
        //             $permissionService = \App::make('App\Service\Consultant\PermissionService');
        //             $result = $permissionService->getStatusByType($externalMemberId, $permissionService::constant('PERMISSION_KEY_LOGIN'));
        //             if (!$result['status']) throw new MemberPremissionException();                      
        //         }
        //     }

        //     return $next($request);
        // }

        // throw new TokenMismatchException();
    }

    /**
     * Determine if the request has a URI that should pass through token verification.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return bool
     */
    protected function inExceptArray($request)
    {
        foreach ($this->except as $except) {
            if ($except !== '/') {
                $except = trim($except, '/');
            }

            if ($request->is($except)) {
                return true;
            }
        }

        return false;
    }

    protected function inExceptByNotTokenArray($request)
    {
        foreach ($this->exceptNotToken as $except) {
            if ($except !== '/') {
                $except = trim($except, '/');
            }

            if ($request->is($except)) {
                return true;
            }
        }

        return false;
    }

    protected function inExceptByNotAgreementArray($request)
    {
        foreach ($this->exceptNotAgreement as $except) {
            if ($except !== '/') {
                $except = trim($except, '/');
            }

            if ($request->is($except)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Validate the token.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return bool
     */
    protected function tokenMatch($request)
    {
        $token = $this->getTokenFromRequest($request);
        if ($this->inExceptByNotTokenArray($request) && (empty($token) || $token == 'null')) return true;

        list($externalMemberId) = $this->memberService->checkToken($token);
        if (empty($externalMemberId)) {
            return false;
        }

        return $externalMemberId;
    }

    /**
     * Get the token from the request.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return string
     */
    protected function getTokenFromRequest($request)
    {
        $token = $request->input('token') ?: $request->header('X-AUTH-ACCESS-TOKEN');

        if (empty($token)) {
            $token = $request->input('access_token');
        }

        return $token;
    }
}
