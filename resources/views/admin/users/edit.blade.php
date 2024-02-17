@extends('admin.layouts.app')

@section('content')
    <div class="container">
        <h2> {{ $user->exists ? 'Edit' : 'Add' }} User</h2>

        @if (session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif


        <form method="post" enctype="multipart/form-data"
            action="{{ $user->exists ? route('admin.users.update', $user->id) : route('admin.users.store') }}">
            @csrf
            @if ($user->exists)
                @method('PUT')
            @endif


            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" class="form-control" id="name" name="name"
                    value="{{ old('name', $user->name) }}">
                @error('name')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="text" class="form-control" id="email" name="email"
                    value="{{ old('email', $user->email) }}">
                @error('email')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <label for="user_role">User Role:</label>
            <select class="form-select" name="role" id="role">
                @foreach (UserRole::cases() as $role)
                <option value="{{ $role->value }}" {{ old('user_role', $user->role) == $role->value ? 'selected' : '' }}>

                        {{ ucfirst($role->value) }}
                    </option>
                @endforeach
            </select>
            @error('role')
                <div class="text-danger">{{ $message }}</div>
            @enderror

            @if (!$user->exists)
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" class="form-control" id="password" name="password">
                    @error('password')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            @endif
            <button type="submit" class="btn btn-primary mt-4">Save</button>
        </form>
    </div>
@endsection
