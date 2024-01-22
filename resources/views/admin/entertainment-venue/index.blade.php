@extends('admin.layouts.app')


@section('content')
    <h2>Entertainment Venues</h2>

    <div class="mb-3">
        <a href="{{ route('admin.entertainment_venues.create') }}" class="btn btn-primary">Add</a>
    </div>
    @if (session('success'))
        <div class="alert alert-success" role="alert">
            {{ session('success') }}
        </div>
    @endif
    @if ($venues)
        <table class="table table-striped">
            <thead>
                <tr>
                    <th><a
                            href="{{ route('admin.entertainment_venues.index', [
                                'sort_by' => 'id',
                                'sort_order' => request('sort_by') == 'id' && request('sort_order') == 'asc' ? 'desc' : 'asc',
                            ]) }}">ID</a>
                    </th>
                    <th><a
                            href="{{ route('admin.entertainment_venues.index', [
                                'sort_by' => 'name',
                                'sort_order' => request('sort_by') == 'name' && request('sort_order') == 'asc' ? 'desc' : 'asc',
                            ]) }}">Name</a>
                    </th>
                    <th><a
                            href="{{ route('admin.entertainment_venues.index', [
                                'sort_by' => 'map_link',
                                'sort_order' => request('sort_by') == 'map_link' && request('sort_order') == 'asc' ? 'desc' : 'asc',
                            ]) }}">Map
                            Link</a>
                    </th>
                    <th><a
                            href="{{ route('admin.entertainment_venues.index', [
                                'sort_by' => 'city',
                                'sort_order' => request('sort_by') == 'city' && request('sort_order') == 'asc' ? 'desc' : 'asc',
                            ]) }}">City</a>
                    </th>
                    <th><a
                            href="{{ route('admin.entertainment_venues.index', [
                                'sort_by' => 'address',
                                'sort_order' => request('sort_by') == 'address' && request('sort_order') == 'asc' ? 'desc' : 'asc',
                            ]) }}">Address</a>
                    </th>
                    <th><a
                            href="{{ route('admin.entertainment_venues.index', [
                                'sort_by' => 'description',
                                'sort_order' => request('sort_by') == 'description' && request('sort_order') == 'asc' ? 'desc' : 'asc',
                            ]) }}">Description</a>
                    </th>
                    <th>Halls</th>
                    <th><a
                            href="{{ route('admin.entertainment_venues.index', [
                                'sort_by' => 'venue_type_id',
                                'sort_order' => request('sort_by') == 'venue_type_id' && request('sort_order') == 'asc' ? 'desc' : 'asc',
                            ]) }}">Venue
                            Type</a>
                    </th>

                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($venues as $venue)
                    <tr>
                        <td>{{ $venue->id }}</td>
                        <td>{{ $venue->name }}</td>
                        <td>{{ $venue->map_link }}</td>
                        <td>{{ $venue->city }}</td>
                        <td>{{ $venue->address }}</td>
                        <td>{{ $venue->description }}</td>
                        <td>{{ $venue->halls->count() }} Hall(s)
                            <a href="{{ route('admin.halls.create', $venue->id) }}" class="btn btn-sm btn-success">Add
                                Hall</a>
                        </td>
                        <td>{{ $venue->venueType->name }}</td>
                        <td>
                            <a href="{{ route('admin.entertainment_venues.edit', ['entertainment_venue' => $venue->id]) }}"
                                class="btn btn-sm btn-warning">Edit</a>
                            <form class="d-inline" action="{{ route('admin.entertainment_venues.destroy', $venue->id) }}"
                                method="post" id="deleteForm">
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
        {{ $venues->appends([
                'sort-by' => request('sort-by'),
            ])->links() }}
    @else
        <h1>Not Found!</h1>
    @endif

    {{-- <svg id="drag-drop-area" width="502px" height="502px" style="border: 2px dashed #ccc;"
        xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink">
        <g id="places" width="100%" height="100%">
            @foreach (json_decode($venues->last()->layout) as $item)
                @if ($item->type === 'table')
                    <circle class="table" cx="{{ $item->x + $item->width }}" cy="{{ $item->y + $item->width }}"
                        r="{{ $item->width }}" fill="blue" />
                @elseif($item->type === 'seat')
                    <rect class="seat" x="{{ $item->x }}" y="{{ $item->y }}" width="{{ $item->width }}"
                        height="{{ $item->height }}" fill="green" data-price="{{ 200 }}" />
                @endif
            @endforeach
        </g>
    </svg> --}}




@endsection
