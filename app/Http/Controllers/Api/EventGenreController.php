<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\EventGenre;
use Illuminate\Http\Request;

class EventGenreController extends Controller
{
    public function index(Request $request)
    {
        return EventGenre::query()->get(['id', 'name']);
    }
}
