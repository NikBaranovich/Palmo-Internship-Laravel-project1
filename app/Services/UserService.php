<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Auth;

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
