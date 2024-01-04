@extends('admin.layouts.app')

@section('content')
    <script src="https://unpkg.com/interactjs/dist/interact.min.js"></script>
    <div class="container">
        @if (!$venue->exists)
            <h2>Add Venue</h2>
        @else
            <h2>Edit Venue</h2>
        @endif

        @if (session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif


        <form method="post" enctype="multipart/form-data"
            action="{{ $venue->exists ? route('admin.entertainment_venues.update', $venue->id) : route('admin.entertainment_venues.store') }}">
            @csrf
            @if ($venue->exists)
                @method('PUT')
            @endif


            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" class="form-control" id="name" name="name"
                    value="{{ old('name', $venue->name) }}">
                @error('name')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div style="display: flex;">
                <div style="width: 100px; padding: 20px; background-color: #f0f0f0;">
                    <h6>Available Elements</h6>

                    <div id="element-list">
                        <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink">
                            <circle class="element" data-element-type="table" data-width = "20" data-height = "20"
                                cx="20" cy="20" r="20" style="fill: lightblue;">
                            </circle>
                            <rect class="element" data-element-type="seat" data-width = "20" data-height = "20"
                                style="width: 20px; height: 20px; fill: lightgreen;">
                            </rect>
                        </svg>
                    </div>

                </div>

                <div style="flex: 1; padding: 20px;">
                    <h3>Drag and Drop Area</h3>
                    <svg id="drag-drop-area" width="502px" height="502px" style="border: 2px dashed #ccc;"
                        xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink">
                        <g stroke="#e8e8e8" stroke-width="1">
                            @for ($i = 0; $i <= 100; $i++)
                                @php
                                    $x = $i * 5;
                                @endphp
                                <line x1="{{ $x }}" y1="0" x2="{{ $x }}" y2="500" />
                            @endfor

                            @for ($i = 0; $i <= 100; $i++)
                                @php
                                    $y = $i * 5;
                                @endphp
                                <line x1="0" y1="{{ $y }}" x2="500" y2="{{ $y }}" />
                            @endfor
                        </g>
                        <g id="places" width="100%" height="100%">

                        </g>
                    </svg>
                </div>
            </div>
            <input type="hidden" id="layout" name="layout">

            <div class="form-group">
                <label for="city">city:</label>
                <input type="text" class="form-control" id="city" name="city"
                    value="{{ old('city', $venue->city) }}">
                @error('city')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="address">address:</label>
                <input type="text" class="form-control" id="address" name="address"
                    value="{{ old('address', $venue->address) }}">
                @error('address')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <input type="hidden" id="venue_type_id" name="venue_type_id" value="1">

            <button type="submit" class="btn btn-primary">Save</button>
        </form>
    </div>
    <script>
        const elementList = document.getElementById('element-list');
        const canvasArea = document.getElementById('drag-drop-area');
        const placesGroup = document.getElementById('places');

        var placesDataInput = document.getElementById('layout');

        document.querySelector('form').addEventListener('submit', function() {
            let placesData = getPlacesData();
            placesDataInput.value = JSON.stringify(placesData);
        });


        function getPlacesData() {

            let places = [];
            let elements = placesGroup.getElementsByClassName('layout-element');

            for (let element of elements) {
                switch (element.dataset.elementType) {
                    case 'table':
                        places.push({
                            type: 'table',
                            x: element.dataset.x,
                            y: element.dataset.y,
                            width: element.dataset.width,
                            height: element.dataset.height,
                        });
                        break;
                    case 'seat':
                        places.push({
                            type: 'seat',
                            x: element.dataset.x,
                            y: element.dataset.y,
                            width: element.dataset.width,
                            height: element.dataset.height,
                        });
                        break;

                }

            };

            return places;
        }

        interact('.element')
            .on('click', function(event) {
                let original = event.target;
                let clone = original.cloneNode(true);

                clone.classList.add('layout-element');
                clone.classList.remove('element');
                placesGroup.appendChild(clone);

            });
        interact(".layout-element")
            .resizable({
                edges: {
                    right: true,
                    bottom: true,
                },
                listeners: {
                    move(event) {

                        let target = event.target;

                        let width = Math.round(event.rect.width / 5) * 5;
                        let height = Math.round(event.rect.height / 5) * 5;

                        if (target.dataset.elementType === 'table') {
                            let previousRadius = target.getAttribute('r');
                            let radius = (width + height) / 2;
                            target.dataset.width = radius;
                            target.dataset.height = radius;
                            target.setAttribute('r', radius);
                        } else {
                            target.dataset.width = width;
                            target.dataset.height = height;
                            target.style.width = width + 'px';
                            target.style.height = height + 'px';
                        }

                    }
                },
                modifiers: [
                    interact.modifiers.restrictEdges({
                        outer: canvasArea
                    }),

                    interact.modifiers.restrictSize({
                        min: {
                            width: 5,
                            height: 5
                        }
                    })
                ],
            })

            .draggable({
                modifiers: [
                    interact.modifiers.snap({
                        targets: [
                            interact.snappers.grid({
                                x: 5,
                                y: 5
                            })
                        ],
                        range: Infinity,
                        relativePoints: [{
                            x: 0,
                            y: 0
                        }]
                    }),
                    interact.modifiers.restrict({
                        restriction: canvasArea,
                        elementRect: {
                            top: 0,
                            left: 0,
                            bottom: 1,
                            right: 1
                        },
                        endOnly: true
                    })
                ],
            })
            .on('dragmove', function(event) {
                let target = event.target
                let x = (parseFloat(target.getAttribute('data-x')) || 0) + Math.round(event.dx / 5) * 5;
                let y = (parseFloat(target.getAttribute('data-y')) || 0) + Math.round(event.dy / 5) * 5;

                if (target.dataset.elementType === 'table') {
                    let radius = Number(target.getAttribute('r'));
                    target.setAttribute('cx', x + radius);
                    target.setAttribute('cy', y + radius);

                    target.setAttribute('data-x', x)
                    target.setAttribute('data-y', y)
                } else {
                    target.setAttribute('x', x);
                    target.setAttribute('y', y);

                    target.setAttribute('data-x', x)
                    target.setAttribute('data-y', y)
                }
            })
            .on('dragend', function(event) {});
    </script>
@endsection
