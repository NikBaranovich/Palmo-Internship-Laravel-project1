<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource as Resource;

class EventCollection extends Resource
{
    public static $wrap = null;
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'title' => $this->resource->title,
            'backdrop_path' => $this->resource->backdrop_path,
            'overview' => $this->resource->overview,
            'poster_path' => $this->resource->poster_path,
            'title' => $this->resource->title,
            'trailer_url' => $this->resource->trailer_url,
            'views_count' => $this->resource->views_count,
            'rating_avg' => $this->resource->ratings()->avg('vote'),
            'rating_count' => $this->resource->ratings()->count(),
        ];
    }
}
