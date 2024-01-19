<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource as Resource;

class LayoutItemCollection extends Resource
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
            'number' => $this->resource->number,
            'seat_group_id' => $this->resource->seat_group_id,
            'type' => strtolower(class_basename($this->resource)),
            'color' => $this->resource->seatGroup?->color,
        ];
    }
}
