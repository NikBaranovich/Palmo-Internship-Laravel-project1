<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;

class UserService extends BaseService
{
    public function __construct(
        protected UserRepository $repository
    ) {
    }

    public function save($request, User $user)
    {
        $user->fill($request->only($user->getFillable()));

        return $user->save();
    }

    public function delete(User $user)
    {
        return $user->delete();
    }
}
