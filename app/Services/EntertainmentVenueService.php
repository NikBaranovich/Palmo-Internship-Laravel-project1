<?php

namespace App\Services;

use App\Models\EntertainmentVenue;
use App\Repositories\EntertainmentVenueRepository;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Auth;

class EntertainmentVenueService extends BaseService
{
    public function __construct(
        protected EntertainmentVenueRepository $repository
    ) {
    }

    public function save($request, EntertainmentVenue $entertainmentVenue)
    {
        $entertainmentVenue->fill($request->only($entertainmentVenue->getFillable()));

        return $entertainmentVenue->save();
    }

    public function delete(EntertainmentVenue $entertainmentVenue)
    {
        return $entertainmentVenue->delete();
    }
}
