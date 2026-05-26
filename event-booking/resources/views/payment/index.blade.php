<!DOCTYPE html>
<html>
<head>
    <title>Payments</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #0f172a, #020617);
            color: white;
            font-family: 'Segoe UI', sans-serif;
        }

        .card {
            background: #0f172a;
            border: 1px solid rgba(255,255,255,0.05);
            border-radius: 15px;
        }

        .status-paid {
            color: #22c55e;
        }

        .status-pending {
            color: #f59e0b;
        }
    </style>
</head>
<body>

<div class="container py-5">

    <h2 class="mb-4">💳 My Payments</h2>

    @if($bookings->count())

        @foreach($bookings as $booking)

            <div class="card p-3 mb-3">

                <h5>{{ $booking->event->title }}</h5>

                <p>Ticket: {{ $booking->ticket_type }}</p>

                <p>
                    Status:
                    <strong class="{{ $booking->payment_status == 'paid' ? 'status-paid' : 'status-pending' }}">
                        {{ $booking->payment_status ?? 'pending' }}
                    </strong>
                </p>

                @if($booking->payment_status !== 'paid')
                    <a href="{{ route('payments.show', $booking->id) }}" class="btn btn-success">
                        Pay Now
                    </a>
                @endif

            </div>

        @endforeach

    @else
        <p>No payments yet 😢</p>
    @endif

</div>

</body>
</html>