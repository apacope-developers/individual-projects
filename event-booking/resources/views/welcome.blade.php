<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>EventBooking</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>

        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
        }

        html{
            scroll-behavior:smooth;
        }

        body{
            font-family:'Poppins', sans-serif;
            background:#0a0a0a;
            color:white;
            overflow-x:hidden;
        }

        a{
            text-decoration:none;
        }

        /* NAVBAR */

        .navbar{

            background:rgba(0,0,0,0.55);

            backdrop-filter:blur(10px);

            padding:18px 60px;

            border-bottom:1px solid rgba(255,255,255,0.06);
        }

        .navbar-brand{

            color:white !important;

            font-size:28px;

            font-weight:700;

            letter-spacing:-1px;
        }

        .btn-login{

            border:1px solid rgba(255,255,255,0.2);

            color:white;

            padding:10px 24px;

            border-radius:12px;

            transition:0.3s ease;
        }

        .btn-login:hover{

            background:white;

            color:black;
        }

        .btn-register{

            background:linear-gradient(135deg,#ff8a00,#ffb347);

            color:white;

            padding:10px 26px;

            border-radius:12px;

            font-weight:600;

            border:none;

            transition:0.3s ease;
        }

        .btn-register:hover{

            transform:translateY(-2px);

            color:white;
        }

        /* HERO */

        .hero{

            height:100vh;

            background:
                linear-gradient(rgba(0,0,0,0.70), rgba(0,0,0,0.82)),
                url('/images/bg.png');

            background-size:cover;

            background-position:center;

            background-repeat:no-repeat;

            display:flex;

            justify-content:center;

            align-items:center;

            text-align:center;

            animation:zoomBg 18s ease-in-out infinite alternate;
        }

        @keyframes zoomBg{

            from{
                background-size:100%;
            }

            to{
                background-size:110%;
            }
        }

        .hero h1{

            font-size:76px;

            font-weight:800;

            line-height:1.1;

            margin-bottom:20px;

            letter-spacing:-2px;
        }

        .hero p{

            color:#d1d1d1;

            font-size:22px;

            margin-bottom:35px;
        }

        .btn-main{

            background:linear-gradient(135deg,#ff8a00,#ffb347);

            border:none;

            padding:16px 38px;

            border-radius:50px;

            color:white;

            font-size:18px;

            font-weight:600;

            transition:0.3s ease;
        }

        .btn-main:hover{

            transform:translateY(-3px);

            color:white;

            box-shadow:0 10px 30px rgba(255,138,0,0.3);
        }

        /* GENERAL */

        .section{

            padding:110px 0;
        }

        .section-badge{

            display:inline-block;

            padding:8px 18px;

            border-radius:30px;

            background:rgba(255,179,71,0.08);

            border:1px solid rgba(255,179,71,0.15);

            color:#ffb347;

            font-size:13px;

            font-weight:600;

            letter-spacing:1px;
        }

        /* ABOUT */

        .about-section{

            background:#0d0d0d;
        }

        .about-title{

            font-size:52px;

            font-weight:700;

            line-height:1.2;
        }

        .about-text{

            color:#b5b5b5;

            line-height:1.9;

            font-size:16px;
        }

        .stat-box{

            background:#151515;

            padding:22px 28px;

            border-radius:22px;

            border:1px solid rgba(255,255,255,0.05);

            min-width:150px;
        }

        .stat-box h3{

            color:#ffb347;

            font-size:34px;

            font-weight:700;

            margin-bottom:5px;
        }

        .stat-box p{

            margin:0;

            color:#b8b8b8;

            font-size:14px;
        }

        .about-image{

            width:100%;

            border-radius:28px;

            object-fit:cover;

            box-shadow:0 20px 60px rgba(0,0,0,0.5);
        }

        /* FEATURES */

        .features-section{

            background:#111;
        }

        .feature-title{

            font-size:48px;

            font-weight:700;
        }

        .feature-card{

            background:#181818;

            padding:45px 35px;

            border-radius:24px;

            transition:0.4s ease;

            height:100%;

            border:1px solid rgba(255,255,255,0.05);
        }

        .feature-card:hover{

            transform:translateY(-8px);

            border-color:rgba(255,179,71,0.25);

            box-shadow:0 15px 40px rgba(0,0,0,0.4);
        }

        .feature-number{

            width:65px;

            height:65px;

            margin:0 auto 25px;

            border-radius:50%;

            display:flex;

            align-items:center;

            justify-content:center;

            background:linear-gradient(135deg,#ff8a00,#ffb347);

            font-size:20px;

            font-weight:700;
        }

        .feature-card h4{

            margin-bottom:18px;

            font-weight:600;
        }

        .feature-card p{

            color:#b5b5b5;

            line-height:1.8;
        }

        /* EVENTS */

        .events-section{

            background:#0d0d0d;
        }

        .event-card{

            background:#151515;

            border-radius:24px;

            overflow:hidden;

            transition:0.4s ease;

            border:1px solid rgba(255,255,255,0.05);
        }

        .event-card:hover{

            transform:translateY(-8px);

            box-shadow:0 15px 40px rgba(0,0,0,0.45);
        }

        .event-card img{

            width:100%;

            height:240px;

            object-fit:cover;
        }

        .event-content{

            padding:28px;
        }

        .event-content h5{

            font-size:24px;

            font-weight:600;

            margin-bottom:10px;
        }

        .event-content p{

            color:#b8b8b8;

            margin-bottom:0;
        }

        /* CONTACT */

        .contact-section{

            background:#111;
            text-align:center;
        }

        .contact-title{

            font-size:46px;

            font-weight:700;

            margin-bottom:25px;
        }

        .contact-section p{

            color:#b8b8b8;

            font-size:18px;
        }

        /* FOOTER */

        .footer{

            background:black;

            padding:35px 0;

            text-align:center;

            border-top:1px solid rgba(255,255,255,0.05);
        }

        .footer p{

            color:#8f8f8f;

            margin:0;
        }

        /* RESPONSIVE */

        @media(max-width:992px){

            .hero h1{

                font-size:56px;
            }

            .about-title{

                font-size:40px;
            }
        }

        @media(max-width:768px){

            .navbar{

                padding:18px 20px;
            }

            .hero h1{

                font-size:42px;
            }

            .hero p{

                font-size:18px;
            }

            .feature-title{

                font-size:36px;
            }

            .contact-title{

                font-size:36px;
            }
        }

    </style>

</head>

<body>

<!-- NAVBAR -->

<nav class="navbar navbar-expand-lg fixed-top">

    <div class="container-fluid">

        <a class="navbar-brand" href="#">
            EventBooking
        </a>

        <div class="d-flex gap-3">

            <a href="/login" class="btn btn-login">
                Login
            </a>

            <a href="/register" class="btn btn-register">
                Register
            </a>

        </div>

    </div>

</nav>

<!-- HERO -->

<section class="hero">

    <div class="container">

        <h1>
            Discover & Book <br>
            Amazing Events
        </h1>

        <p>
            Concerts • Conferences • Sports • Parties
        </p>

        <a href="/events" class="btn btn-main">

            Explore Events

        </a>

    </div>

</section>

<!-- ABOUT -->

<section class="section about-section">

    <div class="container">

        <div class="row align-items-center g-5">

            <div class="col-lg-6">

                <span class="section-badge">
                    ABOUT EVENTBOOKING
                </span>

                <h2 class="about-title mt-4">

                    Experience Events
                    Like Never Before

                </h2>

                <p class="about-text mt-4">

                    EventBooking is a modern event discovery and ticket booking
                    platform built to connect people with unforgettable experiences.
                    From live concerts and sports matches to conferences and social
                    gatherings, we make event discovery seamless and enjoyable.

                </p>

                <p class="about-text">

                    Our platform simplifies the entire booking experience while helping
                    organizers showcase premium events to the right audience.
                    Fast, secure, and beautifully designed — everything you need
                    in one place.

                </p>

                <div class="d-flex flex-wrap gap-3 mt-5">

                    <div class="stat-box">

                        <h3>500+</h3>

                        <p>Events Hosted</p>

                    </div>

                    <div class="stat-box">

                        <h3>10K+</h3>

                        <p>Bookings Made</p>

                    </div>

                    <div class="stat-box">

                        <h3>98%</h3>

                        <p>Satisfaction</p>

                    </div>

                </div>

            </div>

            <div class="col-lg-6">

              <img
    src="{{ asset('images/about.png') }}"
    class="about-image"
>
            </div>

        </div>

    </div>

</section>

<!-- FEATURES -->

<section class="section features-section">

    <div class="container text-center">

        <span class="section-badge">
            WHY CHOOSE US
        </span>

        <h2 class="feature-title mt-4 mb-5">

            Everything You Need
            For Event Booking

        </h2>

        <div class="row g-4">

            <div class="col-md-4">

                <div class="feature-card">

                    <div class="feature-number">
                        01
                    </div>

                    <h4>
                        Fast Ticket Booking
                    </h4>

                    <p>
                        Reserve tickets instantly with a simple,
                        smooth, and modern booking experience.
                    </p>

                </div>

            </div>

            <div class="col-md-4">

                <div class="feature-card">

                    <div class="feature-number">
                        02
                    </div>

                    <h4>
                        Secure Platform
                    </h4>

                    <p>
                        Your information and bookings are protected
                        using modern authentication systems.
                    </p>

                </div>

            </div>

            <div class="col-md-4">

                <div class="feature-card">

                    <div class="feature-number">
                        03
                    </div>

                    <h4>
                        Premium Events
                    </h4>

                    <p>
                        Discover curated experiences from concerts
                        to networking and business conferences.
                    </p>

                </div>

            </div>

        </div>

    </div>

</section>

<!-- EVENTS -->

<section class="section events-section">

    <div class="container">

        <div class="text-center mb-5">

            <span class="section-badge">
                POPULAR EVENTS
            </span>

            <h2 class="feature-title mt-4">
                Trending Experiences
            </h2>

        </div>

        <div class="row g-4">

            <div class="col-md-4">

                <div class="event-card">

                    <img src="/images/v.png">

                    <div class="event-content">

                        <h5>
                            Volleyball Match
                        </h5>

                        <p>
                            BK Arena • Kigali
                        </p>

                    </div>

                </div>

            </div>

            <div class="col-md-4">

                <div class="event-card">

                    <img src="/images/y.png">

                    <div class="event-content">

                        <h5>
                            Morning Yoga
                        </h5>

                        <p>
                            Nyandungu Park
                        </p>

                    </div>

                </div>

            </div>

            <div class="col-md-4">

                <div class="event-card">

                    <img src="/images/t.png">

                    <div class="event-content">

                        <h5>
                            Tech Conference
                        </h5>

                        <p>
                            Kigali Convention Centre
                        </p>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>

<!-- CONTACT -->

<section class="section contact-section">

    <div class="container">

        <span class="section-badge">
            CONTACT US
        </span>

        <h2 class="contact-title mt-4">

            Let’s Connect

        </h2>

        <p>
            kirengamaxime0@gmail.com
        </p>

        <p>
            +250 780185406
        </p>

    </div>

</section>

<!-- FOOTER -->

<footer class="footer">

    <p>
        © 2026 EventBooking. All rights reserved.
    </p>

</footer>

</body>

</html>