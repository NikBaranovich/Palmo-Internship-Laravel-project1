<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SessionCollection;
use App\Models\Session;
use Illuminate\Http\Request;

class SessionController extends Controller
{
    public function show(Session $session)
    {
        return new SessionCollection($session);
    }
    public function find(Request $request)
    {
        return Session::query()
            ->where('hall_id', $request->query('hall_id'))
            ->where('event_id', $request->query('event_id'))
            ->get();
    }
}
