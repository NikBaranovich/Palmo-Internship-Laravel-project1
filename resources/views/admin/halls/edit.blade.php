@extends('admin.layouts.app')

@section('content')
    <script src="https://unpkg.com/interactjs/dist/interact.min.js"></script>
    <style>
        .element {
            cursor: pointer;
        }

        .seat-number {
            color: white;
            font: 18px serif;
            height: 100%;
            width: 100%;
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
            pointer-events: none;
            background-color: rgb(98, 171, 98);
            border-radius: 5px;
        }

        .scene {
            color: white;
            font: 18px serif;
            height: 100%;
            width: 100%;
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
            pointer-events: none;
            background-color: rgb(149, 104, 32);
        }

        .color-circle {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .seat-group {
            display: inline-flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .seat-group-edit {
            display: inline-flex;
            align-items: center;
            margin-bottom: 10px;
            height: 20px;
            width: 20px;
        }

        .seat-group-edit span {
            display: inline-block;
            margin-left: -10px;
            font-size: 20px;
        }

        .group-name {
            margin-right: 10px;
        }

        .context {
            font-size: 1.1em;
            position: absolute;
            width: 200px;
            height: auto;
            padding: 5px 0px;
            border-radius: 5px;
            top: 10px;
            /* добавлен 'px' */
            left: 10px;
            /* добавлен 'px' */
            background-color: #fff;
            box-shadow: 0 12px 15px 0 rgba(0, 0, 0, 0.24);
            color: #333;
        }

        .context_item {
            height: 32px;
            line-height: 32px;
            cursor: pointer;
            text-overflow: ellipsis;
            overflow: hidden;
            white-space: nowrap;
        }

        .context_item:hover {
            background-color: #ddd;
        }

        .inner_item {
            margin: 0px 10px;
        }

        .inner_item i {
            margin: 0 5px 0 0;
            font-weight: bold;
        }

        .context_hr {
            height: 1px;
            border-top: 1px solid #bbb;
            margin: 3px 10px;
        }
    </style>

    <div class="container">
        @if (!$hall->exists)
            <h2>Add Hall</h2>
        @else
            <h2>Edit Hall</h2>
        @endif

        @if (session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif



        <form id='form' method="post" enctype="multipart/form-data"
            action="{{ $hall->exists ? route('admin.halls.update', ['entertainmentVenue' => $entertainmentVenue, 'hall' => $hall->id]) : route('admin.halls.store', ['entertainmentVenue' => $entertainmentVenue]) }}">
            @csrf
            @if ($hall->exists)
                @method('PUT')
            @endif

            <div class="form-group">
                <label for="name">Number:</label>
                <input type="number" class="form-control" id="number" name="number"
                    value="{{ old('number', $hall->number) }}">
                @error('number')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="container mt-5">
                <div class="row">
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body">
                                <h6>Available Elements</h6>
                                <div id="element-list">
                                    <svg style="width: 200px;" xmlns="http://www.w3.org/2000/svg" version="1.1"
                                        xmlns:xlink="http://www.w3.org/1999/xlink">

                                        <circle class="element" data-element-type="table" data-width="20" data-height="20"
                                            cx="20" cy="30" data-x="0" data-y="0" r="20"
                                            style="fill: lightblue;"></circle>
                                        <text x="50" y="35">Table</text>

                                        <foreignobject class="element" data-element-type="seat" data-width="20"
                                            data-height="20" data-x="0" data-y="0" x="10" y="70"
                                            style="width: 20px; height: 20px;">
                                            <div class="seat-number"></div>
                                        </foreignobject>
                                        <text x="50" y="85">Seat</text>

                                        <foreignobject class="element" data-element-type="scene" data-width="20"
                                            data-height="20" data-x="0" data-y="0" x="10" y="110"
                                            style="width: 50px; height: 20px;">
                                            <div class="scene">Scene</div>
                                        </foreignobject>
                                        <text x="75" y="125">Scene</text>

                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <h3>Hall Layout</h3>
                        <svg id="drag-drop-area" width="502px" height="502px" style="border: 2px dashed #ccc;"
                            xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink">
                            <g stroke="#ccc" stroke-width="1">
                                @for ($i = 0; $i <= 100; $i++)
                                    <line x1="{{ $i * 5 }}" y1="0" x2="{{ $i * 5 }}" y2="500" />
                                @endfor

                                @for ($i = 0; $i <= 100; $i++)
                                    <line x1="0" y1="{{ $i * 5 }}" x2="500" y2="{{ $i * 5 }}" />
                                @endfor
                            </g>
                            <g id="places" width="100%" height="100%">
                                @if (old('layout', $hall->exists))
                                    @php
                                        $hallItems = old('layout') ? [] : $hallItems;
                                    @endphp
                                    @foreach (json_decode(old('layout', $hallItems)) as $element)
                                        @switch($element->type)
                                            @case('seat')
                                                <foreignobject class="layout-element" data-element-type="seat"
                                                    data-width="{{ $element->width }}" data-height="{{ $element->height }}"
                                                    data-x="{{ $element->x }}" data-y="{{ $element->y }}"
                                                    data-group-id = "{{ $element->group }}" x="{{ $element->x }}"
                                                    y="{{ $element->y }}" data-color = '{{ $element->color }}'
                                                    data-number = '{{ $element->number }}'
                                                    style="width: {{ $element->width }}px; height: {{ $element->height }}px;">
                                                    <div class="seat-number" style="background-color: {{ $element->color }}">
                                                        {{ $element->number }}</div>
                                                </foreignobject>
                                            @break

                                            @case('table')
                                                <circle class="layout-element" data-element-type="table"
                                                    data-width="{{ $element->width }}" data-height="{{ $element->height }}"
                                                    cx="{{ $element->x + $element->width }}"
                                                    data-group-id = "{{ $element->group }}" data-color = '{{ $element->color }}'
                                                    cy="{{ $element->y + $element->height }}" data-x="{{ $element->x }}"
                                                    data-y="{{ $element->y }}" r="{{ $element->width }}"
                                                    style="fill: {{ $element->color }};"></circle>
                                            @break

                                            @case('scene')
                                                <foreignobject class="layout-element" data-element-type="scene"
                                                    data-width="{{ $element->width }}" data-height="{{ $element->height }}"
                                                    data-x="{{ $element->x }}" data-y="{{ $element->y }}"
                                                    x="{{ $element->x }}" y="{{ $element->y }}"
                                                    style="width: {{ $element->width }}px; height: {{ $element->height }}px;">
                                                    <div class="scene">Scene</div>
                                                </foreignobject>
                                            @break

                                            @default
                                        @endswitch
                                    @endforeach
                                @endif


                            </g>
                        </svg>
                    </div>

                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <h6>Seat Groups</h6>
                                <div id="seat-groups">
                                    @if ($hall->exists)
                                        @foreach ($hall->seatGroups as $group)
                                            <div class="group-container" data-id="{{ $group->id }}">
                                                <label>
                                                    <input type="radio" name="seatGroup"
                                                        value='{"groupId":"{{ $group->id }}","color":"{{ $group->color }}"}'>

                                                    <div class="seat-group">
                                                        <div class="color-circle"
                                                            style="background-color: {{ $group->color }};">
                                                        </div>
                                                        <div class="group-name">{{ $group->name }} {{ $group->number }}
                                                        </div>
                                                    </div>
                                                </label>
                                                <div class="seat-group-edit btn btn-primary">
                                                    <span class="material-icons-outlined"
                                                        onclick="openEditSeatGroupModal('{{ $group->id }}')">edit</span>
                                                </div>
                                            </div>
                                        @endforeach
                                    @elseif (old('groups'))
                                        @foreach (old('groups') as $group)
                                            <div class="group-container" data-id="{{ json_decode($group)->id }}">
                                                <label>
                                                    <input type="radio" name="seatGroup"
                                                        value='{"groupId":"{{ json_decode($group)->id }}","color":"{{ json_decode($group)->color }}"}'>

                                                    <div class="seat-group">
                                                        <div class="color-circle"
                                                            style="background-color: {{ json_decode($group)->color }};">
                                                        </div>
                                                        <div class="group-name">{{ json_decode($group)->name }}
                                                            {{ json_decode($group)->number }}
                                                        </div>
                                                    </div>
                                                </label>
                                                <div class="seat-group-edit btn btn-primary">
                                                    <span class="material-icons-outlined"
                                                        onclick="openEditSeatGroupModal('{{ json_decode($group)->id }}')">edit</span>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif

                                </div>

                                <button type="button" class="btn btn-success mt-3" onclick="openAddSeatGroupModal()">
                                    Add Seat Group
                                </button>

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
                            <input type="number" class="form-control" id="groupNumber" name="groupNumber" required>
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

    <div class="modal" id="editSeatGroupModal" tabindex="-1" role="dialog" aria-labelledby="editSeatGroupModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editSeatGroupModalLabel">Edit Seat Group</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                        onclick="closeEditSeatGroupModal()">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="editSeatGroupForm">
                        <div class="form-group">
                            <label for="editGroupName">Group Name:</label>
                            <input type="text" class="form-control" id="editGroupName" name="editGroupName" required>
                        </div>
                        <div class="form-group">
                            <label for="editGroupNumber">Group Number:</label>
                            <input type="number" class="form-control" id="editGroupNumber" name="editGroupNumber"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="editColor">Group Color:</label>
                            <input type="color" class="form-control" id="editColor" name="editColor" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeEditSeatGroupModal()">Close</button>
                    <button type="button" class="btn btn-danger" onclick="deleteSeatGroup()">Delete</button>
                    <button type="button" class="btn btn-primary" onclick="updateSeatGroup()">Save</button>
                </div>
            </div>
        </div>
    </div>

    <div class="context visually-hidden">

        <div class="context_item">
            <div class="inner_item">
                Delete
            </div>
        </div>
        <div class="context_item">
            <div class="inner_item">
                Paste
            </div>
        </div>
    </div>
    <script>
        const hideElement = (element) => {
            element.classList.add("visually-hidden");
        };
        const showElement = (element) => {
            element.classList.remove("visually-hidden");
        };
        document.addEventListener("contextmenu", function(event) {
            if (event.target.classList.contains('layout-element')) {
                event.preventDefault();
                var selectedElements = document.querySelectorAll('.layout-element.selected');

                selectedElements.forEach(function(element) {
                    element.classList.remove('selected');
                });
                event.target.classList.add('selected');
                var contextMenu = document.querySelector(".context");

                contextMenu.innerHTML = ` <div class="context_item">
                        <div class="inner_item">
                            Delete
                        </div>
                    </div>`;

                if (event.target.dataset.elementType == 'seat') {
                    contextMenu.innerHTML += `
                                <div class="context_item">
                            <div class="inner_item">
                                Change number
                            </div>
                        </div>`;
                }
                showElement(contextMenu);
                contextMenu.style.top = event.pageY + "px";
                contextMenu.style.left = event.pageX + "px";

            }
        });

        document.addEventListener("click", function(event) {
            var contextMenu = document.querySelector(".context");
            if (!contextMenu.contains(event.target)) {
                var selectedElements = document.querySelectorAll('.layout-element.selected');

                selectedElements.forEach(function(element) {
                    element.classList.remove('selected');
                });
                hideElement(contextMenu);
            }
        });

        const contextMenu = document.querySelector(".context");

        contextMenu.addEventListener("click", function(event) {
            var targetElement = event.target.closest('.inner_item');
            var selectedElement = document.querySelector('.layout-element.selected');

            if (targetElement.textContent.trim() === 'Delete') {
                if (selectedElement) {
                    selectedElement.remove();
                }
            } else if (targetElement.textContent.trim() === 'Change number') {
                var newSeatNumber = prompt("Enter new seat number:");
                if (newSeatNumber !== null && !isNaN(parseInt(newSeatNumber))) {
                    selectedElement.setAttribute('data-number', newSeatNumber);
                    selectedElement.querySelector('.seat-number').textContent = newSeatNumber;
                } else {
                    alert("Invalid input. Please enter a number.");
                }
                selectedElement.classList.remove('.selected');
            }
            hideElement(contextMenu);
        });

        const groupsHiddenInput = document.getElementById("input-groups");
        let groups = [];

        @if ($hall->exists)
            @foreach ($hall->seatGroups as $group)
                groupData = {
                    id: '{{ $group->id }}',
                    name: '{{ $group->name }}',
                    number: '{{ $group->number }}',
                    color: '{{ $group->color }}'
                };
                groups.push(JSON.stringify(groupData));
            @endforeach
        @elseif (old('groups'))
            @foreach (old('groups') as $group)
                groupData = {
                    id: '{{ json_decode($group)->id }}',
                    name: '{{ json_decode($group)->name }}',
                    number: '{{ json_decode($group)->number }}',
                    color: '{{ json_decode($group)->color }}'
                };
                groups.push(JSON.stringify(groupData));
            @endforeach
        @endif
        function openAddSeatGroupModal() {
            document.getElementById('addSeatGroupModal').style.display = 'block';
        }

        function openEditSeatGroupModal(groupId) {
            let group = groups.find(item => {
                return JSON.parse(item).id === groupId
            });
            group = JSON.parse(group);
            document.getElementById('editSeatGroupModal').dataset.id = group.id;
            document.getElementById('editSeatGroupModal').style.display = 'block';
            document.getElementById('editGroupName').value = group.name;
            document.getElementById('editGroupNumber').value = group.number;
            document.getElementById('editColor').value = group.color;
        }

        function closeEditSeatGroupModal() {
            document.getElementById('editSeatGroupModal').style.display = 'none';

            document.getElementById('groupName').value = '';
            document.getElementById('groupNumber').value = '';
        }

        function closeAddSeatGroupModal() {
            document.getElementById('addSeatGroupModal').style.display = 'none';

            document.getElementById('groupName').value = '';
            document.getElementById('groupNumber').value = '';
        }

        let groupCounter = 1;

        function updateSeatGroup() {
            var groupName = document.getElementById('editGroupName').value;
            var groupNumber = document.getElementById('editGroupNumber').value;
            var color = document.getElementById('editColor').value;

            const id = document.getElementById('editSeatGroupModal').dataset.id;
            var seatGroupsContainer = document.getElementById('seat-groups');
            var group = seatGroupsContainer.querySelector(`.group-container[data-id="${id}"]`);
            console.log(group);
            group.querySelector('.color-circle').style.backgroundColor = color;
            group.querySelector('.group-name').innerHTML = `${groupName} ${groupNumber}`;

            var groupIndex = groups.findIndex(group => {
                return JSON.parse(group).id == id
            });
            if (groupIndex !== -1) {
                groupData = {
                    id,
                    name: groupName,
                    number: groupNumber,
                    color
                };
                groups[groupIndex] = JSON.stringify(groupData);
            }
            closeEditSeatGroupModal();
        }

        function deleteSeatGroup() {
            var groupName = document.getElementById('editGroupName').value;
            var groupNumber = document.getElementById('editGroupNumber').value;
            var color = document.getElementById('editColor').value;

            const id = document.getElementById('editSeatGroupModal').dataset.id;
            var seatGroupsContainer = document.getElementById('seat-groups');
            var group = seatGroupsContainer.querySelector(`.group-container[data-id="${id}"]`);
            group.remove();

            var elementsToRemove = document.querySelectorAll(`.layout-element[data-group-id="${id}"]`);
            elementsToRemove.forEach(function(element) {
                element.remove();
            });

            var groupIndex = groups.findIndex(group => {
                return JSON.parse(group).id == id
            });
            if (groupIndex !== -1) {
                groups.splice(groupIndex, 1);
            }
            closeEditSeatGroupModal();
        }

        function saveSeatGroup() {
            var groupName = document.getElementById('groupName').value;
            var groupNumber = document.getElementById('groupNumber').value;
            var color = document.getElementById('color').value;
            closeAddSeatGroupModal();

            var seatGroupsContainer = document.getElementById('seat-groups');
            var newGroup = document.createElement('div');
            let groupId = '{{ uniqid() }}' + groupCounter;
            newGroup.dataset.id = groupId;
            newGroup.classList.add("group-container");
            newGroup.innerHTML = `
                <label>
                    <input type="radio" name="seatGroup" value='${JSON.stringify({groupId, color})}' checked>

                    <div class="seat-group">
                            <div class="color-circle" style="background-color: ${color};"></div>
                            <div class="group-name">${groupName} ${groupNumber}</div>
                    </div>
                </label>
                <div class="seat-group-edit btn btn-primary">
                    <span class="material-icons-outlined"
                         onclick="openEditSeatGroupModal('${groupId}')">edit</span>
                </div>
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

            groupsHiddenInput.innerHTML += groups.reduce((layout, group) => {
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
                            group: element.dataset.groupId,
                            color: element.dataset.color,

                        });
                        break;
                    case 'seat':
                        places.push({
                            type: 'seat',
                            number: element.dataset.seatNumber,
                            x: element.dataset.x,
                            y: element.dataset.y,
                            width: element.dataset.width,
                            height: element.dataset.height,
                            group: element.dataset.groupId,
                            color: element.dataset.color,
                            number: element.dataset.number,
                        });
                        break;
                    case 'scene':
                        places.push({
                            type: 'scene',
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
                let selectedGroup;
                switch (clone.dataset.elementType) {
                    case 'table':
                        selectedGroup = document.querySelector('input[name="seatGroup"]:checked');
                        if (!selectedGroup) {
                            return;
                        }

                        clone.setAttribute('cx', '20');
                        clone.setAttribute('cy', '20');
                        selectedGroup = JSON.parse(selectedGroup.value);
                        clone.setAttribute('data-group-id', selectedGroup.groupId);
                        clone.setAttribute('data-color', selectedGroup.color);
                        clone.style.fill = selectedGroup.color;
                        clone.classList.add('layout-element');
                        clone.classList.remove('element');

                        placesGroup.appendChild(clone);
                        break;
                    case 'seat':
                        selectedGroup = document.querySelector('input[name="seatGroup"]:checked');
                        if (!selectedGroup) {
                            return;
                        }
                        var seatNumber = prompt("Enter seat number", "");

                        if (seatNumber !== null && !isNaN(parseInt(seatNumber))) {
                            clone.setAttribute('x', '0');
                            clone.setAttribute('y', '0');
                            selectedGroup = JSON.parse(selectedGroup.value);
                            clone.setAttribute('data-group-id', selectedGroup.groupId);
                            clone.setAttribute('data-color', selectedGroup.color);
                            clone.setAttribute('data-number', seatNumber);
                            clone.innerHTML = '<div class="seat-number">' + seatNumber + '</div>';
                            clone.querySelector('.seat-number').style.backgroundColor = selectedGroup.color;
                            clone.classList.add('layout-element');
                            clone.classList.remove('element');

                            placesGroup.appendChild(clone);
                        } else {
                            alert("Invalid input. Please enter a number.");
                        }
                        break;
                    case 'scene':
                        clone.setAttribute('x', '0');
                        clone.setAttribute('y', '0');
                        clone.classList.add('layout-element');
                        clone.classList.remove('element');

                        placesGroup.appendChild(clone);
                        break;
                }



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
