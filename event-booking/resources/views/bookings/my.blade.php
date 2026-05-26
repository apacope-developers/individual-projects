<!DOCTYPE html>
<html>
<head>
    <title>My Bookings</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

body{

    background:
        radial-gradient(circle at top left,#0f172a,#020617 60%);

    min-height:100vh;

    color:white;

    font-family:'Poppins',sans-serif;
}

/* CONTAINER */

.container{

    max-width:1200px;
}

/* PAGE TITLE */

.page-title{

    font-size:48px;

    font-weight:700;

    letter-spacing:-2px;

    margin-bottom:45px;
}

/* BOOKING CARD */

.card-booking{

    background:rgba(15,23,42,0.75);

    border:1px solid rgba(255,255,255,0.05);

    border-radius:28px;

    padding:30px;

    margin-bottom:28px;

    backdrop-filter:blur(16px);

    transition:0.4s ease;

    overflow:hidden;

    position:relative;
}

.card-booking:hover{

    transform:translateY(-8px);

    border-color:rgba(59,130,246,0.25);

    box-shadow:
        0 20px 60px rgba(0,0,0,0.45),
        0 0 25px rgba(59,130,246,0.12);
}

/* EVENT TITLE */

.event-title{

    font-size:30px;

    font-weight:700;

    margin-bottom:22px;

    letter-spacing:-1px;
}

/* META */

.event-meta{

    color:#cbd5e1;

    margin-bottom:12px;

    font-size:16px;
}

/* TICKET */

.ticket-type{

    margin-top:18px;

    color:#f8fafc;

    font-size:17px;
}

.ticket-type strong{

    color:#3b82f6;
}

/* STATUS SECTION */

.status-row{

    margin-top:22px;

    display:flex;

    align-items:center;

    gap:12px;

    flex-wrap:wrap;
}

/* BADGES */

.badge-paid{

    background:rgba(34,197,94,0.12);

    color:#22c55e;

    padding:8px 18px;

    border-radius:40px;

    font-size:13px;

    font-weight:600;
}

.badge-pending{

    background:rgba(234,179,8,0.12);

    color:#eab308;

    padding:8px 18px;

    border-radius:40px;

    font-size:13px;

    font-weight:600;
}

/* BUTTONS */

.action-buttons{

    margin-top:28px;

    display:flex;

    gap:14px;

    flex-wrap:wrap;
}

.btn-modern{

    padding:12px 22px;

    border-radius:14px;

    text-decoration:none;

    color:white;

    font-weight:600;

    transition:0.3s ease;

    border:none;
}

/* VIEW BUTTON */

.btn-view{

    background:rgba(255,255,255,0.08);

    border:1px solid rgba(255,255,255,0.08);
}

.btn-view:hover{

    background:rgba(255,255,255,0.14);

    color:white;

    transform:translateY(-2px);
}

/* RECEIPT BUTTON */

.btn-receipt{

    background:linear-gradient(
        135deg,
        #2563eb,
        #3b82f6
    );
}

.btn-receipt:hover{

    color:white;

    transform:translateY(-2px);

    box-shadow:
        0 10px 30px rgba(59,130,246,0.35);
}

/* BACK BUTTON */

.btn-back{

    display:inline-flex;

    align-items:center;

    gap:10px;

    margin-top:30px;

    padding:14px 26px;

    border-radius:16px;

    text-decoration:none;

    color:white;

    font-weight:600;

    background:rgba(255,255,255,0.06);

    border:1px solid rgba(255,255,255,0.05);

    transition:0.3s ease;
}

.btn-back:hover{

    background:rgba(255,255,255,0.1);

    color:white;

    transform:translateX(-3px);
}

/* EMPTY STATE */

.empty-state{

    margin-top:120px;

    text-align:center;

    opacity:0.7;
}

.empty-state h3{

    font-size:32px;

    font-weight:600;
}

/* RESPONSIVE */

@media(max-width:768px){

    .page-title{

        font-size:34px;
    }

    .card-booking{

        padding:22px;
    }

    .event-title{

        font-size:24px;
    }
}

    </style>
</head>

<body>

<div class="container py-5">

    <!-- TITLE -->

    <div class="page-title">
        My Bookings
    </div>

    <!-- BOOKINGS -->

    @if($bookings->count())

        @foreach($bookings as $booking)

            <div class="card-booking">

                <!-- EVENT TITLE -->

                <div class="event-title">
                    {{ $booking->event->title }}
                </div>

                <!-- DETAILS -->

                <div class="event-meta">
                    📅 {{ $booking->event->date }}
                </div>

                <div class="event-meta">
                    📍 {{ $booking->event->location }}
                </div>

                <!-- TICKET -->

                <div class="ticket-type">
                    🎫 Ticket:
                    <strong>
                        {{ strtoupper($booking->ticket_type) }}
                    </strong>
                </div>

                <!-- STATUS -->

                <div class="status-row">

                    <span>Status:</span>

                    @if($booking->payment_status == 'paid')

                        <span class="badge-paid">
                            Paid
                        </span>

                    @else

                        <span class="badge-pending">
                            Pending
                        </span>

                    @endif

                </div>

                <!-- BUTTONS -->

                <div class="action-buttons">

                    <a href="{{ route('events.show', $booking->event->id) }}"
                       class="btn-modern btn-view">

                        View Event
                    </a>

                    @if($booking->payment_status == 'paid')

                        <a href="{{ route('receipt.download', $booking->id) }}"
                           class="btn-modern btn-receipt">

                            Download Receipt
                        </a>

                    @endif

                </div>

            </div>

        @endforeach

    @else

        <div class="empty-state">

            <h3>No bookings yet</h3>

            <p class="text-secondary mt-3">
                Explore events and start booking amazing experiences.
            </p>

        </div>

    @endif

    <!-- BACK -->

    <a href="{{ route('events.index') }}"
       class="btn-back">

        ← Back to Events

    </a>

</div>

</body>
</html>