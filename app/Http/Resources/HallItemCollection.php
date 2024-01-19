<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource as Resource;

class HallItemCollection extends Resource
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
            'layout' => $this->resource->layout,
            'seat_groups' => $this->resource->seatGroups()->get(['id', 'name', 'number', 'color']),
            'elements' => $this->resource->getItemsList(),
        ];
    }
}
