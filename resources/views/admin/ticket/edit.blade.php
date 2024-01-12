@extends('admin.layouts.app')

@section('content')
    <div class="container">
        <h2> {{ $ticket->exists ? 'Edit' : 'Add' }} Ticket</h2>

        @if (session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif


        <form method="post" enctype="multipart/form-data"
            action="{{ $ticket->exists ? route('admin.tickets.update', $ticket->id) : route('admin.tickets.store') }}">
            @csrf
            @if ($ticket->exists)
                @method('PUT')
            @endif


            <div class="form-group">
                <label for="price">Price:</label>
                <input type="number" class="form-control" id="price" name="price"
                    value="{{ old('name', $ticket->price) }}">
                @error('price')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <select name="venue_type_id" id="venue_type_id">
                @foreach ($sessions as $session)
                    <option value="{{ $session->id }}" {{ old('venue_type') == $session->name ? 'selected' : '' }}>
                        {{ $session->name }}
                    </option>
                @endforeach
            </select>

            <button type="submit" class="btn btn-primary">Save</button>
        </form>
    </div>
@endsection
