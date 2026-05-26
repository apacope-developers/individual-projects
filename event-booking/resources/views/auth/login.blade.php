<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Event Booking | Login</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>

        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
        }

        body{
            font-family:'Poppins', sans-serif;
            height:100vh;
            overflow:hidden;

            /* YOUR BACKGROUND IMAGE */
            background:
                linear-gradient(rgba(0,0,0,0.78), rgba(0,0,0,0.82)),
                url("{{ asset('images/bg.png') }}");

            background-size:cover;
            background-position:center;
            background-repeat:no-repeat;

            display:flex;
            justify-content:center;
            align-items:center;
        }

        .login-card{

            width:100%;
            max-width:470px;

            padding:55px 45px;

            border-radius:28px;

            background:rgba(10,10,10,0.58);

            backdrop-filter:blur(10px);

            border:1px solid rgba(255,255,255,0.08);

            box-shadow:
                0 10px 40px rgba(0,0,0,0.6),
                inset 0 0 0 1px rgba(255,255,255,0.02);

            animation:fadeIn 0.7s ease;
        }

        @keyframes fadeIn{
            from{
                opacity:0;
                transform:translateY(25px);
            }
            to{
                opacity:1;
                transform:translateY(0);
            }
        }

        .title{
            color:white;
            font-size:56px;
            font-weight:700;
            text-align:center;
            margin-bottom:8px;
            letter-spacing:-1px;
        }

        .subtitle{
            text-align:center;
            color:#b3b3b3;
            margin-bottom:40px;
            font-size:18px;
            font-weight:300;
        }

        .form-label{
            color:white;
            font-size:16px;
            margin-bottom:10px;
            font-weight:500;
        }

        .form-control{

            height:65px;

            background:rgba(255,255,255,0.04);

            border:1px solid rgba(255,255,255,0.08);

            border-radius:18px;

            color:white;

            padding-left:22px;

            font-size:17px;

            transition:0.3s ease;
        }

        .form-control::placeholder{
            color:#7f7f7f;
        }

        .form-control:focus{

            background:rgba(255,255,255,0.05);

            border:1px solid #ffae00;

            box-shadow:none;

            color:white;
        }

        .btn-login{

            width:100%;

            height:62px;

            border:none;

            border-radius:18px;

            margin-top:10px;

            background:linear-gradient(to right, #ff9800, #ffb833);

            color:white;

            font-size:22px;

            font-weight:600;

            transition:0.3s ease;
        }

        .btn-login:hover{

            transform:translateY(-2px);

            background:linear-gradient(to right, #ff8c00, #ffb300);
        }

        .register-text{

            text-align:center;

            margin-top:30px;

            color:#d4d4d4;

            font-size:17px;
        }

        .register-text a{

            color:#ffae00;

            text-decoration:none;

            font-weight:600;
        }

        .register-text a:hover{
            color:#ffc547;
        }

        .error-box{

            background:rgba(255,0,0,0.08);

            border:1px solid rgba(255,0,0,0.2);

            color:#ffb3b3;

            padding:15px;

            border-radius:14px;

            margin-bottom:25px;

            font-size:14px;
        }

        @media(max-width:576px){

            body{
                padding:20px;
            }

            .login-card{

                padding:40px 28px;
            }

            .title{
                font-size:42px;
            }

            .subtitle{
                font-size:15px;
            }
        }

    </style>

</head>

<body>

    <div class="login-card">

        <h1 class="title">
            Event Booking
        </h1>

        <p class="subtitle">
            Login to manage your bookings
        </p>

        @if ($errors->any())

            <div class="error-box">

                @foreach ($errors->all() as $error)

                    <div>{{ $error }}</div>

                @endforeach

            </div>

        @endif

        <form method="POST" action="{{ route('login') }}">

            @csrf

            <div class="mb-4">

                <label class="form-label">
                    Email Address
                </label>

                <input
                    type="email"
                    name="email"
                    class="form-control"
                    placeholder="Enter your email"
                    required
                >

            </div>

            <div class="mb-4">

                <label class="form-label">
                    Password
                </label>

                <input
                    type="password"
                    name="password"
                    class="form-control"
                    placeholder="Enter your password"
                    required
                >

            </div>

            <button type="submit" class="btn-login">

                Login

            </button>

        </form>

        <div class="register-text">

            Don't have an account?

            <a href="{{ route('register') }}">

                Register

            </a>

        </div>

    </div>

</body>
</html>