<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add Class - Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 20px;
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 250px;
            background: linear-gradient(180deg, #2c3e50 0%, #34495e 100%);
            color: white;
            padding: 2rem 1rem;
            overflow-y: auto;
            z-index: 1000;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
        }

        .sidebar-content {
            display: flex;
            flex-direction: column;
            gap: 2rem;
        }

        .sidebar-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #fff;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .menu-section {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .menu-header {
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #b8c5d6;
            margin-bottom: 0.5rem;
        }

        .menu-items {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .menu-item {
            color: #ecf0f1;
            text-decoration: none;
            padding: 0.75rem 1rem;
            border-radius: 8px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            font-size: 0.95rem;
        }

        .menu-item:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateX(5px);
        }

        .menu-item.active {
            background: rgba(255, 255, 255, 0.15);
            border-left: 4px solid #667eea;
        }

        .main-content {
            margin-left: 250px;
            padding: 2rem;
            min-height: 100vh;
        }

        .content-header {
            background: rgba(255, 255, 255, 0.95);
            padding: 2rem;
            border-radius: 15px;
            margin-bottom: 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
        }

        .page-title {
            font-size: 2rem;
            font-weight: 700;
            color: #2c3e50;
            margin: 0;
        }

        .page-subtitle {
            color: #6c757d;
            margin: 0;
            font-size: 1rem;
        }

        .form-container {
            background: rgba(255, 255, 255, 0.95);
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
        }

        .form-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 0.5rem;
            display: block;
        }

        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-select {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: white;
        }

        .form-select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-check {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 1rem;
            background: #f8f9fa;
            border-radius: 8px;
            border: 2px solid #e9ecef;
        }

        .form-check-input {
            width: 20px;
            height: 20px;
            cursor: pointer;
        }

        .form-check-label {
            cursor: pointer;
            margin: 0;
            font-weight: 500;
        }

        .btn-container {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
        }

        .btn {
            padding: 0.75rem 2rem;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            font-size: 1rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background: #5a6268;
            transform: translateY(-2px);
        }

        .alert {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            border: none;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
        }

        .alert-danger {
            background: #f8d7da;
            color: #721c24;
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
                padding: 1rem;
            }
            
            .btn-container {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-content">
            <h4 class="sidebar-title">
                <i class="fas fa-tachometer-alt me-2"></i>
                Admin Panel
            </h4>
            
            <div class="menu-section">
                <h6 class="menu-header">Main Menu</h6>
                <div class="menu-items">
                    <a href="{{ route('admin.dashboard') }}" class="menu-item">
                        <i class="fas fa-tachometer-alt me-2"></i>
                        Dashboard
                    </a>
                    <a href="{{ route('admin.teachers') }}" class="menu-item">
                        <i class="fas fa-chalkboard-teacher me-2"></i>
                        Teachers
                    </a>
                    <a href="{{ route('admin.students') }}" class="menu-item">
                        <i class="fas fa-graduation-cap me-2"></i>
                        Students
                    </a>
                    <a href="{{ route('admin.classes.index') }}" class="menu-item active">
                        <i class="fas fa-school me-2"></i>
                        Classes
                    </a>
                    <a href="{{ route('admin.attendance') }}" class="menu-item">
                        <i class="fas fa-calendar-check me-2"></i>
                        All Attendance
                    </a>
                </div>
            </div>

            <div class="menu-section">
                <h6 class="menu-header">Quick Actions</h6>
                <div class="menu-items">
                    <a href="{{ route('admin.teachers.create') }}" class="menu-item">
                        <i class="fas fa-user-plus me-2"></i>
                        Add Teacher
                    </a>
                    <a href="{{ route('admin.students.create') }}" class="menu-item">
                        <i class="fas fa-user-plus me-2"></i>
                        Add Student
                    </a>
                    <a href="{{ route('admin.classes.create') }}" class="menu-item">
                        <i class="fas fa-school me-2"></i>
                        Add Class
                    </a>
                    <a href="{{ route('attendance.index') }}" class="menu-item">
                        <i class="fas fa-plus me-2"></i>
                        Mark Attendance
                    </a>
                </div>
            </div>

            <div class="menu-section">
                <h6 class="menu-header">Reports</h6>
                <div class="menu-items">
                    <a href="{{ route('attendance.reports') }}" class="menu-item">
                        <i class="fas fa-chart-line me-2"></i>
                        View Reports
                    </a>
                </div>
            </div>

            <div class="menu-section">
                <h6 class="menu-header">Account</h6>
                <div class="menu-items">
                    <a href="#" class="menu-item" onclick="confirmLogout()">
                        <i class="fas fa-sign-out-alt me-2"></i>
                        Logout
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Header -->
        <div class="content-header">
            <h2 class="page-title">Add New Class</h2>
            <p class="page-subtitle">Create a new class and assign teacher</p>
        </div>

        <!-- Form -->
        <div class="form-container">
            @if(session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.classes.store') }}" method="POST">
                @csrf
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label" for="name">
                                <i class="fas fa-school me-2"></i>
                                Class Name *
                            </label>
                            <input type="text" 
                                   class="form-control" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name') }}" 
                                   placeholder="e.g., Class 1A, Grade 5B"
                                   required>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label" for="grade_level">
                                <i class="fas fa-layer-group me-2"></i>
                                Grade Level
                            </label>
                            <input type="text" 
                                   class="form-control" 
                                   id="grade_level" 
                                   name="grade_level" 
                                   value="{{ old('grade_level') }}" 
                                   placeholder="e.g., Grade 5, Form 3">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label" for="teacher_id">
                                <i class="fas fa-chalkboard-teacher me-2"></i>
                                Assigned Teacher
                            </label>
                            <select class="form-select" id="teacher_id" name="teacher_id">
                                <option value="">Select Teacher (Optional)</option>
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}" 
                                            {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                        {{ $teacher->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label" for="capacity">
                                <i class="fas fa-users me-2"></i>
                                Class Capacity *
                            </label>
                            <input type="number" 
                                   class="form-control" 
                                   id="capacity" 
                                   name="capacity" 
                                   value="{{ old('capacity', 30) }}" 
                                   min="1" 
                                   max="100" 
                                   required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label" for="academic_year">
                                <i class="fas fa-calendar-alt me-2"></i>
                                Academic Year
                            </label>
                            <input type="text" 
                                   class="form-control" 
                                   id="academic_year" 
                                   name="academic_year" 
                                   value="{{ old('academic_year') }}" 
                                   placeholder="e.g., 2024-2025">
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <div class="form-check">
                                <input type="checkbox" 
                                       class="form-check-input" 
                                       id="is_active" 
                                       name="is_active" 
                                       value="1" 
                                       {{ old('is_active', '1') ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    <i class="fas fa-toggle-on me-2"></i>
                                    Active Class
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="description">
                        <i class="fas fa-align-left me-2"></i>
                        Description
                    </label>
                    <textarea class="form-control" 
                              id="description" 
                              name="description" 
                              rows="4" 
                              placeholder="Enter class description, schedule, or additional information...">{{ old('description') }}</textarea>
                </div>

                <div class="btn-container">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>
                        Create Class
                    </button>
                    <a href="{{ route('admin.classes.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times me-2"></i>
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Logout confirmation
        function confirmLogout() {
            if (confirm('Are you sure you want to logout?')) {
                document.getElementById('logout-form').submit();
            }
        }

        // Mobile menu toggle
        document.addEventListener('DOMContentLoaded', function() {
            const mobileToggle = document.createElement('button');
            mobileToggle.className = 'mobile-menu-toggle d-md-none';
            mobileToggle.innerHTML = '<i class="fas fa-bars"></i>';
            mobileToggle.style.cssText = `
                position: fixed;
                top: 1rem;
                left: 1rem;
                z-index: 1001;
                background: #667eea;
                color: white;
                border: none;
                padding: 0.75rem;
                border-radius: 8px;
                font-size: 1.2rem;
                cursor: pointer;
            `;
            
            document.body.appendChild(mobileToggle);
            
            mobileToggle.addEventListener('click', function() {
                document.querySelector('.sidebar').classList.toggle('show');
            });
        });
    </script>

    <!-- Hidden Logout Form -->
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
</body>
</html>
