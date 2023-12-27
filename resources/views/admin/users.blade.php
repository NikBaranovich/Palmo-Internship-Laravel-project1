@extends('admin.layouts.app')

@section('title', 'User List')

@section('content')
    <h2>User List</h2>

    <div class="mb-3">
        <a href="#" class="btn btn-primary">Add User</a>
    </div>
    @if (session('success'))
        <div class="alert alert-success" role="alert">
            {{ session('success') }}
        </div>
    @endif
    <table class="table table-striped">
        <thead>
            <tr>
                <th><a href="#">ID</a></th>
                <th><a href="#">Name</a></th>
                <th><a href="#">Email</a></th>
                <th><a href="#">Role</a></th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <td contenteditable="true">{{ $user->id }}</td>
                    <td contenteditable="true">{{ $user->name }}</td>
                    <td contenteditable="true">{{ $user->email }}</td>
                    <td contenteditable="true">{{ $user->role }}</td>
                    <td>
                        <a href="{{ route('admin.users.edit', ['user' => $user->id]) }}"
                            class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="post" id="deleteForm">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="confirmDelete()">Delete User</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
