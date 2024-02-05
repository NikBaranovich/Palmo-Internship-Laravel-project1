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

        #labels {
            pointer-events: none;
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

        .popup {
            position: absolute;
            padding: 10px;
            background-color: #fff;
            border: 1px solid #ccc;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .selected {
            outline: 3px solid #ffcc00;
        }
    </style>
    <div class="container">
        <h2> {{ $ticket->exists ? 'Edit' : 'Add' }} Ticket</h2>

        @if (session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif


        <form method="post" enctype="multipart/form-data" id="session-form"
            action="{{ $ticket->exists ? route('admin.tickets.update', $ticket->id) : route('admin.tickets.store') }}">
            @csrf
            @if ($ticket->exists)
                @method('PUT')
            @endif

            <div class="form-group">
                <label for="venue_id">Select user</label>
                <fieldset class="position-relative">
                    <input type="text" class="form-control" autocomplete="off" list="" id="user" />
                    <datalist class="visually-hidden" id="user-options"> </datalist>
                    </select>
                </fieldset>
            </div>

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

            <div class="form-group">
                <label for="venue_id">Select event</label>
                <fieldset class="position-relative">
                    <input type="text" class="form-control" autocomplete="off" list="" id="event" />
                    <datalist class="visually-hidden" id="event-options"> </datalist>
                </fieldset>
            </div>

            <div class="form-group">
                <label for="session_id">Select session</label>
                <select id="session_id" name="session_id"> </select>
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
                                <g id="labels" width="100%" height="100%">

                                </g>
                            </svg>
                        </div>

                    </div>
                </div>

            </div>

            {{-- <div class="form-group">
                <label for="seat_id">Select seat</label>
                <select id="seat_id" name="seat_id"> </select>
            </div> --}}

            <input type="hidden" id="seat_id" name="seat_id">
            <input type="hidden" id="user_id" name="user_id">
            <input type="hidden" id="price" name="price">

            <button type="submit" class="btn btn-primary">Save</button>
        </form>

        <div id="popup" class="popup visually-hidden">
            <span id="popupText"></span>
        </div>
    </div>

    {{-- {{Vite::asset('resources/js/components/venues.js')}} --}}
    <script>
        const venueInput = document.getElementById("venue");
        const datalistVenues = document.getElementById("venue-options");

        const hallInput = document.getElementById("hall_id");

        const datalistUsers = document.getElementById("user-options");
        const userInput = document.getElementById("user");

        const userIdInput = document.getElementById("user_id");

        const datalistEvents = document.getElementById("event-options");
        const eventInput = document.getElementById("event");

        const sessionInput = document.getElementById("session_id");

        const seatInput = document.getElementById("seat_id");
        const priceInput = document.getElementById("price");

        const groupList = document.getElementById("groups");

        const form = document.getElementById("session-form");

        let eventId;
        let hallId;
        let layout;

        form.addEventListener("submit", saveForm);

        function checkSessionData() {
            if (!eventId || !hallId) {
                return;
            }
            fetchSessions();
        }

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

                    hallId = +hallsArray[0].id;
                    fetchHallItems(hallId);
                    checkSessionData();
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

        function fetchSessions() {
            $.ajax({
                type: 'GET',
                url: "{{ route('api.sessions.find') }}",
                data: {
                    hall_id: hallId,
                    event_id: eventId,
                },
                success: function(sessions) {
                    var sessionArray = Object.keys(sessions).map(function(key) {
                        return sessions[key];
                    });

                    fetchEnabledHallItems(sessionArray[0].id);

                    sessionInput.innerHTML = sessionArray.reduce(
                        (layout, session) =>
                        (layout +=
                            `<option value='${session.id}'>${session.start_time} ${session.end_time}</option>`
                        ),
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

                    layout = hallItems.layout;
                    displayLayout(hallItems.layout, hallItems.elements);
                },
                error: function(response) {
                    console.error(response);
                }
            });
        }

        let elements = [];

        function fetchEnabledHallItems(sessionId) {
            if (!sessionId) {
                return;
            }
            $.ajax({
                type: 'GET',
                url: "{{ route('api.halls.getEnabledHallElements') }}",
                data: {
                    session_id: sessionId
                },
                success: function(hallItems) {
                    displayLayout(layout, hallItems.items);
                    elements = hallItems.items;

                    seatInput.innerHTML = hallItems.items.reduce(
                        (layout, item) => {
                            if (item.is_enabled)
                                layout +=
                                `<option value='${item.id}'>${item.number}</option>`
                            return layout;
                        },
                        ``
                    );
                },
                error: function(response) {
                    console.error(response);
                }
            });
        }

        function fetchUsers(searchUser) {
            if (!searchUser) {
                return;
            }
            $.ajax({
                type: 'GET',
                url: "{{ route('api.users.search') }}",
                data: {
                    email: searchUser
                },
                success: function(users) {

                    var userArray = Object.keys(users).map(function(key) {
                        return users[key];
                    });
                    datalistUsers.innerHTML = userArray.reduce(
                        (layout, user) =>
                        (layout += `<option value="${user.id}">${user.email} (${user.name})</option>`),
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
            fetchVenues(venueInput.value, datalistVenues)
        });

        eventInput.oninput = debounce(() => {
            fetchEvents(eventInput.value)
        });

        userInput.oninput = debounce(() => {
            fetchUsers(userInput.value)
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
        userInput.onfocus = () => {
            showElement(datalistUsers);
        };

        eventInput.addEventListener("focusout", () => {
            handleSearchInputFocusOut(datalistEvents);
        });

        venueInput.addEventListener("focusout", () => {
            handleSearchInputFocusOut(datalistVenues);
        });

        userInput.addEventListener("focusout", () => {
            handleSearchInputFocusOut(datalistUsers);
        });

        function handleSearchInputFocusOut(datalist) {
            setTimeout(() => {
                hideElement(datalist);
            }, 300);
        }

        datalistVenues.addEventListener("click", handleDatalistVenueClick);
        datalistUsers.addEventListener("click", handleDatalistUsersClick);

        function handleDatalistVenueClick(event) {
            const target = event.target;

            if (target.tagName === "OPTION") {
                venueInput.value = target.text;
                fetchHalls(target.value);
            }
            hideElement(datalistVenues);
        }

        function handleDatalistUsersClick(event) {
            const target = event.target;

            if (target.tagName === "OPTION") {
                userInput.value = target.text;
                userIdInput.value = target.value;
            }
            hideElement(datalistUsers);
        }

        datalistEvents.addEventListener("click", handleDatalistEventClick);

        function handleDatalistEventClick(event) {
            const target = event.target;

            if (target.tagName === "OPTION") {
                eventInput.value = target.text;
                eventId = +target.value;
                checkSessionData();
            }
            hideElement(datalistEvents);
        }

        hallInput.addEventListener("change", handleHallInputClick);

        function handleHallInputClick(event) {
            hallId = +hallInput.value;
            checkSessionData();
            fetchHallItems(hallId);

            hideElement(datalistVenues);
        }

        sessionInput.addEventListener("change", handleSessionInputClick);

        function handleSessionInputClick(event) {
            sessionId = +sessionInput.value;
            fetchEnabledHallItems(sessionId);
        }


        function displayLayout(layout, elements) {
            const layoutElements = JSON.parse(layout);

            const svgContainer = document.getElementById("places");
            const labelContainer = document.getElementById("labels");

            while (svgContainer.firstChild) {
                svgContainer.removeChild(svgContainer.firstChild);
            }

            while (labelContainer.firstChild) {
                labelContainer.removeChild(labelContainer.firstChild);
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
                    circle.setAttribute("cx", layoutElement.x + layoutElement.width);
                    circle.setAttribute("cy", layoutElement.y + layoutElement.width);
                    circle.setAttribute("r", layoutElement.width);
                    circle.setAttribute("fill", element.color);
                    svgContainer.appendChild(circle);
                }

                if (layoutElement.type === 'seat') {

                    element = elements.find((element) => {
                        return element.id == layoutElement.id && element.type == 'seat';
                    })
                    const rect = document.createElementNS("http://www.w3.org/2000/svg", "rect");
                    rect.setAttribute("id", element.id);
                    rect.setAttribute("class", "element");
                    rect.setAttribute("width", layoutElement.width);
                    rect.setAttribute("height", layoutElement.height);
                    rect.setAttribute("x", layoutElement.x);
                    rect.setAttribute("y", layoutElement.y);
                    rect.setAttribute("data-price", element.price);
                    rect.setAttribute("fill", element.is_enabled != false ? element.color : '#808080');
                    svgContainer.appendChild(rect);
                    const label = document.createElementNS("http://www.w3.org/2000/svg", "text");
                    label.innerHTML = element.number;
                    label.setAttribute("x", layoutElement.x + layoutElement.width / 2);
                    label.setAttribute("y", layoutElement.y + layoutElement.height / 2);

                    labelContainer.appendChild(label);

                }
            });
        }

        const popup = document.getElementById('popup');
        const popupText = document.getElementById('popupText');

        document.getElementById("places").addEventListener('mouseover', function(event) {
            if (!event.target.id) {
                return;
            }
            let element = elements.find((element) => {
                return element.id == event.target.id && element.type == 'seat';
            })
            const rect = event.target.getBoundingClientRect();
            const text =
                `${element.group_name}, row ${element.group_number}, seat ${element.number}. Price: ${element.price} uah`;

            popupText.innerText = text;
            popup.style.top = rect.top + window.scrollY + 'px';
            popup.style.left = rect.right + window.scrollX + 'px';
            showElement(popup);
        });

        document.getElementById("places").addEventListener('mouseout', function(event) {
            if (!event.target.classList.contains('element')) {
                return;
            }
            hideElement(popup);

        });

        document.getElementById("places").addEventListener('click', function(event) {
            if (!event.target.id) {
                return;
            }

            const prevElement = document.querySelector('.element.selected');
            if (prevElement) {
                prevElement.classList.remove('selected');
            }

            event.target.classList.add('selected');


            seatInput.value = event.target.id;
            priceInput.value = elements.find((element) => {
                return element.id == event.target.id && element.type == 'seat';
            }).price;
        })
    </script>
@endsection
