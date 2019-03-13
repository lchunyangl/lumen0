<?php
/**
 * Created by PhpStorm.
 * User: lilong
 * Date: 2019/2/28
 * Time: 11:17
 */

namespace App\Extensions\Auth;


use App\Services\ProxyService;
use Illuminate\Auth\GuardHelpers;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;

class ProxyGuard implements Guard
{
    use GuardHelpers;

    protected $request;

    protected $token;

    protected $proxy;

    public function __construct(Request $request, ProxyService $proxy)
    {
        $this->request = $request;
        $this->proxy = $proxy;
    }

    /**
     * @return Authenticatable|null
     * 获取认证会员信息
     */
    public function user()
    {
        if (!is_null($this->user)) {
            return $this->user;
        }
        $user = $this->proxy->user(is_null($this->token) ? $this->request->cookie(config('web.cookie')) : $this->token);
        if (is_null($user)) {
            return null;
        }
        $this->setUser($user);
        return $this->user;
    }

    /**
     * 注销登录
     */
    public function logout()
    {
        if (!is_null($this->user())) {
            $this->proxy->logout($this->request->cookie(config('web.cookie')));
        }
        $this->user = null;
    }

    /**
     * @param array $credentials
     * @return bool
     * 验证会员登录信息
     */
    public function validate(array $credentials = [])
    {
        if ($this->token = $this->proxy->login($credentials)) {
            return true;
        }

        return false;
    }

    /**
     * @return mixed
     * 获取认证会员token
     */
    public function token()
    {
        return $this->token;
    }

}
