<?php

namespace App\Services;

use App\Enums\User\UserStatus;
use App\Models\User;
use App\Notifications\SendMailVerifyAccount;
use App\Repositories\User\UserRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthService extends BaseService
{
    public function __construct(protected UserRepositoryInterface $userRepo)
    {
        //
    }

    public function login(): array
    {
        $credentials = $this->data;
        if (!$token = auth('api')->attempt($credentials)) {
            throw new \Exception(__('alert.auth.login.failed'));
        }
        $this->checkStatus(auth('api')->user()->status);
        $refreshToken = $this->createRefreshToken(auth('api')->user());
        return $this->respondWithToken($token, $refreshToken);
    }

    public function logout(): void
    {
        auth('api')->logout();
    }

    public function profile(): User
    {
        return auth('api')->user();
    }

    public function refreshToken($refreshToken): array
    {

        $check = $this->checkRefreshToken($refreshToken);
        if (!$check) {
            throw new \Exception('', __('alert.auth.refresh.failed'));
        }
        if (auth('api')->check()) {
            JWTAuth::invalidate();
        }
        $data = JWTAuth::getJWTProvider()->decode($refreshToken);
        $user = $this->userRepo->find($data['sub']);
        $token = auth('api')->login($user);
        return $this->respondWithToken($token, $refreshToken);
    }

    private function createRefreshToken($user): string
    {
        $refreshTtl = (int) config('jwt.refresh_ttl');
        return JWTAuth::customClaims([
            'exp' => Carbon::now()->addMinutes($refreshTtl)->timestamp
        ])->fromUser($user);
    }

    public function register($rq): User
    {
        $verifyToken = Str::random(40);
        $data = [
            ...$rq,
            'verify_token' => $verifyToken
        ];
        $user =  $this->userRepo->create($data);
        $user->notify(new SendMailVerifyAccount($user));
        return $user;
    }

    public function cofirmRegister(): void
    {
        $this->userRepo->verify($this->data['verify_token']);
        return;
    }

    private function checkRefreshToken($refreshToken): bool
    {
        $currentToken = JWTAuth::getToken();
        JWTAuth::setToken($refreshToken);
        $check = JWTAuth::check();
        if ($currentToken) {
            JWTAuth::setToken($currentToken);
        }
        return $check;
    }

    private function respondWithToken($token, $refreshToken): array
    {
        return [
            'access_token' => $token,
            'refresh_token' => $refreshToken,
            'token_type' => 'bearer',
            'expires_in' => config('jwt.ttl') * 60,
        ];
    }

    private function checkStatus($status): void
    {
        switch ($status) {
            case UserStatus::BLOCK:
                throw new \Exception(__('alert.auth.login.blocked'));
                return;
            case UserStatus::REGISTER:
                throw new \Exception(__('alert.auth.login.register'));
                return;
            default:
                return;
        }
    }
}
