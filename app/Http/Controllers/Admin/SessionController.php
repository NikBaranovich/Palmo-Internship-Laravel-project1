<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateSessionRequest;
use App\Models\EntertainmentVenue;
use App\Models\Event;
use App\Models\Hall;
use App\Models\Session;
use App\Models\SessionSeatGroup;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class SessionController extends Controller
{
    public function __construct(
        protected Session $session,
        protected SessionSeatGroup $sessionSeatGroup
    ) {
        $this->middleware('admin');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $sortableColumns = ['id', 'event', 'venue', 'start_time', 'end_time'];


        $sessions = $this->session->query()
            ->when(
                $request->has('sort_by') && in_array($request->input('sort_by'), $sortableColumns),
                function (Builder $query) use ($request) {
                    $sortBy = $request->input('sort_by');
                    $sortOrder = $request->input('sort_order', 'asc');

                    switch ($sortBy) {
                        case 'event':
                            $query->withAggregate('event', 'title')
                                ->orderBy('event_title', $sortOrder);
                            break;
                        case 'venue':
                            //FIX
                            $query->whereHas('hall', function ($query) {
                                $query->withAggregate('entertainmentVenue', 'name')->groupBy('entertainment_venue_name');
                            });
                            break;
                        default:
                            $query->orderBy($sortBy, $sortOrder);
                    }
                }
            )
            ->paginate(10);

        return view('admin.sessions.index', compact('sessions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Session $session)
    {
        return view('admin.sessions.edit', compact('session'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UpdateSessionRequest $request)
    {
        // dd($request->all());
        $this->session->fill($request->only($this->session->getFillable()));
        $this->session->save();
        foreach ($request->groups as $group) {
            $group = json_decode($group);

            $this->sessionSeatGroup->create(array_merge((array)$group, ['session_id' => $this->session->id]));
        }
        return redirect()->route('admin.sessions.index')->with('success', 'Session saved successfully');
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
    public function edit(Session $session)
    {
        return view('admin.sessions.edit', compact('session'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Session $session)
    {
        $session->fill($request->only($this->session->getFillable()));
        $session->save();

        $session->sessionSeatGroups()->delete();

        foreach ($request->groups as $group) {
            $group = json_decode($group, true);
            $this->sessionSeatGroup->create(array_merge((array)$group, ['session_id' => $session->id]));
        }

        return redirect()->route('admin.sessions.edit', $session)->with('success', 'Session updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Session $session)
    {
        $session->delete();

        return redirect()
            ->route('admin.sessions.index')
            ->with('success', 'Session successfully deleted.');
    }
}
