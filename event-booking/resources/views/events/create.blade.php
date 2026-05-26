<!DOCTYPE html>
<html>
<head>
    <title>Create Event</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }

        .form-card {
            background: white;
            border-radius: 20px;
            padding: 35px;
            box-shadow: 0 15px 50px rgba(0,0,0,0.2);
            animation: fadeIn 0.5s ease-in-out;
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
            color: #2a5298;
        }

        .form-control {
            border-radius: 10px;
            padding: 12px;
        }

        .form-control:focus {
            box-shadow: 0 0 0 3px rgba(42,82,152,0.2);
            border-color: #2a5298;
        }

        .btn-primary {
            background: linear-gradient(135deg, #2a5298, #1e3c72);
            border: none;
            border-radius: 10px;
            padding: 12px 20px;
            font-weight: 600;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(42,82,152,0.4);
        }

        .btn-secondary {
            border-radius: 10px;
            padding: 12px 20px;
        }

        label {
            font-size: 14px;
            color: #555;
        }

    </style>
</head>

<body>

<div class="container">

    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="form-card">

                <h2 class="text-center mb-4">Create New Event</h2>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

              <form method="POST" action="{{ route('events.store') }}" enctype="multipart/form-data">
    @csrf

    <!-- IMAGE -->
    <div class="mb-3">
        <label class="form-label fw-bold">Event Image</label>
        <input type="file" name="image" class="form-control">
    </div>

    <!-- Title -->
    <div class="mb-3">
        <label class="form-label fw-bold">Event Title</label>
        <input type="text" name="title" class="form-control" required>
    </div>

    <!-- Description -->
    <div class="mb-3">
        <label class="form-label fw-bold">Description</label>
        <textarea name="description" class="form-control" rows="3" required></textarea>
    </div>

    <!-- Date -->
    <div class="mb-3">
        <label class="form-label fw-bold">Event Date</label>
        <input type="date" name="date" class="form-control" required>
    </div>

    <!-- Location -->
    <div class="mb-3">
        <label class="form-label fw-bold">Location</label>
        <input type="text" name="location" class="form-control" required>
    </div>

    <!-- Max Attendees -->
    <div class="mb-3">
        <label class="form-label fw-bold">Max Attendees</label>
        <input type="number" name="max_attendees" class="form-control" required>
    </div>

    <!-- Buttons -->
    <div class="d-flex justify-content-between mt-4">
        <a href="{{ route('events.index') }}" class="btn btn-secondary">
            ← Back
        </a>

        <button type="submit" class="btn btn-primary px-4">
            Create Event
        </button>
    </div>

</form>
            </div>

        </div>
    </div>

</div>

</body>
</html>