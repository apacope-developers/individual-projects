<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #0f172a;
            color: white;
        }

        .sidebar {
            width: 220px;
            height: 100vh;
            position: fixed;
            background: #020617;
            padding: 20px;
        }

        .sidebar a {
            display: block;
            color: #94a3b8;
            text-decoration: none;
            margin: 15px 0;
        }

        .sidebar a:hover {
            color: white;
        }

        .content {
            margin-left: 240px;
            padding: 30px;
        }
    </style>
</head>

<body>

<div class="sidebar">
    <h4>⚙ Admin</h4>

    <a href="{{ route('admin.dashboard') }}">📊 Dashboard</a>
    <a href="{{ route('events.index') }}">🎟 Events</a>
</div>

<div class="content">
    @yield('content')
</div>

</body>
</html>