<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource as Resource;
use App\Http\Resources\EventGenreCollection;

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
            'release_date' => $this->resource->release_date,
            'poster_path' => $this->resource->poster_path,
            'trailer_url' => $this->resource->trailer_url,
            'views_count' => $this->resource->views_count,
            'rating_avg' => $this->resource->ratings()->avg('vote'),
            'tickets_count' => $this->resource->tickets()->count(),
            'rating_count' => $this->resource->ratings()->count(),
            'genres' => EventGenreCollection::collection($this->resource->genres()->get()),
        ];
    }
}
