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
                <label for="name">Name:</label>
                <input type="text" class="form-control" id="name" name="name"
                    value="{{ old('name', $event->name) }}">
                @error('name')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="image">Poster:</label>
                <input type="file" class="form-control" id="image" name="image" onchange="loadFile(event)">
                @error('image')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
                @if (session()->has('image'))
                    <input type="hidden" name="image_saved" value="{{ session('image') }}">
                @endif
            </div>

            <img id="output" style="height: 200px"/>

            <div class="form-group">
                <label for="description">Description:</label>
                <input type="text" class="form-control" id="description" name="description"
                    value="{{ old('description', $event->description) }}">
                @error('description')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="trailer-url">Trailer Url:</label>
                <input type="text" class="form-control" id="trailer-url" name="trailer_url"
                    value="{{ old('trailer_url', $event->trailer_url) }}">
                @error('trailer_url')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary">Save</button>
        </form>
    </div>
@endsection

<script>
    var loadFile = function(event) {
        var output = document.getElementById('output');
        output.src = URL.createObjectURL(event.target.files[0]);
        output.onload = function() {
            URL.revokeObjectURL(output.src)
        }
    };
</script>
