@extends('admin.layouts.app')

@section('title', 'Venues List')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="main-title">
            <p class="font-weight-bold">ENTERTAINMENT VENUES</p>
        </div>

        <div>
            <a href="{{ route('admin.entertainment-venues.create') }}" class="btn btn-primary btn-circle btn-xl add-button">
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
                        href="{{ route('admin.entertainment-venues.index', ['sort_by' => 'id', 'sort_order' => sortOrder('id')]) }}">
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
                        href="{{ route('admin.entertainment-venues.index', ['sort_by' => 'name', 'sort_order' => sortOrder('name')]) }}">Name
                        @if (request('sort_by') == 'name')
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
                        href="{{ route('admin.entertainment-venues.index', ['sort_by' => 'type', 'sort_order' => sortOrder('type')]) }}">Type
                        @if (request('sort_by') == 'type')
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
                        href="{{ route('admin.entertainment-venues.index', ['sort_by' => 'city', 'sort_order' => sortOrder('city')]) }}">City
                        @if (request('sort_by') == 'city')
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
                        href="{{ route('admin.entertainment-venues.index', ['sort_by' => 'address', 'sort_order' => sortOrder('address')]) }}">Address
                        @if (request('sort_by') == 'address')
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
                <th> <span class="table-header">Halls </span></th>

                <th> <span class="table-header">Action </span></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($venues as $venue)
                <tr>
                    <td>{{ $venue->id }}</td>
                    <td>{{ $venue->name }}</td>
                    <td>{{ $venue->venueType->name }}</td>
                    <td>{{ $venue->city->name }}</td>
                    <td>{{ $venue->address }}</td>
                    <td>{{ $venue->halls->count() }} Hall(s) </td>
                    <td>
                        <div class="dropdown">
                            <button class="btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="material-icons-outlined">more_vert</span>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item text-warning"
                                        href="{{ route('admin.entertainment-venues.edit', ['entertainment_venue' => $venue->id]) }}">edit</a>
                                </li>
                                <li><a href="{{ route('admin.halls.create', $venue->id) }}"
                                        class="dropdown-item text-success">add hall</a>
                                </li>
                                <li>
                                    <button onclick="handleDeleteClick({{ $venue->id }})"
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
    {{ $venues->appends([
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
            form.action = '{{ route('admin.entertainment-venues.destroy', ':id') }}'.replace(':id', id);
        }
    </script>
@endsection
