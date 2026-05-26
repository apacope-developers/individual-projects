<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Class Details - Admin</title>
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
            display: flex;
            justify-content: space-between;
            align-items: center;
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

        .header-right {
            display: flex;
            gap: 1rem;
        }

        .btn-action {
            padding: 0.5rem 1rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            font-size: 0.9rem;
        }

        .btn-edit {
            background: #ffc107;
            color: #212529;
        }

        .btn-edit:hover {
            background: #e0a800;
            transform: translateY(-1px);
        }

        .class-details {
            background: rgba(255, 255, 255, 0.95);
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .info-card {
            background: white;
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            border-left: 4px solid #667eea;
        }

        .info-card h5 {
            color: #2c3e50;
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.5rem 0;
            border-bottom: 1px solid #e9ecef;
        }

        .info-item:last-child {
            border-bottom: none;
        }

        .info-label {
            font-weight: 600;
            color: #495057;
        }

        .info-value {
            color: #6c757d;
        }

        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .status-active {
            background: #d4edda;
            color: #155724;
        }

        .status-inactive {
            background: #f8d7da;
            color: #721c24;
        }

        .section-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .students-table {
            width: 100%;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        }

        .table {
            margin: 0;
        }

        .table th {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            font-weight: 600;
            border: none;
            padding: 1rem;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .table td {
            padding: 1rem;
            vertical-align: middle;
            border-bottom: 1px solid #e9ecef;
            font-size: 0.95rem;
        }

        .table tbody tr:hover {
            background: #f8f9fa;
        }

        .empty-state {
            text-align: center;
            padding: 3rem;
            color: #6c757d;
        }

        .empty-state i {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: #dee2e6;
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
            
            .content-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }
            
            .info-grid {
                grid-template-columns: 1fr;
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
            <div>
                <h2 class="page-title">{{ $class->name }}</h2>
                <p class="page-subtitle">Class details and student information</p>
            </div>
            <div class="header-right">
                <a href="{{ route('admin.classes.edit', $class->id) }}" class="btn-action btn-edit">
                    <i class="fas fa-edit"></i>
                    Edit Class
                </a>
            </div>
        </div>

        <!-- Class Details -->
        <div class="class-details">
            <div class="info-grid">
                <div class="info-card">
                    <h5><i class="fas fa-school me-2"></i>Class Information</h5>
                    <div class="info-item">
                        <span class="info-label">Class Name:</span>
                        <span class="info-value">{{ $class->name }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Grade Level:</span>
                        <span class="info-value">{{ $class->grade_level ?? 'Not specified' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Capacity:</span>
                        <span class="info-value">{{ $class->capacity }} students</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Academic Year:</span>
                        <span class="info-value">{{ $class->academic_year ?? 'Not specified' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Status:</span>
                        <span class="info-value">
                            <span class="status-badge {{ $class->is_active ? 'status-active' : 'status-inactive' }}">
                                {{ $class->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </span>
                    </div>
                </div>

                <div class="info-card">
                    <h5><i class="fas fa-chalkboard-teacher me-2"></i>Teacher Assignment</h5>
                    <div class="info-item">
                        <span class="info-label">Assigned Teacher:</span>
                        <span class="info-value">
                            @if($class->teacher)
                                {{ $class->teacher->name }}
                            @else
                                <span class="text-muted">Not assigned</span>
                            @endif
                        </span>
                    </div>
                    @if($class->description)
                        <div class="info-item">
                            <span class="info-label">Description:</span>
                            <span class="info-value">{{ $class->description }}</span>
                        </div>
                    @endif
                </div>

                <div class="info-card">
                    <h5><i class="fas fa-chart-bar me-2"></i>Statistics</h5>
                    <div class="info-item">
                        <span class="info-label">Total Students:</span>
                        <span class="info-value">{{ $class->students->count() }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Available Slots:</span>
                        <span class="info-value">{{ $class->capacity - $class->students->count() }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Total Attendance:</span>
                        <span class="info-value">{{ $class->attendances->count() }}</span>
                    </div>
                </div>
            </div>

            <!-- Students List -->
            <h5 class="section-title">
                <i class="fas fa-users me-2"></i>
                Enrolled Students ({{ $class->students->count() }})
            </h5>

            @if($class->students->count() > 0)
                <div class="students-table">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Student Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Enrollment Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($class->students as $student)
                                <tr>
                                    <td>
                                        <strong>{{ $student->name }}</strong>
                                    </td>
                                    <td>{{ $student->email }}</td>
                                    <td>
                                        <span class="status-badge status-active">
                                            {{ $student->role }}
                                        </span>
                                    </td>
                                    <td>{{ $student->created_at->format('M d, Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-state">
                    <i class="fas fa-user-graduate"></i>
                    <h5>No Students Enrolled</h5>
                    <p>This class currently has no students enrolled.</p>
                </div>
            @endif
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
