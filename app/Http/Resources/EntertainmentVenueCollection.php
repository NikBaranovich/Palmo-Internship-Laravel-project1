<?php

namespace App\Http\Resources;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource as Resource;

class EntertainmentVenueCollection extends Resource
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        $hallsWithSessions = $this->resource->halls()
            ->with([
                'sessions' => function ($query) use ($request) {
                    $query->when($request->query('event'), function (Builder $query) use ($request) {
                        $query->where('event_id', $request->input('event'));
                    })
                        ->withMin('sessionSeatGroups', 'price')
                        ->withMax('sessionSeatGroups', 'price')
                        ->byStartDate($request->query('start_date'))
                        ->orderBy('start_time');
                }
            ])
            ->get(['id', 'number']);
        return [
            'id' => $this->resource->id,
            'name' => $this->resource->name,
            'city_id' => $this->resource->city_id,
            'city' => $this->resource->city->name,
            'address' => $this->resource->address,
            'description' => $this->resource->description,
            'venue_type_id' => $this->resource->venue_type_id,
            'venue_type' => $this->resource->venueType->name,
            'halls' => $hallsWithSessions,
        ];
    }
}
