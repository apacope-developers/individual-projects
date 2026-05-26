<!DOCTYPE html>
<html>
<head>
    <title>Edit Event</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #0f172a, #020617);
            min-height: 100vh;
            display: flex;
            align-items: center;
            font-family: 'Segoe UI', sans-serif;
            color: white;
        }

        .form-card {
            background: #0f172a;
            border-radius: 20px;
            padding: 35px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.6);
            border: 1px solid rgba(255,255,255,0.05);
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        h2 {
            font-weight: 700;
            margin-bottom: 25px;
        }

        label {
            font-size: 13px;
            margin-bottom: 5px;
            opacity: 0.8;
        }

        .form-control {
            background: #020617;
            border: 1px solid rgba(255,255,255,0.08);
            color: white;
            border-radius: 10px;
            padding: 12px;
        }

        .form-control:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37,99,235,0.2);
            background: #020617;
            color: white;
        }

        textarea {
            resize: none;
        }

        .btn-update {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            border: none;
            border-radius: 10px;
            padding: 12px;
            font-weight: 600;
            width: 100%;
            transition: 0.3s;
            color: white;
        }

        .btn-update:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(37,99,235,0.4);
        }

        .btn-back {
            background: #334155;
            border: none;
            border-radius: 10px;
            padding: 10px 20px;
            color: white;
        }

        .btn-back:hover {
            background: #1e293b;
        }

        .alert {
            border-radius: 12px;
        }
    </style>
</head>

<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-7">

            <div class="form-card">

                <h2 class="text-center">✏ Edit Event</h2>

                <!-- ERRORS -->
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- FORM -->
                <form method="POST" action="{{ route('events.update', $event->id) }}">
                    @csrf
                    @method('PUT')

                    <!-- TITLE -->
                    <div class="mb-3">
                        <label>Event Title</label>
                        <input type="text" name="title" class="form-control"
                               value="{{ $event->title }}" required>
                    </div>

                    <!-- DESCRIPTION -->
                    <div class="mb-3">
                        <label>Description</label>
                        <textarea name="description" class="form-control" rows="3" required>{{ $event->description }}</textarea>
                    </div>

                    <!-- DATE -->
                    <div class="mb-3">
                        <label>Date</label>
                        <input type="date" name="date" class="form-control"
                               value="{{ $event->date }}" required>
                    </div>

                    <!-- LOCATION -->
                    <div class="mb-3">
                        <label>Location</label>
                        <input type="text" name="location" class="form-control"
                               value="{{ $event->location }}" required>
                    </div>

                    <!-- MAX -->
                    <div class="mb-3">
                        <label>Max Attendees</label>
                        <input type="number" name="max_attendees" class="form-control"
                               value="{{ $event->max_attendees }}" required>
                    </div>

                    <!-- BUTTONS -->
                    <div class="d-flex gap-2 mt-4">
                        <a href="{{ route('events.index') }}" class="btn btn-back w-50">
                            ← Back
                        </a>

                        <button class="btn-update w-50">
                            Update Event
                        </button>
                    </div>

                </form>

            </div>

        </div>
    </div>
</div>

</body>
</html>