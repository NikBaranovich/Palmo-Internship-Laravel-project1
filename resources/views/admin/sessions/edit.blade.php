@extends('admin.layouts.app')

@section('content')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <style>
        datalist {
            display: block !important;
            position: absolute;
            background-color: white;
            border: 1px solid #adb5bd;
            border-radius: 0 0 5px 5px;
            border-top: none;
            width: 100%;
            padding: 5px;
            max-height: 10rem;
            overflow-y: auto;
        }

        option {
            padding: 4px;
            margin-bottom: 1px;
            cursor: pointer;
        }

        option:hover {
            background-color: #d3d3d3;
        }
    </style>
    <div class="container">
        <h2> {{ $session->exists ? 'Edit' : 'Add' }} Session</h2>

        @if (session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif


        <form method="post" enctype="multipart/form-data"
            action="{{ $session->exists ? route('admin.sessions.update', $session->id) : route('admin.sessions.store') }}">
            @csrf
            @if ($session->exists)
                @method('PUT')
            @endif

            <div class="form-group">
                <label for="hall_id">Select hall</label>
                <fieldset class="position-relative">
                    <input type="text" class="form-control" autocomplete="off" list="" name="hall_id"
                        id="hall" />
                    <datalist class="visually-hidden" id="hall-options"> </datalist>
                    {{-- @if (isset($errors['event-user-send'])) {
                        <div  class='invalid-input-error'>{$errors['event-user-send']}
                        </div>";
                    }
                    @endif --}}
                    <div id="selected-users"></div>
                    </select>
                </fieldset>
            </div>

            {{-- <select name="entertainment_venue_id" id="entertainment_venue_id">
                @foreach ($halls as $hall)
                    <option value="{{ $hall->id }}" {{ old('entertainment_venue') == $hall->id ? 'selected' : '' }}>
                        {{ $hall->entertainmentVenue->name }}, hall {{ $hall->id }}
                    </option>
                @endforeach
            </select> --}}

            <div class="col-md-9">
                <div class="container">
                    <div class="row">
                        <div class="col-md-7">
                            <h3>Drag and Drop Area</h3>
                            <svg id="drag-drop-area" width="502px" height="502px" style="border: 2px dashed #ccc;"
                                xmlns="http://www.w3.org/2000/svg" version="1.1"
                                xmlns:xlink="http://www.w3.org/1999/xlink">
                                <g stroke="#e8e8e8" stroke-width="1">
                                    @for ($i = 0; $i <= 100; $i++)
                                        <line x1="{{ $i * 5 }}" y1="0" x2="{{ $i * 5 }}"
                                            y2="500" />
                                    @endfor

                                    @for ($i = 0; $i <= 100; $i++)
                                        <line x1="0" y1="{{ $i * 5 }}" x2="500"
                                            y2="{{ $i * 5 }}" />
                                    @endfor
                                </g>
                                <g id="places" width="100%" height="100%">
                                    @foreach (json_decode($halls[1]->layout) as $element)
                                        @if ($element->type == 'table')
                                            {
                                            <circle class="element" data-element-type="table" data-height="20"
                                                cx="{{ $element->x + $element->width }}"
                                                cy="{{ $element->y + $element->width }}" r="{{ $element->width }}"
                                                style="fill: lightblue;"></circle>
                                            }
                                        @endif
                                        @if ($element->type == 'seat')
                                            {
                                            <rect class="element" width = " {{ $element->width }}"
                                                height = " {{ $element->height }}" x="{{ $element->x }}"
                                                y="{{ $element->y }}" data-element-type="seat" style="fill: lightgreen;">
                                            </rect>
                                            }
                                        @endif
                                    @endforeach
                                </g>
                            </svg>
                        </div>

                        <div class="col-md-5">
                            <div class="card">
                                <div class="card-body">
                                    <h6>Seat Groups</h6>
                                    <div id="seat-groups">
                                    </div>
                                    <select name="groups[]" id="input-groups" multiple style="display: none;">
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <select name="event_id" id="event_id">
                @foreach ($events as $event)
                    <option value="{{ $event->id }}" {{ old('event') == $event->name ? 'selected' : '' }}>
                        {{ $event->name }}
                    </option>
                @endforeach
            </select>

            <div class="form-group">
                <label for="start_time">Start time:</label>
                <input type="datetime-local" class="form-control" id="start_time" name="start_time"
                    value="{{ old('start_time', $session->start_time) }}">
                @error('start_time')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="start_time">End time:</label>
                <input type="datetime-local" class="form-control" id="end_time" name="end_time"
                    value="{{ old('end_time', $session->end_time) }}">
                @error('end_time')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Save</button>
        </form>
    </div>

    <script>
        const hallInput = document.getElementById("hall");
        const datalistHalls = document.getElementById("hall-options");

        function fetchHalls(searchHall) {
            $.ajax({
                type: 'GET',
                url: "{{ route('admin.entertainment_venues.search') }}",
                data: {
                    name: searchHall
                },
                success: function(halls) {
                    console.log(halls);

                    var hallsArray = Object.keys(halls).map(function(key) {
                        return halls[key];
                    });
                    datalistHalls.innerHTML = hallsArray.reduce(
                        (layout, hall) =>
                        (layout += `<option value="${hall.id}">${hall.name}</option>`),
                        ``
                    );
                },
                error: function(response) {
                    console.error(response);
                }
            });


        }

        function debounce(func, timeout = 300) {
            let timer;
            return (...args) => {
                clearTimeout(timer);
                timer = setTimeout(() => {
                    func.apply(this, args);
                }, timeout);
            };
        }
        hallInput.oninput = debounce(() => {
            console.log('Input!');
            const halls = fetchHalls(hallInput.value)
        });

        const hideElement = (element) => {
            element.classList.add("visually-hidden");
        };
        const showElement = (element) => {
            element.classList.remove("visually-hidden");
        };
        hallInput.onfocus = () => {
            showElement(datalistHalls);
        };
    </script>
@endsection
