<?php

namespace App\Repositories;

use App\Models\EntertainmentVenue;
use Illuminate\Database\Eloquent\Builder;

class EntertainmentVenueRepository extends BaseRepository
{
    public function __construct(
        protected EntertainmentVenue $entertainmentVenue
    ) {
    }

    public function query()
    {
        return $this->entertainmentVenue->query();
    }

    public function sort($sortBy = null, $sortOrder = 'asc')
    {
        return $this->query()
            ->when(
                $sortBy,
                function (Builder $query) use ($sortBy, $sortOrder) {

                    switch ($sortBy) {
                        case 'city':
                            $query->withAggregate('city', 'name')
                                ->orderBy('city_name', $sortOrder);
                            break;
                        case 'type':
                            $query->withAggregate('venueType', 'name')
                                ->orderBy('venue_type_name', $sortOrder);
                            break;
                        default:
                            $query->orderBy($sortBy, $sortOrder);
                    }
                }
            )
            ->paginate(self::PER_PAGE);
    }
}
