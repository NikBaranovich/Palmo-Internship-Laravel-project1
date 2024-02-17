@extends('admin.layouts.app')

@section('title', 'User List')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="main-title">
            <p class="font-weight-bold">USERS</p>
        </div>

        <div>
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-circle btn-xl">
                <span class="material-icons-outlined">add</span>
            </a>
        </div>
    </div>
    @if (session('success'))
        <div class="alert alert-success" role="alert">
            {{ session('success') }}
        </div>
    @endif
    <table class="table table-striped table-rounded">
        <thead>
            <tr>
                <th><a class="table-header"
                        href="{{ route('admin.users.index', ['sort_by' => 'id', 'sort_order' => sortOrder('id')]) }}">
                        ID
                        @if (request('sort_by') == 'id')
                            @if (request('sort_order') == 'asc')
                                <span class="material-symbols-rounded">arrow_upward_alt</span>
                            @else
                                <span class="material-symbols-rounded">arrow_downward_alt</span>
                            @endif
                        @else
                            <span class="material-symbols-rounded">swap_vert</span>
                        @endif
                    </a>
                </th>
                <th><a class="table-header"
                        href="{{ route('admin.users.index', ['sort_by' => 'name', 'sort_order' => sortOrder('name')]) }}">Name
                        @if (request('sort_by') == 'name')
                            @if (request('sort_order') == 'asc')
                                <span class="material-symbols-rounded">arrow_upward_alt</span>
                            @else
                                <span class="material-symbols-rounded">arrow_downward_alt</span>
                            @endif
                        @else
                            <span class="material-symbols-rounded">swap_vert</span>
                        @endif
                    </a>
                </th>
                <th><a class="table-header"
                        href="{{ route('admin.users.index', [
                            'sort_by' => 'email',
                            'sort_order' => request('sort_by') == 'email' && request('sort_order') == 'asc' ? 'desc' : 'asc',
                        ]) }}">Email
                        @if (request('sort_by') == 'email')
                            @if (request('sort_order') == 'asc')
                                <span class="material-symbols-rounded">arrow_upward_alt</span>
                            @else
                                <span class="material-symbols-rounded">arrow_downward_alt</span>
                            @endif
                        @else
                            <span class="material-symbols-rounded">swap_vert</span>
                        @endif
                    </a>
                </th>
                <th><a class="table-header"
                        href="{{ route('admin.users.index', [
                            'sort_by' => 'role',
                            'sort_order' => request('sort_by') == 'role' && request('sort_order') == 'asc' ? 'desc' : 'asc',
                        ]) }}">Role
                        @if (request('sort_by') == 'role')
                            @if (request('sort_order') == 'asc')
                                <span class="material-symbols-rounded">arrow_upward_alt</span>
                            @else
                                <span class="material-symbols-rounded">arrow_downward_alt</span>
                            @endif
                        @else
                            <span class="material-symbols-rounded">swap_vert</span>
                        @endif
                    </a>
                </th>
                <th> <span class="table-header">Action </span></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td><span class="badge bg-primary">{{ $user->role }}</span></td>
                    <td>
                        <div class="dropdown">
                            <button class="btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="material-icons-outlined">more_vert</span>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item text-warning"
                                        href="{{ route('admin.users.edit', ['user' => $user->id]) }}">edit</a></li>
                                <li>
                                    <form class="d-inline" action="{{ route('admin.users.destroy', $user->id) }}"
                                        method="post" id="deleteForm">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="dropdown-item text-danger">delete</button>
                                    </form>
                                </li>
                            </ul>
                        </div>

                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $users->appends([
        'sort_by' => request('sort_by'),
        'sort_order' => request('sort_order'),
    ])->links() }}
@endsection
