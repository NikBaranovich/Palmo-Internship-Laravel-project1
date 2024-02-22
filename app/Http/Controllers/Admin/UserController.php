<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Enums\UserRole;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\ShowUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Services\UserService;
use Illuminate\Database\Eloquent\Builder;
use GuzzleHttp\Promise\Create;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

class UserController extends Controller
{
    public function __construct(
        protected UserService $service
    ) {
        $this->middleware('admin');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(ShowUserRequest $request)
    {
        $this->authorize('viewAny', User::class);

        $users = $this->service->index($request);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateUserRequest $request, User $user)
    {
        $this->authorize('store', [User::class, $request->input('role')]);

        $this->service->save($request, $user);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User successfully created.');
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
    public function edit(User $user)
    {
        $this->authorize('edit', [User::class, $user]);

        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $this->authorize('update', [User::class, $user, $request->input('role')]);

        $this->service->save($request, $user);

        return redirect()
            ->route('admin.users.edit', $user)
            ->with('success', 'User successfully updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $this->authorize('delete', [User::class, $user]);

        $this->service->delete($user);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User successfully deleted.');
    }
}
