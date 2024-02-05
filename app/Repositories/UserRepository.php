<?php
namespace App\Repositories;

use App\Models\User;

class UserRepository extends BaseRepository
{
    public function __construct(
        protected User $user
    ) {}

    public function query()
    {
        return $this->user->query();
    }
}
