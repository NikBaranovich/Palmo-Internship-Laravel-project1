<?php

namespace App\Repositories;

use App\Models\Session;
use Illuminate\Database\Eloquent\Builder;

class SessionRepository extends BaseRepository
{
    public function __construct(
        protected Session $session
    ) {
    }

    public function query()
    {
        return $this->session->query();
    }

    public function sort($sortBy = null, $sortOrder = 'asc')
    {
        return $this->query()
            ->when(
                $sortBy,
                function (Builder $query) use ($sortBy, $sortOrder) {
                    switch ($sortBy) {
                        case 'event':
                            $query->withAggregate('event', 'title')
                                ->orderBy('event_title', $sortOrder);
                            break;
                        case 'venue':
                            $query->whereHas('hall', function ($query) {
                                $query->withAggregate('entertainmentVenue', 'name')->groupBy('entertainment_venue_name');
                            });
                            break;
                        default:
                            $query->orderBy($sortBy, $sortOrder);
                    }
                }
            )
            ->paginate(self::PER_PAGE);
    }
}
