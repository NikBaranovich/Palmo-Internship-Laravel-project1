<?php

namespace App\Http\Resources;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource as Resource;

class SessionSeatGroupCollection extends Resource
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
            'price' => $this->resource->price,
            'color' => $this->resource->seatGroup->color,
            'name' => $this->resource->seatGroup->name,
            'number' => $this->resource->seatGroup->number,
            'seats_count' => $this->resource->seatGroup->seats()->count(),
            'enabled_seats_count' => $this->resource->seatGroup
                ->seats()
                ->whereDoesntHave('tickets', function (Builder $query) {
                    $query
                        ->where('session_id', $this->resource->session_id);
                })->count()
        ];
    }
}
