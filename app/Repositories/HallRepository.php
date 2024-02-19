<?php

namespace App\Repositories;

use App\Models\EntertainmentVenue;
use App\Models\Hall;
use GuzzleHttp\Psr7\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request as HttpRequest;

class HallRepository extends BaseRepository
{
    public function __construct(
        protected Hall $hall
    ) {
    }

    public function index(HttpRequest $request, EntertainmentVenue $entertainmentVenue)
    {
        $sortableColumns = ['id', 'number'];

        return $this->query()
            ->byEntertainmentVenue($entertainmentVenue->id)
            ->when(
                $request->has('sort_by') && in_array($request->input('sort_by'), $sortableColumns),
                function (Builder $query) use ($request) {
                    $sortBy = $request->input('sort_by');
                    $sortOrder = $request->input('sort_order', 'asc');

                    $query->orderBy($sortBy, $sortOrder);
                }
            )
            ->paginate(10);
    }

    public function query()
    {
        return $this->hall->query();
    }
}
