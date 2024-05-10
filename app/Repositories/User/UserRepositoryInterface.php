<?php

namespace App\Repositories\User;

use App\Repositories\RepositoryInterface;

interface UserRepositoryInterface extends RepositoryInterface
{
    /**
     * Delete
     * @param $token
     * @return bool
     */
    public function verify($token);

    /**
     * Paginate
     * @param $filters
     * @param $role
     * @return mixed
     */
    public function paginate($filters, $role);

    /**
     * Get list
     * @param $filters
     * @param $role
     * @return mixed
     */
    public function getList($filters, $role);
}
