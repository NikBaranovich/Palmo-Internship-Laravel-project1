<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .ticket {
            width: 100%;
            max-width: 600px;
            /* Adjust as needed */
            margin: 20px auto;
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 20px;
        }

        .ticket-sub,
        .ticket-main {
            margin-bottom: 20px;
            border-bottom: 1px solid #ccc;
            /* Добавляем линию разделения */
            padding-bottom: 20px;
            /* Добавляем отступ между билетами */
        }

        .ticket-sub:last-child,
        .ticket-main:last-child {
            border-bottom: none;
            /* Убираем линию разделения для последнего билета */
            padding-bottom: 0;
            /* Убираем отступ для последнего билета */
        }

        .ticket-seat-box,
        .ticket-info-event-title,
        .ticket-info-event,
        .ticket-info-enjoy,
        .ticket-info-date,
        .ticket-info-misc {
            margin-bottom: 10px;
        }

        h3,
        h4,
        p,
        span {
            margin: 0;
            padding: 0;
        }

        .ticket-seat-box p,
        .ticket-seat-box h4 {
            margin: 5px 0;
        }

        .ticket-seat-box p {
            font-weight: bold;
        }

        .qr-code {
            display: inline-block;
            border: 1px solid #ccc;
            padding: 10px;
        }
    </style>
</head>

<body>
    <main class="ticket">
        @foreach ($ticketsData as $data)
            <table class="ticket-main">
                <tr>
                    <td colspan="2">
                        <h3>Ticket Price <span>${{ intval($data['ticket']->price) }}<span></h3>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="ticket-seat-box">
                            <p>{{ $data['seatGroup']->name }}</p>
                            <h4>{{ $data['seatGroup']->number }}</h4>
                        </div>
                    </td>
                    <td>
                        <div class="ticket-seat-box">
                            <p>Seat</p>
                            <h4>{{ $data['seat']->number }}</h4>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <div class="ticket-info-event-title">
                            <h3>{{ $data['event']->title }}</h3>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <div class="ticket-info-event">
                            <div class="event-title">
                                <span>{{ $data['entertainmentVenue']->name }}, Hall {{ $data['hall']->number }}</span>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <div class="ticket-info-enjoy">
                            <span>Enjoy watching!</span>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <div class="ticket-info-date">
                            <p>{{ date('M j, Y, g:i a', strtotime($data['session']->start_time)) }}</p>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <div class="ticket-info-misc">
                            <p>This ticket is fictional</p>
                        </div>
                    </td>
                </tr>
            </table>
        @endforeach
    </main>
</body>

</html>
