<?php

namespace App\Services;

use App\Models\Event;
use App\Models\Session;
use App\Models\SessionSeatGroup;
use App\Repositories\SessionRepository;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SessionService extends BaseService
{
    public function __construct(
        protected SessionRepository $repository,
        protected SessionSeatGroup $sessionSeatGroup
    ) {
    }

    public function save($request, Session $session)
    {
        $session->fill($request->only($session->getFillable()));
        $session->save();
        $session->sessionSeatGroups()->delete();

        foreach ($request->groups as $group) {
            $group = (array)json_decode($group);
            $sessionSeatGroup = new SessionSeatGroup();
            $sessionSeatGroup->fill($group);
            $sessionSeatGroup->session()->associate($session);
            $sessionSeatGroup->save();
        }

        return ['message' => 'success'];
    }

    public function delete(Session $session)
    {
        $session->delete();
    }
}
