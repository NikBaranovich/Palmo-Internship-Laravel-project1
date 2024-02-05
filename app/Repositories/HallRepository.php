<?php

namespace App\Repositories;

use App\Models\Hall;

class HallRepository extends BaseRepository
{
    public function __construct(
        protected Hall $hall
    ) {
    }

    public function index(int|string $entertainmentVenue)
    {
        return $this->query()
            ->byEntertainmentVenue($entertainmentVenue)
            ->get();
    }

    public function query()
    {
        return $this->hall->query();
    }
}
