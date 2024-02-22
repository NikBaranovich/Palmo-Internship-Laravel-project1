@extends('admin.layouts.app')

@section('content')
    <div class="container">
        <h2> {{ $event->exists ? 'Edit' : 'Add' }} Event</h2>

        @if (session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif


        <form method="post" enctype="multipart/form-data"
            action="{{ $event->exists ? route('admin.events.update', $event->id) : route('admin.events.store') }}">
            @csrf
            @if ($event->exists)
                @method('PUT')
            @endif


            <div class="form-group">
                <label for="title">Title:</label>
                <input type="text" class="form-control" id="title" name="title"
                    value="{{ old('title', $event->title) }}">
                @error('title')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="backdrop">Backdrop:</label>
                <input type="file" class="form-control" id="backdrop" name="backdrop">
                @error('backdrop')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="poster">Poster:</label>
                <input type="file" class="form-control" id="poster" name="poster">
                @error('poster')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="overview">Trailer url:</label>
                <input type="text" class="form-control" id="trailer_url" name="trailer_url"
                    value="{{ old('trailer_url', $event->trailer_url) }}">
                @error('trailer_url')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="overview">Overview:</label>
                <input type="text" class="form-control" id="overview" name="overview"
                    value="{{ old('overview', $event->overview) }}">
                @error('overview')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="venue_type_id">Venue Type:</label>
                <select class="form-select" name="event_type_id" id="event_type_id">
                    @foreach ($types as $type)
                        <option value="{{ $type->id }}"
                            {{ old('venue_type_id', $event->event_type_id) == $type->id ? 'selected' : '' }}>

                            {{ $type->name }}
                        </option>
                    @endforeach
                </select>
                @error('venue_type_id')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="genres">Genres:</label>
                <div class="bg-white">
                    @foreach ($genres as $genre)
                        <div class="btn row">
                            <label class='form-check-label'>
                                <input value="{{ $genre->id }}" type="checkbox" class="form-check-input block"
                                    name="genres[]" @if (collect(old('genres', $event->genres->pluck('id')))->contains($genre->id)) checked @endif>
                                {{ $genre->name }}
                            </label>
                        </div>
                    @endforeach
                </div>
                @error('genres')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group mt-2">
                <label for="release_date">Release date:</label>
                <input type="date" class="form-control" id="release_date" name="release_date"
                    value="{{ old('release_date', \Carbon\Carbon::parse($event->release_date)->format('Y-m-d')) }}" />
            </div>

            <button type="submit" class="btn btn-primary mt-4">Save</button>

        </form>
    </div>
@endsection
