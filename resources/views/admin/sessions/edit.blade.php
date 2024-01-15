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
            z-index: 999;
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
                <label for="venue_id">Select venue</label>
                <fieldset class="position-relative">
                    <input type="text" class="form-control" autocomplete="off" list="" name="venue_id"
                        id="venue" />
                    <datalist class="visually-hidden" id="venue-options"> </datalist>
                    {{-- @if (isset($errors['event-user-send'])) {
                        <div  class='invalid-input-error'>{$errors['event-user-send']}
                        </div>";
                    }
                    @endif --}}
                    </select>
                </fieldset>
            </div>

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
                    </select>
                </fieldset>
            </div>

            <div class="col-md-9">
                <div class="container">
                    <div class="row">
                        <div class="col-md-7">
                            <h3>Layout</h3>
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

                                </g>
                            </svg>
                        </div>

                        <div class="col-md-5">
                            <div class="card">
                                <div class="card-body">
                                    <h6>Seat Groups</h6>
                                    <div id="seat-groups">
                                    </div>
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
        const venueInput = document.getElementById("venue");
        const datalistVenues = document.getElementById("venue-options");
        const datalistHalls = document.getElementById("hall-options");
        const hallInput = document.getElementById("hall");
        const seatGroupsContainer = document.getElementById('seat-groups');

        function fetchVenues(searchVenue) {
            if (!searchVenue) {
                return;
            }
            $.ajax({
                type: 'GET',
                url: "{{ route('api.entertainment_venues.search') }}",
                data: {
                    name: searchVenue
                },
                success: function(venues) {

                    var venuesArray = Object.keys(venues).map(function(key) {
                        if (venues[key].name === searchVenue) {
                            fetchHalls(venues[key].id);
                        } else {
                            datalistHalls.innerHTML = "";
                        }
                        return venues[key];
                    });
                    datalistVenues.innerHTML = venuesArray.reduce(
                        (layout, venue) =>
                        (layout += `<option value="${venue.id}">${venue.name} </option>`),
                        ``
                    );
                },
                error: function(response) {
                    console.error(response);
                }
            });
        }

        function fetchHalls(searchHall) {
            if (!searchHall) {
                return;
            }
            $.ajax({
                type: 'GET',
                url: "{{ route('api.halls.search') }}",
                data: {
                    venueId: searchHall
                },
                success: function(halls) {
                    var hallsArray = Object.keys(halls).map(function(key) {
                        return halls[key];
                    });
                    datalistHalls.innerHTML = hallsArray.reduce(
                        (layout, hall) =>
                        (layout += `<option value='${JSON.stringify({
                            id: hall.id,
                            layout: hall.layout
                        })}'>${hall.id} Hall</option>`),
                        ``
                    );
                },
                error: function(response) {
                    console.error(response);
                }
            });
        }

        function fetchGroups(hallId) {
            if (!hallId) {
                return;
            }
            $.ajax({
                type: 'GET',
                url: "{{ route('api.seat_groups.search') }}",
                data: {
                    hallId
                },
                success: function(groups) {
                    console.log(groups);
                    const groupsArray = Object.keys(groups).map(function(key) {
                        return groups[key];
                    });
                    seatGroupsContainer.innerHTML = groupsArray.reduce(
                        (layout, group) =>
                        (layout += `<div>
                            <label>
                            <input type="radio" name="seatGroup" value='${group.id}'>
                            <strong>${group.name}</strong>
                            <span>${group.number}</span>
                            </label>
                            </div>`),
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
        venueInput.oninput = debounce(() => {
            const halls = fetchVenues(venueInput.value)
        });

        const hideElement = (element) => {
            element.classList.add("visually-hidden");
        };
        const showElement = (element) => {
            element.classList.remove("visually-hidden");
        };

        venueInput.onfocus = () => {
            showElement(datalistVenues);
        };
        hallInput.onfocus = () => {
            showElement(datalistHalls);
        };
        hallInput.addEventListener("focusout", () => {
            handleSearchInputFocusOut(datalistHalls);
        });

        venueInput.addEventListener("focusout", () => {
            handleSearchInputFocusOut(datalistVenues);
        });

        function handleSearchInputFocusOut(datalist) {
            setTimeout(() => {
                hideElement(datalist);
            }, 300);
        }

        datalistVenues.addEventListener("click", handleDatalistVenueInputClick);

        function handleDatalistVenueInputClick(event) {
            const target = event.target;

            if (target.tagName === "OPTION") {
                venueInput.value = target.text;
                fetchHalls(target.value);
            }
            hideElement(datalistVenues);
        }

        datalistHalls.addEventListener("click", handleDatalistHallsInputClick);

        function handleDatalistHallsInputClick(event) {
            const target = event.target;

            if (target.tagName === "OPTION") {
                hall = JSON.parse(target.value);

                hallInput.value = target.text;
                fetchGroups(hall.id);
                displayLayout(hall.layout);
            }

            hideElement(datalistVenues);
        }

        function displayLayout(layout) {
            const elements = JSON.parse(layout);

            const svgContainer = document.getElementById("places");

            while (svgContainer.firstChild) {
                svgContainer.removeChild(svgContainer.firstChild);
            }

            elements.forEach(element => {
                console.log(element.id);
                element.id = parseInt(element.id);

                element.x = parseInt(element.x);
                element.y = parseInt(element.y);
                element.width = parseInt(element.width);
                element.height = parseInt(element.height);

                if (element.type === 'table') {

                    const circle = document.createElementNS("http://www.w3.org/2000/svg", "circle");
                    circle.setAttribute("class", "element");
                    circle.setAttribute("data-element-type", "table");
                    circle.setAttribute("data-height", "20");
                    circle.setAttribute("cx", element.x + element.width);
                    circle.setAttribute("cy", element.y + element.width);
                    circle.setAttribute("r", element.width);
                    circle.setAttribute("style", `fill: ${element.color};`);
                    svgContainer.appendChild(circle);
                }

                if (element.type === 'seat') {
                    const rect = document.createElementNS("http://www.w3.org/2000/svg", "rect");
                    rect.setAttribute("class", "element");
                    rect.setAttribute("width", element.width);
                    rect.setAttribute("height", element.height);
                    rect.setAttribute("x", element.x);
                    rect.setAttribute("y", element.y);
                    rect.setAttribute("data-element-type", "seat");
                    rect.setAttribute("style", `fill: ${element.color};`);
                    svgContainer.appendChild(rect);
                }
            });
        }
    </script>
@endsection
