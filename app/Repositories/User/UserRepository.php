<?php

namespace App\Repositories\User;

use App\Enums\User\UserStatus;
use App\Models\User;
use App\Repositories\BaseRepository;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public function getModel()
    {
        return User::class;
    }

    public function verify($token): bool
    {
        return (bool) $this->model->where('verify_token', $token)
            ->status(UserStatus::REGISTER)
            ->update([
                'status' => UserStatus::ACTIVE,
                'verify_token' => null,
            ]);
    }

    public function baseList($filters, $role)
    {
        return $this->model
            ->role([$role])
            ->when(isset($filters['name']), function ($query) use ($filters) {
                return $query->where('name', 'like', '%' . $filters['name'] . '%');
            })
            ->when(isset($filters['email']), function ($query) use ($filters) {
                return $query->where('email', 'like', '%' . $filters['email'] . '%');
            })
            ->when(isset($filters['phone_number']), function ($query) use ($filters) {
                return $query->where('phone_number', 'like', '%' . $filters['phone_number'] . '%');
            })
            ->when(isset($filters['status']), function ($query) use ($filters) {
                return $query->status($filters['status']);
            })
            ->when(isset($filters['sort']), function ($query) use ($filters) {
                return $query->modelSort($filters['sort']);
            });
    }

    public function paginate($filters, $role)
    {
        return $this->baseList($filters, $role)
            ->paginate($filters['per_page'] ?? null);
    }

    public function getList($filters, $role)
    {
        return $this->baseList($filters, $role)->get();
    }
}
