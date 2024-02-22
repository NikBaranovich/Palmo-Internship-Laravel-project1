<?php

namespace App\Repositories;

use App\Models\Event;
use Illuminate\Database\Eloquent\Builder;

class EventRepository extends BaseRepository
{
    public function __construct(
        protected Event $event
    ) {
    }

    public function query()
    {
        return $this->event->query();
    }

    public function sort($sortBy = null, $sortOrder = 'asc')
    {
        return $this->query()
            ->when(
                $sortBy,
                function (Builder $query) use ($sortBy, $sortOrder) {
                    switch ($sortBy) {
                        case 'type':
                            $query->withAggregate('eventType', 'name')
                                ->orderBy('event_type_name', $sortOrder);
                            break;
                        default:
                            $query->orderBy($sortBy, $sortOrder);
                    }
                }
            )
            ->paginate(self::PER_PAGE);
    }
}
