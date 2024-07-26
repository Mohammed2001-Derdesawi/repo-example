<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Requests\Api\Auth\RegisterRequest;
use App\Http\Requests\Api\Auth\UserResetRequest;
use App\Http\Requests\Api\Auth\UserConfirmRequest;
use App\Http\Requests\Api\Auth\UserChangePasswordRequest;
use App\Http\Services\Api\Auth\AuthService;

class AuthController extends Controller
{
    private AuthService $auth;
    public function __construct(AuthService $auth){
        $this->middleware('auth:api')->except(['login' , 'register','reset','resetUserconfirm','changePassword']);
        $this->auth = $auth;
    }

    public function login(LoginRequest $request){
        return $this->auth->login($request);
    }

    public function register(RegisterRequest $request){
        return $this->auth->register($request);
    }

    public function logout()
    {
        return $this->auth->logout();
    }

    public function refresh()
    {
        return $this->auth->refresh();
    }

    public function delete()
    {
        return $this->auth->delete();
    }

    public function reset(UserResetRequest $request)
    {
        return $this->auth->reset($request);
    }

    public function resetUserconfirm(UserConfirmRequest $request)
    {
        return $this->auth->resetUserconfirm($request);
    }

    public function changePassword(UserChangePasswordRequest $request)
    {
        return $this->auth->changePassword($request);
    }

}
