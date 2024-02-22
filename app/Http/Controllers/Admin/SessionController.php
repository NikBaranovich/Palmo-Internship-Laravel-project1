<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateSessionRequest;
use App\Models\EntertainmentVenue;
use App\Models\Event;
use App\Models\Hall;
use App\Models\Session;
use App\Models\SessionSeatGroup;
use App\Services\SessionService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class SessionController extends Controller
{
    public function __construct(
        protected SessionService $service
    ) {
        $this->middleware('admin');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $sessions = $this->service->index($request);

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
    public function store(UpdateSessionRequest $request, Session $session)
    {
        $this->service->save($request, $session);

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
        $this->service->save($request, $session);

        return redirect()->route('admin.sessions.edit', $session)->with('success', 'Session updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Session $session)
    {
        $this->service->delete($session);

        return redirect()
            ->route('admin.sessions.index')
            ->with('success', 'Session deleted successfully.');
    }
}
