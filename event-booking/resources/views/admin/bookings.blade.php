<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #0f172a;
            color: white;
            font-family: 'Segoe UI', sans-serif;
        }

        .card-box {
            background: #1e293b;
            padding: 25px;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.4);
        }

        table {
            color: white;
        }

        th {
            background: #334155;
        }

        td {
            vertical-align: middle;
        }

        .badge-vip {
            background: gold;
            color: black;
        }

        .badge-regular {
            background: #38bdf8;
        }

        .status {
            font-weight: bold;
            color: #4ade80;
        }
    </style>
</head>

<body>

<div class="container py-5">

    <h2 class="mb-4">📊 Admin Dashboard - Bookings</h2>

    <div class="card-box">

        <table class="table table-dark table-hover rounded">
            <thead>
                <tr>
                    <th>#</th>
                    <th>👤 Name</th>
                    <th>📧 Email</th>
                    <th>🎟 Ticket</th>
                    <th>💰 Amount</th>
                    <th>📍 Event</th>
                    <th>📊 Status</th>
                </tr>
            </thead>

            <tbody>
                @foreach($bookings as $booking)
                <tr>
                    <td>{{ $loop->iteration }}</td>

                    <td>{{ $booking->name }}</td>

                    <td>{{ $booking->email }}</td>

                    <td>
                        @if($booking->ticket_type === 'vip')
                            <span class="badge badge-vip">VIP</span>
                        @else
                            <span class="badge badge-regular">Regular</span>
                        @endif
                    </td>

                    <td>
                        {{ $booking->ticket_type === 'vip' ? '15,000 RWF' : '5,000 RWF' }}
                    </td>

                    <td>{{ $booking->event->title ?? 'N/A' }}</td>

                    <td class="status">Confirmed</td>
                </tr>
                @endforeach
            </tbody>

        </table>

    </div>

</div>

</body>
</html>