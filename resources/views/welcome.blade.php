<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interact.js Demo</title>
    <script src="https://unpkg.com/interactjs/dist/interact.min.js"></script>

    <style>
        /* Стили для панели элементов */
        .draggable {
            margin-bottom: 10px;
            padding: 10px;
            cursor: move;
            background-color: #3498db;
            color: #fff;
            border-radius: 5px;
        }

        /* Стили для кружков */
        .draggable[data-shape="circle"] {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #e74c3c;
        }

        /* Стили для прямоугольников */
        .draggable[data-shape="rectangle"] {
            width: 60px;
            height: 40px;
            background-color: #2ecc71;
        }
    </style>
</head>

<body>

    <div style="display: flex;">
        <!-- Sidebar with available elements -->
        <div style="width: 200px; padding: 20px; background-color: #f0f0f0;">
            <h3>Available Elements</h3>
            <div id="element-list">
                <div class="element" data-element-type="circle"
                    style="width: 50px; height: 50px; background-color: lightblue; margin-bottom: 10px;"></div>
                <div class="element" data-element-type="rectangle"
                    style="width: 20px; height: 20px; background-color: lightgreen; margin-bottom: 10px;"></div>
            </div>
        </div>

        <!-- Main area for drag-and-drop -->
        <div style="flex: 1; padding: 20px;">
            <h3>Drag and Drop Area</h3>
            <div id="drag-drop-area" style="width: 100%; height: 400px; border: 2px dashed #ccc;"></div>
        </div>
    </div>

    <script>
        interact('.element')
            .pointerEvents({
                holdDuration: 50,
            })
            .on('hold', function(event) {
                var original = event.target;
                var clone = original.cloneNode(true);

                clone.classList.add('dragging');
                clone.classList.add('draggable-element');
                clone.classList.remove('element');
                clone.style.position = 'absolute';
                clone.style.zIndex = '9999';

                document.body.appendChild(clone);

                // Set initial position for the clone under the cursor

                clone.style.left = (event.clientX) + 'px';
                clone.style.top = (event.clientY) + 'px';

            });
        interact(".draggable-element")
            .resizable({
                edges: {
                    right: true,
                    bottom: true,
                },
                listeners: {
                    move(event) {

                        var target = event.target;
                        var x = (parseFloat(target.getAttribute('data-x')) || 0);
                        var y = (parseFloat(target.getAttribute('data-y')) || 0);

                        // Округляем размеры до ближайшего кратного 5
                        var width = Math.round(event.rect.width / 20) * 20;
                        var height = Math.round(event.rect.height / 20) * 20;

                        console.log(width);
                        console.log(height);
                        // update the element's style
                        target.style.width = width + 'px';
                        target.style.height = height + 'px';

                        // translate when resizing from top or left edges
                        x += event.deltaRect.left;
                        y += event.deltaRect.top;

                        target.style.transform = 'translate(' + x + 'px,' + y + 'px)';

                        target.setAttribute('data-x', x);
                        target.setAttribute('data-y', y);

                    }
                },

            })

            .draggable({
                modifiers: [
                    interact.modifiers.snap({
                        targets: [
                            interact.snappers.grid({
                                x: 30,
                                y: 30
                            })
                        ],
                        range: Infinity,
                        relativePoints: [{
                            x: 0,
                            y: 0
                        }]
                    }),
                    interact.modifiers.restrict({
                        elementRect: {
                            top: 0,
                            left: 0,
                            bottom: 1,
                            right: 1
                        },
                        endOnly: true
                    })
                ],
                inertia: true
            })
            .on('dragmove', function(event) {
                var target = event.target
                // keep the dragged position in the data-x/data-y attributes
                var x = (parseFloat(target.getAttribute('data-x')) || 0) + event.dx
                var y = (parseFloat(target.getAttribute('data-y')) || 0) + event.dy

                // translate the element
                target.style.transform = 'translate(' + x + 'px, ' + y + 'px)'

                // update the posiion attributes
                target.setAttribute('data-x', x)
                target.setAttribute('data-y', y)
            })
            .on('dragend', function(event) {});
    </script>

</body>

</html>
