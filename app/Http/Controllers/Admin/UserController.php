<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Enums\UserRole;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Database\Eloquent\Builder;
use GuzzleHttp\Promise\Create;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{
    public function __construct(
        protected User $user
    ) {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $users = $this->user->query()
            ->when($request->has('sort_by'), function (Builder $query) use ($request) {
                $query->orderBy(
                    $request->input('sort_by'),
                    $request->input('sort_order', 'asc')
                );
            })
            ->paginate(2);

        return view('admin.users', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(User $user)
    {
        return view('admin.user-form', compact('user'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateUserRequest $request, User $user)
    {
        $user->create($request->except('_token'));

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
        $user->makeHidden(['email_verified_at', 'created_at', 'updated_at']);

        return view('admin.user-form', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $user->update($request->except('_token'));

        return redirect()
            ->route('admin.users.edit', $user)
            ->with('success', 'User successfully updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User successfully deleted.');
    }
}
