<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource as Resource;

class SessionCollection extends Resource
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'hall_id' => $this->resource->hall_id,
            'event_id' => $this->resource->event_id,
            'hall_item' => $this->resource->hall->makeHidden(['created_at', 'updated_at']),
            'hall' => $this->resource->hall->getEnabledItemsList(),
            'seat_groups' => SessionSeatGroupCollection::collection($this->resource->sessionSeatGroups()->get()),
            'city' => $this->resource->hall->entertainmentVenue->city->name,
            'venue' => $this->resource->hall->entertainmentVenue->makeHidden(['created_at', 'updated_at']),
            'event' => $this->resource->event,
            'start_time' => $this->resource->start_time,
            'end_time' => $this->resource->end_time
        ];
    }
}
