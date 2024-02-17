<?php
namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class UserRepository extends BaseRepository
{
    public function __construct(
        protected User $user
    ) {}

    public function sort($sortBy = null, $sortOrder = 'asc')
    {
        $sortableColumns = ['id', 'name', 'email', 'role'];

        return $this->query()
            ->when($sortBy && in_array($sortBy, $sortableColumns), function (Builder $query) use ($sortBy, $sortOrder) {
                $query->orderBy($sortBy, $sortOrder);
            })
            ->paginate(self::PER_PAGE);
    }

    public function query()
    {
        return $this->user->query();
    }
}
