@extends('admin.layouts.app')

@section('content')
    <script src="https://unpkg.com/interactjs/dist/interact.min.js"></script>

    <div class="container">
        @if (!$venue->exists)
            <h2>Add Venue</h2>
        @else
            <h2>Edit Venue</h2>
        @endif

        @if (session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif


        <form id='form' method="post" enctype="multipart/form-data"
            action="{{ $venue->exists ? route('admin.entertainment_venues.update', $venue->id) : route('admin.entertainment_venues.store') }}">
            @csrf
            @if ($venue->exists)
                @method('PUT')
            @endif


            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" class="form-control" id="name" name="name"
                    value="{{ old('name', $venue->name) }}">
                @error('name')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <input type="text" class="form-control" id="description" name="description"
                    value="{{ old('description', $venue->description) }}">
                @error('description')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="city">City:</label>
                <input type="text" class="form-control" id="city" name="city"
                    value="{{ old('city', $venue->city) }}">
                @error('city')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="address">Address:</label>
                <input type="text" class="form-control" id="address" name="address"
                    value="{{ old('address', $venue->address) }}">
                @error('address')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <select name="venue_type_id" id="venue_type_id">
                @foreach ($venueTypes as $venueType)
                    <option value="{{ $venueType->id }}" {{ old('venue_type') == $venueType->name ? 'selected' : '' }}>
                        {{ $venueType->name }}
                    </option>
                @endforeach
            </select>

            <button type="submit" class="btn btn-primary">Save</button>
        </form>
    </div>

@endsection
