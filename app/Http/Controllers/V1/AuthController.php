<?php
/**
 * Created by PhpStorm.
 * User: lilong
 * Date: 2019/3/4
 * Time: 11:03
 */

namespace App\Http\Controllers\V1;


use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    protected $user;

    public function __construct(User $user)
    {
        $this->middleware('auth', ['only' => 'user']);
        $this->user = $user;
    }

    public function login(Request $request)
    {
        $credentials = [
            $this->user->username() => $request->input('username'),
            'password' => $request->input('password'),
            'channel' => 1,
            'ip' => $request->ip(),
            'timeout' => 1800
        ];

        if (Auth::validate($credentials)) {
            return response()->json([
                'code' => 200,
                'message' => '登录成功'
            ]);
        }
        return response()->json([
            'code' => 401,
            'message' => trans('auth.failed')
        ]);
    }

    public function user()
    {
        return Auth::user();
    }

    public function logout()
    {
        Auth::logout();
    }
}