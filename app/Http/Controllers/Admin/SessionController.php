<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
        $sessions = $this->session->query()
            ->when($request->has('sort_by'), function (Builder $query) use ($request) {
                $query->orderBy(
                    $request->input('sort_by'),
                    $request->input('sort_order', 'asc')
                );
            })
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
    public function store(Request $request)
    {
        // dd($request->all());
        $this->session->fill($request->only($this->session->getFillable()));
        $this->session->save();
        foreach ($request->groups as $group) {
            $group = json_decode($group);

            $this->sessionSeatGroup->create(array_merge((array)$group, ['session_id' => $this->session->id]));
        }
        return redirect()->route('admin.events.index')->with('success', 'Event saved successfully');
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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
