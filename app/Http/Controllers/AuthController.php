<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\VerifyAccountRequest;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Throwable;

class AuthController extends BaseApiController
{
    public function __construct(protected AuthService $authSer)
    {
        //
    }

    public function login(LoginRequest $rq)
    {
        try {
            $data = $this->authSer->setRequest($rq)->login();
            return $this->sendResponse($data);
        } catch (Throwable $e) {
            return $this->sendError($e->getMessage(), Response::HTTP_UNAUTHORIZED);
        }
    }

    public function logout()
    {
        $this->authSer->logout();
    }

    public function profile()
    {
        try {
            $user = $this->authSer->profile();
            return $this->sendResponse(
                UserResource::make($user)
            );
        } catch (Throwable $e) {
            Log::error($e);
            return $this->sendError();
        }
    }

    public function refeshToken(Request $rq)
    {
        try {
            $data = $this->authSer->refreshToken($rq->get('refresh_token'));
            return $this->sendResponse($data);
        } catch (Throwable $e) {
            return $this->sendError($e->getMessage(), Response::HTTP_UNAUTHORIZED);
        }
    }

    public function register(RegisterRequest $rq)
    {
        try {
            $this->authSer->register($rq->validated());
            return $this->sendResponse([
                'message' => __('alert.auth.register.success')
            ]);
        } catch (Throwable $e) {
            return $this->sendError($e, Response::HTTP_UNAUTHORIZED);
        }
    }

    public function cofirmRegister(VerifyAccountRequest $rq)
    {
        $this->authSer->setRequest($rq)->cofirmRegister();
        return $this->sendResponse([
            'message' => __('alert.auth.verify.success')
        ]);
    }
}
