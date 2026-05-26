<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Settings</title>

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
                linear-gradient(rgba(2,6,23,0.88), rgba(2,6,23,0.95)),
                url('/images/bg.png');

            background-size:cover;
            background-position:center;
            background-attachment:fixed;
            min-height:100vh;
            color:white;
        }

        /* LAYOUT */
        .profile-wrapper{
            min-height:100vh;
            display:flex;
            align-items:center;
            justify-content:center;
            padding:40px 20px;
        }

        .profile-container{
            width:100%;
            max-width:950px;
        }

        /* HEADER */
        .profile-header{
            margin-bottom:35px;
        }

        .profile-title{
            font-size:48px;
            font-weight:800;
            margin-bottom:10px;
            background:linear-gradient(135deg,#ffffff,#60a5fa);
            -webkit-background-clip:text;
            -webkit-text-fill-color:transparent;
        }

        .profile-subtitle{
            color:#94a3b8;
            font-size:17px;
        }

        /* CARDS */
        .profile-card{
            background:rgba(15,23,42,0.85);
            border:1px solid rgba(255,255,255,0.06);
            backdrop-filter:blur(10px);
            border-radius:28px;
            padding:35px;
            margin-bottom:30px;
            box-shadow:0 20px 60px rgba(0,0,0,0.45);
            transition:0.3s ease;
        }

        .profile-card:hover{
            transform:translateY(-4px);
            border-color:rgba(59,130,246,0.25);
        }

        .section-title{
            font-size:28px;
            font-weight:700;
            margin-bottom:8px;
        }

        .section-text{
            color:#94a3b8;
            margin-bottom:28px;
        }

        /* FORM */
        .form-label{
            margin-bottom:10px;
            font-weight:600;
            color:#e2e8f0;
        }

        .form-control{
            height:60px;
            background:rgba(2,6,23,0.8);
            border:1px solid rgba(255,255,255,0.08);
            border-radius:16px;
            color:white;
            padding:15px 20px;
            font-size:16px;
            margin-bottom:20px;
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

        /* BUTTONS */
        .btn-save{
            background:linear-gradient(135deg,#2563eb,#3b82f6);
            border:none;
            height:56px;
            padding:0 35px;
            border-radius:16px;
            color:white;
            font-size:16px;
            font-weight:700;
            transition:0.3s;
        }

        .btn-save:hover{
            transform:translateY(-2px);
            box-shadow:0 15px 30px rgba(37,99,235,0.4);
        }

        .btn-delete{
            background:linear-gradient(135deg,#ef4444,#dc2626);
            border:none;
            height:56px;
            padding:0 35px;
            border-radius:16px;
            color:white;
            font-size:16px;
            font-weight:700;
            transition:0.3s;
        }

        .btn-delete:hover{
            transform:translateY(-2px);
            box-shadow:0 15px 30px rgba(239,68,68,0.35);
        }

        .btn-back{
            display:inline-flex;
            align-items:center;
            gap:10px;
            background:rgba(255,255,255,0.08);
            color:white;
            padding:14px 24px;
            border-radius:14px;
            text-decoration:none;
            font-weight:600;
            transition:0.3s;
        }

        .btn-back:hover{
            background:rgba(255,255,255,0.14);
            color:white;
            transform:translateX(-3px);
        }

        /* ALERTS */
        .alert{
            border:none;
            border-radius:16px;
            padding:18px;
            margin-bottom:25px;
        }

        .alert-success{
            background:rgba(34,197,94,0.12);
            color:#4ade80;
            border:1px solid rgba(34,197,94,0.2);
        }

        .danger-box{
            background:rgba(127,29,29,0.15);
            border:1px solid rgba(239,68,68,0.15);
        }

        .danger-title{
            color:#f87171;
            font-weight:700;
            font-size:28px;
            margin-bottom:10px;
        }

        .danger-text{
            color:#cbd5e1;
            margin-bottom:25px;
            line-height:1.7;
        }

        /* RESPONSIVE */
        @media(max-width:768px){

            .profile-title{
                font-size:36px;
            }

            .profile-card{
                padding:25px;
                border-radius:22px;
            }

            .section-title{
                font-size:24px;
            }

            .form-control{
                height:55px;
            }
        }
    </style>
</head>

<body>

<div class="profile-wrapper">

    <div class="profile-container">

        <!-- HEADER -->
        <div class="profile-header">
            <h1 class="profile-title">Profile Settings</h1>
            <p class="profile-subtitle">
                Manage your account information and personalize your experience.
            </p>
        </div>

        <!-- SUCCESS MESSAGE -->
        @if(session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        <!-- UPDATE PROFILE -->
        <div class="profile-card">

            <h3 class="section-title">Update Profile</h3>

            <p class="section-text">
                Keep your account information accurate and up to date.
            </p>

            <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                @method('PATCH')

                <label class="form-label">Full Name</label>

                <input
                    type="text"
                    name="name"
                    value="{{ auth()->user()->name }}"
                    class="form-control"
                    placeholder="Enter your full name"
                    required
                >

                <label class="form-label">Email Address</label>

                <input
                    type="email"
                    name="email"
                    value="{{ auth()->user()->email }}"
                    class="form-control"
                    placeholder="Enter your email"
                    required
                >

                <button class="btn-save">
                    Save Changes
                </button>

            </form>

        </div>

        <!-- DELETE ACCOUNT -->
        <div class="profile-card danger-box">

            <h3 class="danger-title">Delete Account</h3>

            <p class="danger-text">
                Once your account is deleted, all your bookings,
                profile information, and activity will be permanently removed.
                This action cannot be undone.
            </p>

            <form method="POST" action="{{ route('profile.destroy') }}">
                @csrf
                @method('DELETE')

                <button
                    class="btn-delete"
                    onclick="return confirm('Are you sure you want to permanently delete your account?')"
                >
                    Delete Account
                </button>

            </form>

        </div>

        <!-- BACK BUTTON -->
        <a href="{{ route('events.index') }}" class="btn-back">
            ← Back to Events
        </a>

    </div>

</div>

</body>
</html>