<!DOCTYPE html>
<html>
<head>
    <title>Events</title>

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

    color:white;

    font-family:'Poppins',sans-serif;

    overflow-x:hidden;
}

/* LAYOUT */

.app-layout{
    display:flex;
}

/* SIDEBAR */

.sidebar{

    width:260px;

    min-height:100vh;

    background:rgba(2,6,23,0.95);

    backdrop-filter:blur(20px);

    border-right:1px solid rgba(255,255,255,0.05);

    padding:30px 20px;

    position:sticky;

    top:0;
}

/* LOGO */

.logo{

    font-size:28px;

    font-weight:700;

    margin-bottom:40px;

    letter-spacing:-1px;

    color:white;
}

.logo span{
    color:#3b82f6;
}

/* MENU */

.menu-item{

    display:flex;

    align-items:center;

    gap:12px;

    text-decoration:none;

    color:#cbd5e1;

    padding:15px 18px;

    border-radius:16px;

    margin-bottom:14px;

    transition:0.3s ease;

    font-size:15px;

    font-weight:500;
}

.menu-item:hover{

    background:rgba(59,130,246,0.15);

    color:white;

    transform:translateX(4px);
}

.menu-item.active{

    background:linear-gradient(
        135deg,
        #2563eb,
        #3b82f6
    );

    color:white;

    box-shadow:
        0 10px 30px rgba(37,99,235,0.35);
}

/* USER */

.user-box{

    margin-top:40px;

    padding:20px;

    border-radius:18px;

    background:rgba(255,255,255,0.03);

    border:1px solid rgba(255,255,255,0.04);
}

.user-label{

    color:#94a3b8;

    font-size:13px;

    margin-bottom:6px;
}

.user-name{

    font-weight:600;

    font-size:15px;
}

/* LOGOUT */

.logout-btn{

    border:none;

    width:100%;

    text-align:left;

    margin-top:16px;

    background:none;
}

.logout-btn:hover{

    background:rgba(239,68,68,0.12);
}

/* MAIN */

.main-content{

    flex:1;

    padding:40px;
}

/* HEADER */

.header{

    display:flex;

    justify-content:space-between;

    align-items:center;

    margin-bottom:50px;
}

/* TITLE */

.page-title{

    font-size:48px;

    font-weight:700;

    letter-spacing:-2px;
}

/* BUTTONS */

.btn-modern{

    border:none;

    padding:12px 22px;

    border-radius:14px;

    font-weight:600;

    color:white;

    text-decoration:none;

    transition:0.3s ease;
}

.btn-dashboard{

    background:linear-gradient(
        135deg,
        #10b981,
        #059669
    );
}

.btn-create{

    background:linear-gradient(
        135deg,
        #2563eb,
        #3b82f6
    );
}

.btn-modern:hover{

    transform:translateY(-3px);

    color:white;
}

/* EVENT CARD */

.event-card{

    background:rgba(15,23,42,0.75);

    border:1px solid rgba(255,255,255,0.05);

    border-radius:28px;

    overflow:hidden;

    transition:0.4s ease;

    backdrop-filter:blur(16px);

    position:relative;
}

.event-card:hover{

    transform:translateY(-10px);

    border-color:rgba(59,130,246,0.3);

    box-shadow:
        0 25px 60px rgba(0,0,0,0.45),
        0 0 20px rgba(59,130,246,0.15);
}

/* IMAGE */

.event-image{

    position:relative;

    overflow:hidden;
}

.event-image img{

    width:100%;

    height:260px;

    object-fit:cover;

    transition:0.5s ease;
}

.event-card:hover .event-image img{

    transform:scale(1.06);
}

/* OVERLAY */

.overlay{

    position:absolute;

    bottom:0;

    left:0;

    width:100%;

    padding:28px 22px;

    background:linear-gradient(
        transparent,
        rgba(0,0,0,0.95)
    );
}

.overlay strong{

    font-size:28px;

    font-weight:700;

    letter-spacing:-1px;
}

/* CONTENT */

.event-content{

    padding:26px;
}

/* META */

.event-meta{

    color:#94a3b8;

    font-size:15px;

    margin-bottom:10px;
}

/* FOOTER */

.event-footer{

    display:flex;

    justify-content:space-between;

    align-items:center;

    margin-top:25px;
}

/* STATUS */

.badge-available{

    background:rgba(34,197,94,0.12);

    color:#22c55e;

    padding:8px 18px;

    border-radius:40px;

    font-size:13px;

    font-weight:600;
}

/* VIEW BUTTON */

.btn-view{

    background:linear-gradient(
        135deg,
        #2563eb,
        #3b82f6
    );

    color:white;

    text-decoration:none;

    padding:11px 20px;

    border-radius:14px;

    font-weight:600;

    transition:0.3s ease;
}

