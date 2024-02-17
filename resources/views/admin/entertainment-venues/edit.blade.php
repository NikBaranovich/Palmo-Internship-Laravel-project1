@extends('admin.layouts.app')

@section('content')
    <div class="container">
        <h2> {{ $entertainmentVenue->exists ? 'Edit' : 'Add' }} Venue</h2>

        @if (session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif


        <form method="post" enctype="multipart/form-data"
            action="{{ $entertainmentVenue->exists ? route('admin.entertainment-venues.update', $entertainmentVenue->id) : route('admin.entertainment-venues.store') }}">
            @csrf
            @if ($entertainmentVenue->exists)
                @method('PUT')
            @endif


            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" class="form-control" id="name" name="name"
                    value="{{ old('name', $entertainmentVenue->name) }}">
                @error('name')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <input type="text" class="form-control" id="description" name="description"
                    value="{{ old('description', $entertainmentVenue->description) }}">
                @error('description')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="venue_type_id">Venue Type:</label>
                <select class="form-select" name="venue_type_id" id="venue_type_id">
                    @foreach ($venueTypes as $type)
                        <option value="{{ $type->id }}"
                            {{ old('venue_type_id', $entertainmentVenue->venue_type_id) == $type->id ? 'selected' : '' }}>

                            {{ $type->name }}
                        </option>
                    @endforeach
                </select>
                @error('venue_type_id')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="city_id">City:</label>
                <select class="form-select" name="city_id" id="city_id">
                    @foreach ($cities as $city)
                        <option value="{{ $city->id }}"
                            {{ old('city_id', $entertainmentVenue->city_id) == $city->id ? 'selected' : '' }}>
                            {{ $city->name }}
                        </option>
                    @endforeach
                </select>
                @error('city_id')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="address">Address:</label>
                <input type="text" class="form-control" id="address" name="address"
                    value="{{ old('address', $entertainmentVenue->address) }}">
                @error('address')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary mt-4">Save</button>
        </form>
    </div>
@endsection
