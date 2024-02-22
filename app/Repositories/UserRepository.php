<?php
namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

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
