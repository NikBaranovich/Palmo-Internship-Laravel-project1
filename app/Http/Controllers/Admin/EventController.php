<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Models\Event;
use App\Models\EventType;
use App\Models\Genre;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class EventController extends Controller
{
    public function __construct(
        protected Event $event
    ) {
        $this->middleware('admin');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $sortableColumns = ['id', 'title', 'event_type_id', 'release_date'];

        $events = $this->event->query()
            ->when(
                $request->has('sort_by') && in_array($request->input('sort_by'), $sortableColumns),
                function (Builder $query) use ($request) {
                    $query->orderBy(
                        $request->input('sort_by'),
                        $request->input('sort_order', 'asc')
                    );
                }
            )
            ->paginate(10);
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

        if ($request->hasFile('poster')) {
            $imagePath = $request->file('poster')->store('events/posters', 'public');
            $request['poster_path'] = $imagePath;
        }
        if ($request->hasFile('backdrop')) {
            $imagePath = $request->file('backdrop')->store('events/backdrops', 'public');
            $request['backdrop_path'] = $imagePath;
        }
        $event->fill($request->except('_token'));
        $event->save();
        $event->genres()->sync($request->input('genres'));

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
        // Проверяем, загружены ли новые постеры и/или фоны
        if ($request->hasFile('poster')) {
            // Удаляем старый постер, если он существует
            if ($event->poster_path) {
                Storage::disk('public')->delete($event->poster_path);
            }
            // Загружаем новый постер
            $imagePath = $request->file('poster')->store('events/posters', 'public');
            $request['poster_path'] = $imagePath;
        }

        if ($request->hasFile('backdrop')) {
            // Удаляем старый фон, если он существует
            if ($event->backdrop_path) {
                Storage::disk('public')->delete($event->backdrop_path);
            }
            // Загружаем новый фон
            $imagePath = $request->file('backdrop')->store('events/backdrops', 'public');
            $request['backdrop_path'] = $imagePath;
        }

        // Обновляем модель события
        $event->fill($request->except('_token'));
        $event->save();
        $event->genres()->sync($request->input('genres'));

        return redirect()->route('admin.events.index')->with('success', 'Event updated successfully');
    }
    
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        dd($id);
    }
}
