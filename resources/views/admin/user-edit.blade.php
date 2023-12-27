@extends('admin.layouts.app')

@section('content')
    <div class="container">
        <h2>Edit User</h2>

        @if(session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif

        <form method="post" action="{{ route('admin.users.update', $user->id) }}">
            @csrf
            @method('put')

            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}">
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}">
            </div>


            <button type="submit" class="btn btn-primary">Save</button>
        </form>
    </div>
@endsection
