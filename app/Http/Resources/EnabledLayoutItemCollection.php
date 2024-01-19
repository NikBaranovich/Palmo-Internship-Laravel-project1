<?php

namespace App\Http\Resources;

use App\Models\Session;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Builder as Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource as Resource;

class EnabledLayoutItemCollection extends Resource
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        // $hallId = Session::query()
        //     ->where('id', request()->query('session_id'))
        //     ->value('hall_id');

        $data = [
            'id' => $this->resource->id,
            'number' => $this->resource->number,
            'seat_group_id' => $this->resource->seat_group_id,
            'type' => strtolower(class_basename($this->resource)),
            'color' => $this->resource->seatGroup?->color,
            'group_name' => $this->resource->seatGroup?->name,
            'group_number' => $this->resource->seatGroup?->number,
        ];

        if ($data['type'] === 'seat') {
            $data['is_enabled'] = !$this->resource->tickets()
                ->where('session_id', request()->query('session_id'))
                ->where('seat_id', $data['id'])
                ->exists();
                
            $data['price'] = $this->resource->seatGroup->sessionSeatGroups()
                ->where('session_id',  request()->query('session_id'))
                ->get()
                ->first()->price;
        }
        // dump($data);
        return $data;
    }
}
