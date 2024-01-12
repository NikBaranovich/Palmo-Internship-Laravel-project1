@extends('admin.layouts.app')

@section('title', 'Event List')

@section('content')
    <h2>Events</h2>

    <div class="mb-3">
        <a href="{{ route('admin.events.create') }}" class="btn btn-primary">Add</a>
    </div>
    @if (session('success'))
        <div class="alert alert-success" role="alert">
            {{ session('success') }}
        </div>
    @endif
    @if (count($events))
        <table class="table table-striped">
            <thead>
                <tr>
                    <th><a
                            href="{{ route('admin.events.index', ['sort_by' => 'id', 'sort_order' => sortOrder('id')]) }}">ID</a>
                    </th>
                    <th><a
                            href="{{ route('admin.events.index', ['sort_by' => 'name', 'sort_order' => sortOrder('name')]) }}">Name</a>
                    </th>
                    <th>Trailer Url</th>

                    <th><a
                            href="{{ route('admin.events.index', [
                                'sort_by' => 'description',
                                'sort_order' => request('sort_by') == 'description' && request('sort_order') == 'asc' ? 'desc' : 'asc',
                            ]) }}">Description</a>
                    </th>
                    <th>Image</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($events as $event)
                    <tr>
                        <td>{{ $event->id }}</td>
                        <td>{{ $event->name }}</td>
                        <td>{{ $event->trailer_url }}</td>
                        <td>{{ $event->description }}</td>
                        <td><img style="height: 200px" src="{{ Storage::url($event->poster_picture_url) }}" /></td>

                        <td>
                            <a href="{{ route('admin.events.edit', ['event' => $event->id]) }}"
                                class="btn btn-sm btn-warning">Edit</a>
                            <form class="d-inline" action="{{ route('admin.events.destroy', $event->id) }}" method="post"
                                id="deleteForm">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"
                                    onclick="confirmDelete()">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $events->appends([
                'sort_by' => request('sort_by'),
                'sort_order' => request('sort_order'),
            ])->links() }}
    @else
        <h1>Not Found!</h1>
    @endif
@endsection
