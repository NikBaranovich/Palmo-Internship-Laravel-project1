@extends('admin.layouts.app')

@section('title', 'Tickets List')

@section('content')
    <h2>Users</h2>

    <div class="mb-3">
        <a href="{{ route('admin.tickets.create') }}" class="btn btn-primary">Add</a>
    </div>
    @if (session('success'))
        <div class="alert alert-success" role="alert">
            {{ session('success') }}
        </div>
    @endif
    @if (count($tickets))
        <table class="table table-striped">
            <thead>
                <tr>
                    <th><a
                            href="{{ route('admin.tickets.index', ['sort_by' => 'id', 'sort_order' => sortOrder('id')]) }}">ID</a>
                    </th>
                    <th><a
                            href="{{ route('admin.tickets.index', ['sort_by' => 'name', 'sort_order' => sortOrder('name')]) }}">Venue</a>
                    </th>
                    <th><a
                            href="{{ route('admin.tickets.index', [
                                'sort_by' => 'email',
                                'sort_order' => request('sort_by') == 'email' && request('sort_order') == 'asc' ? 'desc' : 'asc',
                            ]) }}">Event</a>
                    </th>
                    <th><a
                            href="{{ route('admin.tickets.index', [
                                'sort_by' => 'role',
                                'sort_order' => request('sort_by') == 'role' && request('sort_order') == 'asc' ? 'desc' : 'asc',
                            ]) }}">Role</a>
                    </th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tickets as $ticket)
                    <tr>
                        <td contenteditable="true">{{ $ticket->token }}</td>
                        <td contenteditable="true">{{ dump($ticket->entertainmentVenueEvent->entertainmentVenue) }}</td>
                        <td contenteditable="true">{{ dump($ticket->entertainmentVenueEvent->event) }}</td>
                        <td contenteditable="true">{{ $ticket->role }}</td>
                        <td>
                            <a href="{{ route('admin.users.edit', ['user' => $ticket->id]) }}"
                                class="btn btn-sm btn-warning">Edit</a>
                            <form class="d-inline" action="{{ route('admin.users.destroy', $ticket->id) }}" method="post"
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
        {{ $tickets->appends([
                'sort_by' => request('sort_by'),
                'sort_order' => request('sort_order'),
            ])->links() }}
    @else
        <h1>Not Found!</h1>
    @endif
@endsection
