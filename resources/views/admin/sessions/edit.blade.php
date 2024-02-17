@extends('admin.layouts.app')

@section('content')
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


        <form id="session-form" method="post" enctype="multipart/form-data"
            action="{{ $session->exists ? route('admin.sessions.update', $session->id) : route('admin.sessions.store') }}">
            @csrf
            @if ($session->exists)
                @method('PUT')
            @endif


            <div class="form-group">
                <label for="venue">Select venue</label>
                <fieldset class="position-relative">
                    <input type="text" value="{{ old('venue', $session->hall?->entertainmentVenue->name) }}"
                        class="form-control" autocomplete="off" list="" name="venue" id="venue" />
                    <datalist class="visually-hidden" id="venue-options"> </datalist>

                </fieldset>
            </div>

            <div @class([
                'hall-form-group',
                'visually-hidden' => !old('hall_id', $session),
            ])>
                <div class="form-group">
                    <label for="hall_id">Select hall</label>
                    <select id="hall_id" name="hall_id" class="form-control">

                    </select>
                </div>

                <div class="col-md-9 mt-4">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-7">
                                <h3>Hall Layout</h3>
                                <svg id="drag-drop-area" width="502px" height="502px" style="border: 2px dashed #ccc;"
                                    xmlns="http://www.w3.org/2000/svg" version="1.1"
                                    xmlns:xlink="http://www.w3.org/1999/xlink">

                                    <g id="places" width="100%" height="100%">

                                    </g>
                                </svg>
                            </div>

                            <div class="col-md-5 mt-4">
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
            </div>
            <div class="form-group">
                <label for="venue_id">Select event</label>
                <fieldset class="position-relative">
                    <input type="text" class="form-control" autocomplete="off" list="" name="event"
                        id="event" value="{{ old('event', $session->event?->title) }}" />

                    <datalist class="visually-hidden" id="event-options"> </datalist>
                    <input type="hidden" name="event_id" id="event_id" value="{{ old('event', $session->event_id) }}" />
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

        let halls = [];

        document.addEventListener("DOMContentLoaded", () => {
            @if ($session->hall?->entertainment_venue_id)
                fetchHalls({{ $session->hall->entertainment_venue_id }}, function(response) {
                    var hallsArray = Object.keys(response).map(function(key) {
                        return response[key];
                    });
                    halls = hallsArray;
                    if (!hallsArray.length) {
                        hideElement(document.querySelector('.hall-form-group'));
                        return;
                    }
                    showElement(document.querySelector('.hall-form-group'));
                    hallId = {{ $session->hall_id }};
                    hallInput.innerHTML = hallsArray.reduce(
                        (layout, hall) =>
                        (layout +=
                            `<option value='${hall.id}' ${hall.id == {{ $session->hall_id }} ? 'selected' : ''}>Hall ${hall.number}</option>`
                        ),
                        ``
                    );

                    console.log("object");
                    fetchHallItems(hallId, function(hallItems) {

                        @foreach ($session->sessionSeatGroups as $sessionSeatGroup)
                            seatGroupsContainer.innerHTML += `
                                <div class="seat-group">
                                <div class="color-circle" style="background-color: {{ $sessionSeatGroup->seatGroup->color }};"></div>
                                <div class="group-name">{{ $sessionSeatGroup->seatGroup->name }} {{ $sessionSeatGroup->seatGroup->number }}</div>
                                <input type="number" value="{{ $sessionSeatGroup->price }}" class="group-price" id = "{{ $sessionSeatGroup->seatGroup->id }}" placeholder="Enter price" />
                                </div>
                                `
                        @endforeach

                        displayLayout(hallItems.layout, hallItems.elements);
                    });
                })
            @endif

            @if (old('hall_id'))
                fetchVenues('{{ old('venue') }}', function(venues) {
                    console.log("object");
                    const venuesArray = Object.keys(venues).map(function(key) {
                        if (venues[key].name === venueInput.value) {
                            fetchHalls(venues[key].id, function(response) {
                                var hallsArray = Object.keys(response).map(function(key) {
                                    return response[key];
                                });
                                halls = hallsArray;
                                if (!hallsArray.length) {
                                    hideElement(document.querySelector('.hall-form-group'));
                                    return;
                                }
                                showElement(document.querySelector('.hall-form-group'));
                                hallId = {{ old('hall_id') }};

                                hallInput.innerHTML = hallsArray.reduce(
                                    (layout, hall) =>
                                    (layout +=
                                        `<option value='${hall.id}' ${hall.id == hallId ? 'selected' : ''}>Hall ${hall.number}</option>`
                                    ),
                                    ``
                                );
                                fetchHallItems(hallId, function(hallItems) {
                                    seatGroupsContainer.innerHTML = hallItems
                                        .seat_groups.reduce((layout, group) => {

                                            let oldValue = '';
                                            @if (old('groups'))
                                                let groups =
                                                    @json(old('groups'));

                                                let foundGroup = groups.find(
                                                    item => JSON.parse(item)
                                                    .seat_group_id ==
                                                    group.id);

                                                if (foundGroup) {
                                                    oldValue = JSON.parse(foundGroup).price;
                                                }
                                            @endif

                                            layout += `
                                                    <div class="seat-group">
                                                        <div class="color-circle" style="background-color: ${group.color};"></div>
                                                        <div class="group-name">${group.name} ${group.number}</div>
                                                        <input type="number" class="group-price" id="${group.id}" placeholder="Enter price" value="${oldValue}" />
                                                    </div>`;
                                            return layout;
                                        }, '');
                                    displayLayout(hallItems.layout, hallItems
                                        .elements);
                                });

                            });
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
                })
            @endif
        });

        function fetchHalls(venueId, success) {
            if (!venueId) {
                return;
            }
            $.ajax({
                type: 'GET',
                url: "{{ route('api.halls.search') }}",
                data: {
                    venueId
                },
                success,
                error: function(response) {
                    console.error(response);
                }
            });
        }

        function fetchEvents(title) {
            if (!title) {
                return;
            }
            $.ajax({
                type: 'GET',
                url: "{{ route('api.events.search') }}",
                data: {
                    title
                },
                success: function(events) {
                    var eventsArray = Object.keys(events).map(function(key) {
                        return events[key];
                    });

                    datalistEvents.innerHTML = eventsArray.reduce(
                        (layout, event) =>
                        (layout += `<option value="${event.id}">${event.title} </option>`),
                        ``
                    );
                },
                error: function(response) {
                    console.error(response);
                }
            });
        }

        function fetchHallItems(hallId, success) {
            if (!hallId) {
                return;
            }
            $.ajax({
                type: 'GET',
                url: "{{ route('api.halls.getHallById') }}",
                data: {
                    hall_id: hallId
                },
                success,
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
            hideElement(document.querySelector('.hall-form-group'));
            fetchVenues(venueInput.value, function(venues) {

                const venuesArray = Object.keys(venues).map(function(key) {
                    if (venues[key].name === venueInput.value) {
                        fetchHalls(venues[key].id, function(response) {
                            var hallsArray = Object.keys(response).map(function(key) {
                                return response[key];
                            });
                            halls = hallsArray;
                            if (!hallsArray.length) {
                                hideElement(document.querySelector('.hall-form-group'));
                                return;
                            }
                            showElement(document.querySelector('.hall-form-group'));
                            hallId = hallsArray[0].id;
                            fetchHallItems(hallId, function(hallItems) {

                                seatGroupsContainer.innerHTML = hallItems
                                    .seat_groups.reduce(
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
                            });
                            hallInput.innerHTML = hallsArray.reduce(
                                (layout, hall) =>
                                (layout +=
                                    `<option value='${hall.id}'>Hall ${hall.number}</option>`
                                ),
                                ``
                            );
                        });
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
            })
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
                fetchHalls(target.value, function(response) {
                    var hallsArray = Object.keys(response).map(function(key) {
                        return response[key];
                    });
                    halls = hallsArray;
                    if (!hallsArray.length) {
                        hideElement(document.querySelector('.hall-form-group'));
                        return;
                    }
                    showElement(document.querySelector('.hall-form-group'));
                    hallId = hallsArray[0].id;
                    fetchHallItems(hallId, function(hallItems) {

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
                    });
                    hallInput.innerHTML = hallsArray.reduce(
                        (layout, hall) =>
                        (layout += `<option value='${hall.id}'>Hall ${hall.number}</option>`),
                        ``
                    );
                });
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

            fetchHallItems(hallId, function(hallItems) {

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
            });

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
