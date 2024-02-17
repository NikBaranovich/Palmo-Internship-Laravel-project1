@extends('admin.layouts.app')

@section('title', 'Sessions List')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="main-title">
            <p class="font-weight-bold">SESSIONS</p>
        </div>

        <div>
            <a href="{{ route('admin.sessions.create') }}" class="btn btn-primary btn-circle btn-xl add-button">
                <span class="material-icons-outlined">add</span>
            </a>
        </div>
    </div>
    @if (session('success'))
        <div class="alert alert-success" role="alert">
            {{ session('success') }}
        </div>
    @endif
    <table class="table table-striped table-rounded">
        <thead>
            <tr>
                <th><a class="table-header"
                        href="{{ route('admin.sessions.index', ['sort_by' => 'id', 'sort_order' => sortOrder('id')]) }}">
                        ID
                        @if (request('sort_by') == 'id')
                            @if (request('sort_order') == 'asc')
                                <span class="material-symbols-rounded">arrow_upward_alt</span>
                            @else
                                <span class="material-symbols-rounded">arrow_downward_alt</span>
                            @endif
                        @else
                            <span class="material-symbols-rounded">swap_vert</span>
                        @endif
                    </a>
                </th>

                <th><a class="table-header"
                        href="{{ route('admin.sessions.index', ['sort_by' => 'venue', 'sort_order' => sortOrder('venue')]) }}">
                        Entertainment Venue
                        @if (request('sort_by') == 'venue')
                            @if (request('sort_order') == 'asc')
                                <span class="material-symbols-rounded">arrow_upward_alt</span>
                            @else
                                <span class="material-symbols-rounded">arrow_downward_alt</span>
                            @endif
                        @else
                            <span class="material-symbols-rounded">swap_vert</span>
                        @endif
                    </a>
                </th>

                <th> <span class="table-header">Hall </span></th>

                <th><a class="table-header"
                        href="{{ route('admin.sessions.index', ['sort_by' => 'event', 'sort_order' => sortOrder('event')]) }}">
                        Event
                        @if (request('sort_by') == 'event')
                            @if (request('sort_order') == 'asc')
                                <span class="material-symbols-rounded">arrow_upward_alt</span>
                            @else
                                <span class="material-symbols-rounded">arrow_downward_alt</span>
                            @endif
                        @else
                            <span class="material-symbols-rounded">swap_vert</span>
                        @endif
                    </a>
                </th>

                <th><a class="table-header"
                        href="{{ route('admin.sessions.index', ['sort_by' => 'start_time', 'sort_order' => sortOrder('start_time')]) }}">
                        Start Time
                        @if (request('sort_by') == 'start_time')
                            @if (request('sort_order') == 'asc')
                                <span class="material-symbols-rounded">arrow_upward_alt</span>
                            @else
                                <span class="material-symbols-rounded">arrow_downward_alt</span>
                            @endif
                        @else
                            <span class="material-symbols-rounded">swap_vert</span>
                        @endif
                    </a>
                </th>

                <th><a class="table-header"
                        href="{{ route('admin.sessions.index', ['sort_by' => 'end_time', 'sort_order' => sortOrder('end_time')]) }}">
                        End Time
                        @if (request('sort_by') == 'end_time')
                            @if (request('sort_order') == 'asc')
                                <span class="material-symbols-rounded">arrow_upward_alt</span>
                            @else
                                <span class="material-symbols-rounded">arrow_downward_alt</span>
                            @endif
                        @else
                            <span class="material-symbols-rounded">swap_vert</span>
                        @endif
                    </a>
                </th>

                <th> <span class="table-header">Action </span></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($sessions as $session)
                <tr>
                    <td>{{ $session->id }}</td>
                    <td>{{ $session->hall->entertainmentVenue->name }}</td>
                    <td>{{ $session->hall->number }}</td>
                    <td>{{ $session->event->title }}</td>
                    <td>{{ \Carbon\Carbon::parse($session->start_time)->format('d M Y H:i') }}</td>
                    <td>{{ \Carbon\Carbon::parse($session->end_time)->format('d M Y H:i') }}</td>

                    <td>
                        <div class="dropdown">
                            <button class="btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="material-icons-outlined">more_vert</span>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item text-warning"
                                        href="{{ route('admin.sessions.edit', ['session' => $session->id]) }}">edit</a>
                                </li>
                                <li>
                                    <button onclick="handleDeleteClick({{ $session->id }})"
                                        class="dropdown-item text-danger" data-bs-toggle="modal"
                                        data-bs-target="#deleteModal">delete</button>

                                </li>
                            </ul>
                        </div>

                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $sessions->appends([
            'sort_by' => request('sort_by'),
            'sort_order' => request('sort_order'),
        ])->links() }}

    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirm deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form class="d-inline" action="" method="post" id="deleteForm">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function handleDeleteClick(id) {
            document.getElementById('deleteModal').dataset.id = id;
            var form = deleteModal.querySelector('form');
            form.action = '{{ route('admin.sessions.destroy', ':id') }}'.replace(':id', id);

        }
    </script>
@endsection
