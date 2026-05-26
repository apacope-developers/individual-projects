<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $event->title }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>

        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
        }

        body{
            font-family:'Segoe UI',sans-serif;
            background:
                linear-gradient(rgba(2,6,23,0.92), rgba(2,6,23,0.95)),
                url('/images/bg.png');

            background-size:cover;
            background-position:center;
            background-attachment:fixed;
            color:white;
            min-height:100vh;
        }

        /* HERO */
        .hero{
            position:relative;
            height:520px;
            overflow:hidden;
        }

        .hero-image{
            position:absolute;
            inset:0;
            background-size:cover;
            background-position:center;
            transform:scale(1.05);
        }

        .hero-overlay{
            position:absolute;
            inset:0;
            background:
                linear-gradient(to top, rgba(2,6,23,1), rgba(2,6,23,0.4)),
                linear-gradient(to right, rgba(2,6,23,0.8), rgba(2,6,23,0.2));
        }

        .hero-content{
            position:relative;
            z-index:2;
            max-width:1200px;
            margin:auto;
            height:100%;
            display:flex;
            align-items:flex-end;
            padding:60px 20px;
        }

        .hero-box{
            max-width:700px;
        }

        .event-badge{
            display:inline-block;
            padding:10px 18px;
            border-radius:50px;
            background:rgba(59,130,246,0.15);
            color:#60a5fa;
            font-weight:600;
            margin-bottom:20px;
            border:1px solid rgba(96,165,250,0.2);
        }

        .hero-title{
            font-size:64px;
            font-weight:900;
            line-height:1.1;
            margin-bottom:20px;
        }

        .hero-meta{
            display:flex;
            gap:25px;
            flex-wrap:wrap;
            color:#cbd5e1;
            font-size:17px;
        }

        /* MAIN */
        .main-section{
            max-width:1200px;
            margin:auto;
            padding:50px 20px 80px;
        }

        /* CARDS */
        .glass-card{
            background:rgba(15,23,42,0.82);
            border:1px solid rgba(255,255,255,0.06);
            backdrop-filter:blur(12px);
            border-radius:28px;
            padding:35px;
            box-shadow:0 20px 60px rgba(0,0,0,0.4);
            transition:0.3s ease;
        }

        .glass-card:hover{
            transform:translateY(-4px);
            border-color:rgba(59,130,246,0.25);
        }

        .section-title{
            font-size:32px;
            font-weight:800;
            margin-bottom:18px;
        }

        .section-text{
            color:#cbd5e1;
            line-height:1.8;
            font-size:16px;
        }

        /* STATS */
        .stats-grid{
            display:grid;
            grid-template-columns:repeat(3,1fr);
            gap:20px;
            margin-top:35px;
        }

        .stat-box{
            background:rgba(2,6,23,0.7);
            border:1px solid rgba(255,255,255,0.05);
            border-radius:22px;
            padding:25px;
            text-align:center;
        }

        .stat-number{
            font-size:38px;
            font-weight:800;
            color:#60a5fa;
            margin-bottom:5px;
        }

        .stat-label{
            color:#94a3b8;
            font-size:15px;
        }

        /* PROGRESS */
        .progress-wrapper{
            margin-top:35px;
        }

        .progress{
            height:14px;
            border-radius:20px;
            background:rgba(255,255,255,0.08);
            overflow:hidden;
        }

        .progress-bar{
            background:linear-gradient(135deg,#3b82f6,#6366f1);
            border-radius:20px;
        }

        /* BOOKING CARD */
        .booking-card{
            position:sticky;
            top:30px;
        }

        .booking-title{
            font-size:30px;
            font-weight:800;
            margin-bottom:10px;
        }

        .booking-subtitle{
            color:#94a3b8;
            margin-bottom:30px;
        }

        /* INPUTS */
        .form-control{
            height:60px;
            background:rgba(2,6,23,0.75);
            border:1px solid rgba(255,255,255,0.08);
            border-radius:16px;
            color:white;
            padding:15px 20px;
            margin-bottom:18px;
            font-size:15px;
        }

        .form-control:focus{
            background:rgba(2,6,23,0.95);
            color:white;
            border-color:#3b82f6;
            box-shadow:0 0 0 4px rgba(59,130,246,0.15);
        }

        .form-control::placeholder{
            color:#64748b;
        }

        /* TICKET */
        .ticket-options{
            display:grid;
            grid-template-columns:1fr 1fr;
            gap:15px;
            margin-bottom:25px;
        }

        .ticket{
            background:rgba(2,6,23,0.75);
            border:2px solid transparent;
            border-radius:20px;
            padding:22px;
            text-align:center;
            cursor:pointer;
            transition:0.3s;
        }

        .ticket:hover{
            transform:translateY(-3px);
            border-color:rgba(59,130,246,0.3);
        }

        .ticket.active{
            border-color:#3b82f6;
            background:rgba(59,130,246,0.12);
        }

        .ticket input{
            display:none;
        }

        .ticket-name{
            font-size:22px;
            font-weight:700;
            margin-bottom:8px;
        }

        .ticket-price{
            color:#60a5fa;
            font-size:18px;
            font-weight:600;
        }

        /* BUTTON */
        .btn-book{
            width:100%;
            height:62px;
            border:none;
            border-radius:18px;
            background:linear-gradient(135deg,#2563eb,#6366f1);
            color:white;
            font-size:18px;
            font-weight:700;
            transition:0.3s ease;
        }

        .btn-book:hover{
            transform:translateY(-3px);
            box-shadow:0 20px 35px rgba(59,130,246,0.35);
        }

        /* ATTENDEES */
        .attendees-grid{
            display:grid;
            grid-template-columns:repeat(2,1fr);
            gap:15px;
            margin-top:25px;
        }

        .attendee{
            background:rgba(2,6,23,0.7);
            border:1px solid rgba(255,255,255,0.05);
            border-radius:16px;
            padding:18px;
            color:#e2e8f0;
        }

        /* ALERT */
        .alert-warning{
            background:rgba(234,179,8,0.12);
            border:1px solid rgba(234,179,8,0.2);
            color:#facc15;
            border-radius:18px;
            padding:18px;
        }

        /* BACK BUTTON */
        .btn-back{
            display:inline-flex;
            align-items:center;
            gap:10px;
            margin-top:35px;
            padding:14px 24px;
            border-radius:14px;
            background:rgba(255,255,255,0.08);
            color:white;
            text-decoration:none;
            font-weight:600;
            transition:0.3s;
        }

        .btn-back:hover{
            background:rgba(255,255,255,0.14);
            color:white;
            transform:translateX(-3px);
        }

        /* RESPONSIVE */
        @media(max-width:992px){

            .hero-title{
                font-size:46px;
            }

            .booking-card{
                position:relative;
                top:0;
            }
        }

        @media(max-width:768px){

            .hero{
                height:420px;
            }

            .hero-title{
                font-size:36px;
            }

            .stats-grid{
                grid-template-columns:1fr;
            }

            .ticket-options{
                grid-template-columns:1fr;
            }

            .attendees-grid{
                grid-template-columns:1fr;
            }

            .glass-card{
                padding:25px;
                border-radius:22px;
            }
        }

    </style>
</head>

<body>

@php
    $title = strtolower($event->title);

    if ($event->image) {
        $image = asset('storage/'.$event->image);
    } elseif (str_contains($title, 'volleyball')) {
        $image = asset('images/v.png');
    } elseif (str_contains($title, 'yoga')) {
        $image = asset('images/y.png');
    } elseif (str_contains($title, 'tech')) {
        $image = asset('images/t.png');
    } else {
        $image = asset('images/default.png');
    }
@endphp

<!-- HERO -->
<div class="hero">

    <div class="hero-image" style="background-image:url('{{ $image }}')"></div>

    <div class="hero-overlay"></div>

    <div class="hero-content">

        <div class="hero-box">

            <div class="event-badge">
                Premium Event Experience
            </div>

            <h1 class="hero-title">
                {{ $event->title }}
            </h1>

            <div class="hero-meta">
                <span>📍 {{ $event->location }}</span>
                <span>📅 {{ $event->date }}</span>
            </div>

        </div>

    </div>

</div>

<!-- MAIN -->
<div class="main-section">

    @php
        $current = $event->bookings->count();
        $max = $event->max_attendees;
        $remaining = $max - $current;
        $percentage = ($max > 0) ? ($current / $max) * 100 : 0;
    @endphp

    <div class="row g-4">

        <!-- LEFT -->
        <div class="col-lg-8">

            <!-- DESCRIPTION -->
            <div class="glass-card mb-4">

                <h2 class="section-title">
                    About This Event
                </h2>

                <p class="section-text">
                    {{ $event->description }}
                </p>

                <!-- STATS -->
                <div class="stats-grid">

                    <div class="stat-box">
                        <div class="stat-number">{{ $max }}</div>
                        <div class="stat-label">Maximum Seats</div>
                    </div>

                    <div class="stat-box">
                        <div class="stat-number">{{ $current }}</div>
                        <div class="stat-label">Tickets Booked</div>
                    </div>

                    <div class="stat-box">
                        <div class="stat-number">{{ $remaining }}</div>
                        <div class="stat-label">Seats Remaining</div>
                    </div>

                </div>

                <!-- PROGRESS -->
                <div class="progress-wrapper">

                    <div class="d-flex justify-content-between mb-2">
                        <small style="color:#94a3b8;">Booking Progress</small>
                        <small style="color:#60a5fa;">
                            {{ number_format($percentage) }}%
                        </small>
                    </div>

                    <div class="progress">
                        <div class="progress-bar"
                             style="width: {{ $percentage }}%">
                        </div>
                    </div>

                </div>

            </div>

            <!-- ATTENDEES -->
            @auth
            @if(auth()->user()->role === 'admin')

            <div class="glass-card">

                <h2 class="section-title">
                    Event Attendees
                </h2>

                <div class="attendees-grid">

                    @foreach($event->bookings as $a)

                        <div class="attendee">
                            👤 {{ $a->name }}
                        </div>

                    @endforeach

                </div>

            </div>

            @endif
            @endauth

        </div>

        <!-- RIGHT -->
        <div class="col-lg-4">

            <div class="glass-card booking-card">

                <h2 class="booking-title">
                    Book Your Ticket
                </h2>

                <p class="booking-subtitle">
                    Secure your seat before tickets sell out.
                </p>

                @if($remaining > 0)

                <form method="POST"
                      action="{{ route('bookings.store', $event->id) }}">

                    @csrf

                    <input type="hidden"
                           name="event_id"
                           value="{{ $event->id }}">

                    <input type="text"
                           name="name"
                           placeholder="Your Full Name"
                           class="form-control"
                           required>

                    <input type="email"
                           name="email"
                           placeholder="Email Address"
                           class="form-control"
                           required>

                    <!-- TICKET TYPES -->
                    <div class="ticket-options">

                        <label class="ticket">
                            <input type="radio"
                                   name="ticket_type"
                                   value="regular"
                                   required>

                            <div class="ticket-name">
                                Regular
                            </div>

                            <div class="ticket-price">
                                5,000 RWF
                            </div>

                        </label>

                        <label class="ticket">
                            <input type="radio"
                                   name="ticket_type"
                                   value="vip">

                            <div class="ticket-name">
                                VIP
                            </div>

                            <div class="ticket-price">
                                15,000 RWF
                            </div>

                        </label>

                    </div>

                    <button class="btn-book">
                        Book Now
                    </button>

                </form>

                @else

                    <div class="alert alert-warning">
                        This event is fully booked 🚫
                    </div>

                @endif

            </div>

        </div>

    </div>

    <!-- BACK -->
    <a href="{{ route('events.index') }}"
       class="btn-back">
        ← Back to Events
    </a>

</div>

<script>

document.querySelectorAll('.ticket').forEach(card => {

    card.addEventListener('click', () => {

        document.querySelectorAll('.ticket')
            .forEach(c => c.classList.remove('active'));

        card.classList.add('active');

    });

});

</script>

</body>
</html>