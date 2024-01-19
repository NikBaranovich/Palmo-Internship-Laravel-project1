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

        .seat-group {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .group-name {
            margin-right: 10px;
        }

        .color-circle {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            margin-right: 10px;
        }
    </style>
    <div class="container">
        <h2> {{ $session->exists ? 'Edit' : 'Add' }} Session</h2>

        @if (session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif


        <form method="post" enctype="multipart/form-data" id="session-form"
            action="{{ $session->exists ? route('admin.sessions.update', $session->id) : route('admin.sessions.store') }}">
            @csrf
            @if ($session->exists)
                @method('PUT')
            @endif

            <div class="form-group">
                <label for="venue_id">Select venue</label>
                <fieldset class="position-relative">
                    <input type="text" class="form-control" autocomplete="off" list="" id="venue" />
                    <datalist class="visually-hidden" id="venue-options"> </datalist>
                    </select>
                </fieldset>
            </div>

            <div class="form-group">
                <label for="hall_id">Select hall</label>
                <select id="hall_id" name="hall_id"> </select>
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
                                    <select class="visually-hidden" id="groups" multiple name = "groups[]"> </select>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="form-group">
                <label for="venue_id">Select event</label>
                <fieldset class="position-relative">
                    <input type="text" class="form-control" autocomplete="off" list="" id="event" />
                    <datalist class="visually-hidden" id="event-options"> </datalist>
                    <input type="hidden" name="event_id" id="event_id" />
                </fieldset>
            </div>
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

            <button type="submit" class="btn btn-primary" onclick="saveForm">Save</button>
        </form>
    </div>

    <script>
        const csrfToken = document.head.querySelector(
            'meta[name="csrf-token"]'
        ).content;

        const venueInput = document.getElementById("venue");
        const eventIdInput = document.getElementById("event_id");

        const datalistVenues = document.getElementById("venue-options");
        const hallInput = document.getElementById("hall_id");

        const datalistEvents = document.getElementById("event-options");
        const eventInput = document.getElementById("event");
        const seatGroupsContainer = document.getElementById('seat-groups');

        const groupList = document.getElementById("groups");

        const form = document.getElementById("session-form");

        form.addEventListener("submit", saveForm);

        function saveForm(event) {
            const seatGroupsInput = document.querySelectorAll('.group-price');

            const groupData = {};

            groupList.innerHTML = [...seatGroupsInput].reduce((layout, group) => {
                let seat_group_id = group.id;
                let price = group.value;

                layout += `<option value='${JSON.stringify({
                    seat_group_id,
                    price
                })}' selected>${price}</option> `;
                return layout;
            }, "");
        }

        function fetchVenues(searchVenue) {
            if (!searchVenue) {
                return;
            }
            $.ajax({
                type: 'GET',
                url: "{{ route('api.entertainment_venues.search') }}",
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                data: {
                    name: searchVenue
                },
                success: function(venues) {

                    var venuesArray = Object.keys(venues).map(function(key) {
                        if (venues[key].name === searchVenue) {
                            fetchHalls(venues[key].id);
                        } else {
                            hallInput.innerHTML = "";
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
        let halls = [];

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
                success: function(response) {
                    var hallsArray = Object.keys(response).map(function(key) {
                        return response[key];
                    });
                    halls = hallsArray;

                    hallId = hallsArray[0].id;
                    fetchHallItems(hallId);

                    hallInput.innerHTML = hallsArray.reduce(
                        (layout, hall) =>
                        (layout += `<option value='${hall.id}'>${hall.number} Hall</option>`),
                        ``
                    );
                },
                error: function(response) {
                    console.error(response);
                }
            });
        }

        function fetchEvents(name) {
            if (!name) {
                return;
            }
            $.ajax({
                type: 'GET',
                url: "{{ route('api.events.search') }}",
                data: {
                    name
                },
                success: function(events) {
                    var eventsArray = Object.keys(events).map(function(key) {
                        return events[key];
                    });

                    datalistEvents.innerHTML = eventsArray.reduce(
                        (layout, event) =>
                        (layout += `<option value="${event.id}">${event.name} </option>`),
                        ``
                    );
                },
                error: function(response) {
                    console.error(response);
                }
            });
        }

        function fetchHallItems(hallId) {
            if (!hallId) {
                return;
            }
            $.ajax({
                type: 'GET',
                url: "{{ route('api.halls.getHallById') }}",
                data: {
                    hall_id: hallId
                },
                success: function(hallItems) {

                    seatGroupsContainer.innerHTML = hallItems.seat_groups.reduce(
                        (layout, group) =>
                        (layout += `
                        <div class="seat-group">
                        <div class="color-circle" style="background-color: ${group.color};"></div>
                        <div class="group-name">${group.name} ${group.number}</div>
                        <input type="number" class = "group-price" id = "${group.id}" placeholder="Enter price" />
                        </div>
                        `),
                        ''
                    );
                    displayLayout(hallItems.layout, hallItems.elements);
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
            fetchVenues(venueInput.value)
        });

        eventInput.oninput = debounce(() => {
            fetchEvents(eventInput.value)
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
        eventInput.onfocus = () => {
            showElement(datalistEvents);
        };
        eventInput.addEventListener("focusout", () => {
            handleSearchInputFocusOut(datalistEvents);
        });

        venueInput.addEventListener("focusout", () => {
            handleSearchInputFocusOut(datalistVenues);
        });

        function handleSearchInputFocusOut(datalist) {
            setTimeout(() => {
                hideElement(datalist);
            }, 300);
        }

        datalistVenues.addEventListener("click", handleDatalistVenueClick);

        function handleDatalistVenueClick(event) {
            const target = event.target;

            if (target.tagName === "OPTION") {
                venueInput.value = target.text;
                fetchHalls(target.value);
            }
            hideElement(datalistVenues);
        }

        datalistEvents.addEventListener("click", handleDatalistEventClick);

        function handleDatalistEventClick(event) {
            const target = event.target;

            if (target.tagName === "OPTION") {
                eventIdInput.value = target.value;
                eventInput.value = target.text;
            }
            hideElement(datalistEvents);
        }

        hallInput.addEventListener("change", handleHallInputClick);

        function handleHallInputClick(event) {
            hallId = hallInput.value;

            fetchHallItems(hallId);

            hideElement(datalistVenues);
        }


        function displayLayout(layout, elements) {
            const layoutElements = JSON.parse(layout);

            const svgContainer = document.getElementById("places");

            while (svgContainer.firstChild) {
                svgContainer.removeChild(svgContainer.firstChild);
            }

            layoutElements.forEach(layoutElement => {
                layoutElement.id = parseInt(layoutElement.id);
                layoutElement.x = parseInt(layoutElement.x);
                layoutElement.y = parseInt(layoutElement.y);
                layoutElement.width = parseInt(layoutElement.width);
                layoutElement.height = parseInt(layoutElement.height);

                if (layoutElement.type === 'table') {

                    element = elements.find((element) => {
                        return element.id == layoutElement.id && element.type == 'table';
                    })

                    const circle = document.createElementNS("http://www.w3.org/2000/svg", "circle");
                    circle.setAttribute("class", "element");
                    circle.setAttribute("data-element-type", "table");
                    circle.setAttribute("data-height", "20");
                    circle.setAttribute("cx", layoutElement.x + layoutElement.width);
                    circle.setAttribute("cy", layoutElement.y + layoutElement.width);
                    circle.setAttribute("r", layoutElement.width);
                    circle.setAttribute("style", `fill: ${element.color};`);
                    svgContainer.appendChild(circle);
                }

                if (layoutElement.type === 'seat') {

                    element = elements.find((element) => {
                        return element.id == layoutElement.id && element.type == 'seat';
                    })

                    const rect = document.createElementNS("http://www.w3.org/2000/svg", "rect");
                    rect.setAttribute("class", "element");
                    rect.setAttribute("width", layoutElement.width);
                    rect.setAttribute("height", layoutElement.height);
                    rect.setAttribute("x", layoutElement.x);
                    rect.setAttribute("y", layoutElement.y);
                    rect.setAttribute("data-element-type", "seat");
                    rect.setAttribute("style", `fill: ${element.color};`);
                    svgContainer.appendChild(rect);
                }
            });
        }
    </script>
@endsection
