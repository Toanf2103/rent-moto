<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseApiController;
use App\Http\Requests\Admin\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\Admin\UserService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Throwable;

class UserController extends BaseApiController
{
    public function __construct(protected UserService $userSer)
    {
        //
    }

    public function index(Request $rq)
    {
        try {
            $users = $this->userSer->paginate($rq->all());
            return $this->sendResourceResponse(
                UserResource::collection($users)
            );
        } catch (Throwable $e) {
            Log::error($e);
            return $this->sendError(__('alert.params.invalid'), Response::HTTP_BAD_REQUEST);
        }
    }

    public function store(Request $request)
    {
        //
    }

    public function show(User $user)
    {
        return $this->sendResponse(
            UserResource::make($user)
        );
    }

    public function update(UserUpdateRequest $rq, User $user)
    {
        try {
            $this->userSer->setRequest($rq)->update($user->id);
            return $this->sendResponse([
                'message' => __('alert.update_successful')
            ], Response::HTTP_CREATED);
        } catch (Throwable $e) {
            Log::error($e);
            return $this->sendError($e->getMessage());
        }
    }

    public function resetPassword(User $user)
    {
        try {
            $this->userSer->resetPassword($user);
            return $this->sendResponse([
                'message' => __('alert.auth.reset_password.success')
            ]);
        } catch (Throwable $e) {
            Log::error($e);
            return $this->sendError($e->getMessage());
        }
    }

    public function export(Request $rq)
    {
        try {
            return $this->sendCsvResponse($this->userSer->export($rq->all()), 'orders.csv');
        } catch (Throwable $e) {
            Log::error($e);
            return $this->sendError($e->getMessage());
        }
    }
}
