@extends('admin.layouts.app')

@section('content')
    <script src="https://unpkg.com/interactjs/dist/interact.min.js"></script>

    <div class="container">
        @if (!$hall->exists)
            <h2>Add Venue</h2>
        @else
            <h2>Edit Venue</h2>
        @endif

        @if (session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif



        <div class="row">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h6>Available Elements</h6>
                        <div id="element-list">
                            <svg style="width: 100px;" xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink">
                                <circle class="element" data-element-type="table" data-width="20" data-height="20"
                                    cx="20" cy="20" r="20" style="fill: lightblue;"></circle>
                                <rect class="element" data-element-type="seat" data-width="20" data-height="20"
                                    style="width: 20px; height: 20px; fill: lightgreen;"></rect>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-9">
                <form id='form' method="post" enctype="multipart/form-data"
                    action="{{ $hall->exists ? route('admin.halls.update', ['entertainmentVenue' => $entertainmentVenue, 'hall' => $hall->id]) : route('admin.halls.store', ['entertainmentVenue' => $entertainmentVenue]) }}">
                    @csrf
                    @if ($hall->exists)
                        @method('PUT')
                    @endif

                    <div class="form-group">
                        <label for="name">Number:</label>
                        <input type="text" class="form-control" id="number" name="number"
                            value="{{ old('number', $hall->number) }}">
                        @error('number')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

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
                                    <g id="places" width="100%" height="100%"></g>
                                </svg>
                            </div>

                            <div class="col-md-5">
                                <div class="card">
                                    <div class="card-body">
                                        <h6>Seat Groups</h6>
                                        <div id="seat-groups">
                                        </div>

                                        <button type="button" class="btn btn-success mt-3"
                                            onclick="openAddSeatGroupModal()">Add Seat
                                            Group</button>

                                        <select name="groups[]" id="input-groups" multiple style="display: none;">
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" id="layout" name="layout">
                    <input type="hidden" id="entertainment-venue-id" name="entertainment-venue-id"
                        value={{ $entertainmentVenue->id }}>
                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>


        </div>
    </div>
    <div class="modal" id="addSeatGroupModal" tabindex="-1" role="dialog" aria-labelledby="addSeatGroupModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addSeatGroupModalLabel">Add Seat Group</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                        onclick="closeAddSeatGroupModal()">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="addSeatGroupForm">
                        <div class="form-group">
                            <label for="groupName">Group Name:</label>
                            <input type="text" class="form-control" id="groupName" name="groupName" required>
                        </div>
                        <div class="form-group">
                            <label for="groupNumber">Group Number:</label>
                            <input type="text" class="form-control" id="groupNumber" name="groupNumber" required>
                        </div>
                        <div class="form-group">
                            <label for="groupName">Group Color:</label>
                            <input type="color" class="form-control" id="color" name="color" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeAddSeatGroupModal()">Close</button>
                    <button type="button" class="btn btn-primary" onclick="saveSeatGroup()">Save</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        const groupsHiddenInput = document.getElementById("input-groups");
        let groups = [];

        function openAddSeatGroupModal() {
            document.getElementById('addSeatGroupModal').style.display = 'block';
        }

        function closeAddSeatGroupModal() {
            document.getElementById('addSeatGroupModal').style.display = 'none';

            document.getElementById('groupName').value = '';
            document.getElementById('groupNumber').value = '';
        }

        let groupCounter = 1;

        function saveSeatGroup() {
            var groupName = document.getElementById('groupName').value;
            var groupNumber = document.getElementById('groupNumber').value;
            var color = document.getElementById('color').value;
            closeAddSeatGroupModal();

            var seatGroupsContainer = document.getElementById('seat-groups');
            var newGroup = document.createElement('div');
            let groupId = '{{ uniqid() }}' + groupCounter;
            newGroup.innerHTML = `
                <label>
                    <input type="radio" name="seatGroup" value='${JSON.stringify({groupId, color})}'>
                    <strong>${groupName}</strong>
                    <span>${groupNumber}</span>
                </label>
            `;
            seatGroupsContainer.appendChild(newGroup);
            groupData = {
                id: groupId,
                name: groupName,
                number: groupNumber,
                color
            };
            groups.push(JSON.stringify(groupData));

            groupCounter++;
            closeAddSeatGroupModal();
        }





        const elementList = document.getElementById('element-list');
        const canvasArea = document.getElementById('drag-drop-area');
        const placesGroup = document.getElementById('places');

        var placesDataInput = document.getElementById('layout');

        document.querySelector('form').addEventListener('submit', function() {
            let placesData = getPlacesData();
            setGroupsData();
            placesDataInput.value = JSON.stringify(placesData);
        });


        function setGroupsData() {

            groupsHiddenInput.innerHTML = groups.reduce((layout, group) => {
                layout += `<option value='${group}' selected>${group}</option>`;
                return layout;
            }, "");
        }

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
                            group: element.dataset.groupId
                        });
                        break;
                    case 'seat':
                        places.push({
                            type: 'seat',
                            x: element.dataset.x,
                            y: element.dataset.y,
                            width: element.dataset.width,
                            height: element.dataset.height,
                            group: element.dataset.groupId
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

                var selectedGroup = JSON.parse(document.querySelector('input[name="seatGroup"]:checked').value);
                clone.setAttribute('data-group-id', selectedGroup.groupId);
                clone.style.fill = selectedGroup.color;
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
                        console.log("object");
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
