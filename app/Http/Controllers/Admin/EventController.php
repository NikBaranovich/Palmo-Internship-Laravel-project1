<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateEventRequest;
use App\Http\Requests\ShowEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Models\Event;
use App\Models\EventType;
use App\Models\Genre;
use App\Services\EventService;
use App\Services\FileService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class EventController extends Controller
{
    public function __construct(
        protected EventService $service,
        protected FileService $fileService,
    ) {
        $this->middleware('admin');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(ShowEventRequest $request)
    {
        $events = $this->service->index($request);

        return view('admin.events.index', compact('events'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Event $event)
    {
        $genres = Genre::all();
        $types = EventType::all();

        return view('admin.events.edit', compact('event', 'genres', 'types'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateEventRequest $request, Event $event)
    {
        $this->service->save($request, $event);

        return redirect()->route('admin.events.index')->with('success', 'Event created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event)
    {
        $genres = Genre::all();
        $types = EventType::all();

        return view('admin.events.edit', compact('event', 'genres', 'types'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEventRequest $request, Event $event)
    {
        $this->service->update($request, $event);

        return redirect()->route('admin.events.index')->with('success', 'Event updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        $this->service->delete($event);

        return redirect()
            ->route('admin.events.index')
            ->with('success', 'Event deleted successfully.');
    }
}
