<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Apacope Attendance') }}</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)),
                        url('https://images.unsplash.com/photo-1588072432836-e10032774350');
            background-size: cover;
            background-position: center;
            min-height: 100vh;
            color: white;
        }
        .card-custom {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 40px;
        }
        .btn-custom {
            padding: 10px 25px;
            font-weight: bold;
        }
    </style>
</head>

<body class="d-flex align-items-center justify-content-center">

<div class="container text-center">
    <div class="card-custom shadow-lg">

        <!-- School Name -->
        <h1 class="mb-3 fw-bold">Groupe Scolaire Apacope</h1>

        <!-- Title -->
        <h2 class="mb-3">Student Attendance System</h2>

        <!-- Description -->
        <p class="mb-4">
            Manage and track student attendance efficiently in real-time.
            Designed for teachers, administrators, and students.
        </p>

        <!-- Buttons -->
        <div class="d-flex justify-content-center gap-3 flex-wrap">
            @if (Route::has('attendance.index'))
                <a href="{{ route('attendance.index') }}" class="btn btn-primary btn-custom">
                     View Attendance
                </a>
            @endif

            @if (Route::has('login'))
                <a href="{{ route('login') }}" class="btn btn-outline-light btn-custom">
                     Login
                </a>
            @endif
        </div>

    </div>

    <!-- Footer -->
    <div class="mt-4">
        <small>© {{ date('Y') }} Groupe Scolaire Apacope. All rights reserved.</small>
    </div>
</div>

</body>
</html>