.btn-view:hover{

    color:white;

    transform:translateY(-2px);

    box-shadow:
        0 10px 30px rgba(59,130,246,0.35);
}

/* ADMIN BUTTONS */

.btn-warning{

    background:linear-gradient(
        135deg,
        #f59e0b,
        #d97706
    );

    border:none;

    border-radius:10px;
}

.btn-danger{

    background:linear-gradient(
        135deg,
        #ef4444,
        #dc2626
    );

    border:none;

    border-radius:10px;
}

/* ALERT */

.alert-success{

    background:rgba(34,197,94,0.12);

    border:none;

    color:#22c55e;

    border-radius:16px;

    padding:18px;
}

/* EMPTY */

.empty-box{

    text-align:center;

    margin-top:100px;

    opacity:0.7;
}

/* RESPONSIVE */

@media(max-width:991px){

    .sidebar{
        display:none;
    }

    .main-content{
        padding:20px;
    }

    .page-title{
        font-size:34px;
    }

    .header{
        flex-direction:column;
        align-items:flex-start;
        gap:20px;
    }
}

    </style>
</head>

<body>

<div class="app-layout">

    <!-- SIDEBAR -->
    <div class="sidebar">

        <div class="logo">
            Event<span>Booking</span>
        </div>

        <a href="{{ route('events.index') }}"
           class="menu-item {{ request()->routeIs('events.index') ? 'active' : '' }}">

            Explore Events
        </a>

        <a href="{{ route('bookings.my') }}"
           class="menu-item {{ request()->routeIs('bookings.my') ? 'active' : '' }}">

            My Bookings
        </a>

        <a href="#" class="menu-item">
            Payments
        </a>

        <a href="{{ route('profile.edit') }}"
           class="menu-item {{ request()->routeIs('profile.*') ? 'active' : '' }}">

            Profile
        </a>

        @auth

        <div class="user-box">

            <div class="user-label">
                Logged in as
            </div>

            <div class="user-name">
                {{ auth()->user()->name }}
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <button class="menu-item logout-btn">
                    Logout
                </button>
            </form>

        </div>

        @endauth

    </div>

    <!-- MAIN -->
    <div class="main-content">

        <div class="header">

            <div class="page-title">
                Explore Events
            </div>

            <div class="d-flex gap-3">

                @auth
                    @if(auth()->user()->role === 'admin')

                        <a href="{{ route('admin.bookings') }}"
                           class="btn-modern btn-dashboard">

                            Dashboard
                        </a>

                        <a href="{{ route('events.create') }}"
                           class="btn-modern btn-create">

                            + Create Event
                        </a>

                    @endif
                @endauth

            </div>

        </div>

        <!-- SUCCESS -->

        @if(session('success'))

            <div class="alert alert-success mb-4">
                {{ session('success') }}
            </div>

        @endif

        <!-- EVENTS -->

        @if(isset($events) && count($events) > 0)

        <div class="row g-4">

            @foreach($events as $event)

            @php
                $image = $event->image
                    ? asset('storage/' . $event->image)
                    : asset('images/default.png');
            @endphp

            <div class="col-lg-4 col-md-6">

                <div class="event-card">

                    <div class="event-image">

                        <img src="{{ $image }}">

                        <div class="overlay">

                            <strong>
                                {{ $event->title }}
                            </strong>

                        </div>

                    </div>

                    <div class="event-content">

                        <div class="event-meta">
                            📅 {{ $event->date }}
                        </div>

                        <div class="event-meta">
                            📍 {{ $event->location }}
                        </div>

                        <div class="event-footer">

                            <span class="badge-available">
                                Available
                            </span>

                            <div class="d-flex gap-2">

                                <a href="{{ route('events.show', $event->id) }}"
                                   class="btn-view">

                                    View →
                                </a>

                                @auth
                                    @if(auth()->user()->role === 'admin')

                                        <a href="{{ route('events.edit', $event->id) }}"
                                           class="btn btn-warning btn-sm">

                                            ✏
                                        </a>

                                        <form action="{{ route('events.destroy', $event->id) }}"
                                              method="POST"
                                              onsubmit="return confirm('Delete this event?')">

                                            @csrf
                                            @method('DELETE')

                                            <button class="btn btn-danger btn-sm">
                                                🗑
                                            </button>

                                        </form>

                                    @endif
                                @endauth

                            </div>

                        </div>

                    </div>

                </div>

            </div>

            @endforeach

        </div>

        @else

        <div class="empty-box">

            <h3>No events available</h3>

        </div>

        @endif

    </div>

</div>

</body>
</html>