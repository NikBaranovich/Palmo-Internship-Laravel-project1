<?php
namespace App\Repositories;

use Illuminate\Database\Eloquent\Builder;

class BaseRepository
{
    public const PER_PAGE = 10;

    public function sort($sortBy = null, $sortOrder = 'asc')
    {
        return $this->query()
            ->when($sortBy, function (Builder $query) use ($sortBy, $sortOrder) {
                $query->orderBy($sortBy, $sortOrder);
            })
            ->paginate(self::PER_PAGE);
    }
}
