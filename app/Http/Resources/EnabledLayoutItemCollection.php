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
        $layout = json_decode($this->resource->seatGroup->hall->layout);
        $foundElement = null;
        foreach ($layout as $element) {
            if ($element->type == strtolower(class_basename($this->resource)) && $element->id == $this->resource->id) {
                $foundElement = $element;
                break;
            }
        }
        if (isset($foundElement)) {
            $element = $foundElement;
        } else {
            return [];
        }
        $this->resource->loadMissing('seatGroup');

        $data = [
            'id' => $this->resource->id,
            'x' => $element->x,
            'y' => $element->y,
            'width' => $element->width,
            'height' => $element->height,
            'number' => $this->resource->number,
            'seat_group_id' => $this->resource->seat_group_id,
            'type' => strtolower(class_basename($this->resource)),
            'color' => $this->resource->seatGroup?->color,
            'group_name' => $this->resource->seatGroup?->name,
            'group_number' => $this->resource->seatGroup?->number,
        ];

        if ($data['type'] === 'seat') {
            $session = $request->query('session_id') ?: $request->session->id;
            $data['is_enabled'] = !$this->resource->tickets()
                ->where('session_id',  $session)
                ->where('seat_id', $data['id'])
                ->exists();

            $data['price'] = $this->resource->seatGroup->sessionSeatGroups()
                ->where('session_id',   $session)
                ->value('price');
        }
        return $data;
    }
}
