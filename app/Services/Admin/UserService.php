<?php

namespace App\Services\Admin;

use App\Enums\User\UserRole;
use App\Models\User;
use App\Notifications\SendMailResetPassword;
use App\Repositories\User\UserRepositoryInterface;
use App\Services\BaseService;
use App\Services\ExcelService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserService extends BaseService
{
    public function __construct(
        protected UserRepositoryInterface $userRep,
        protected ExcelService $excelSer,
    ) {
        //
    }

    public function paginate($rq)
    {
        return $this->userRep->paginate($rq, $role = UserRole::USER);
    }

    public function update($id)
    {
        return $this->userRep->update($id, $this->data);
    }

    public function resetPassword(User $user)
    {
        $newPass = Str::random(10);
        $user->notify(new SendMailResetPassword($newPass));
        return $this->userRep->update($user->id, ['password' => Hash::make($newPass)]);
    }

    public function export($filters)
    {
        $users = $this->userRep->getList($filters, $role = UserRole::USER);
        return $this->excelSer->exportUsers($users);
    }
}
