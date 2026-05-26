<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard - Attendance System</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
        }

        /* Sidebar Styles */
        .sidebar {
            background: #f8f9fa;
            border-right: 1px solid #e9ecef;
            min-height: 100vh;
            padding: 0;
            position: fixed;
            top: 0;
            left: 0;
            width: 280px;
            z-index: 1000;
        }

        .sidebar-content {
            padding: 1.5rem;
        }

        .sidebar-title {
            color: #495057;
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #e9ecef;
        }

        .menu-section {
            margin-bottom: 2rem;
        }

        .menu-header {
            color: #6c757d;
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.75rem;
        }

        .menu-items {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .menu-item {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            color: #495057;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s ease;
            font-size: 0.95rem;
        }

        .menu-item:hover {
            background: #e9ecef;
            color: #495057;
            text-decoration: none;
        }

        .menu-item.active {
            background: #007bff;
            color: white;
        }

        /* Main Content Styles */
        .main-content {
            margin-left: 280px;
            padding: 2rem;
            min-height: 100vh;
        }

        .content-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #e9ecef;
        }

        .page-title {
            color: #495057;
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
        }

        .page-subtitle {
            color: #6c757d;
            margin-bottom: 0;
        }

        /* Statistics Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            border-left: 4px solid;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 16px rgba(0,0,0,0.12);
        }

        .stat-card.primary { border-left-color: #007bff; }
        .stat-card.success { border-left-color: #28a745; }
        .stat-card.warning { border-left-color: #ffc107; }
        .stat-card.danger { border-left-color: #dc3545; }
        .stat-card.info { border-left-color: #17a2b8; }

        .stat-icon {
            font-size: 2rem;
            margin-bottom: 1rem;
            color: #6c757d;
        }

        .stat-card.primary .stat-icon { color: #007bff; }
        .stat-card.success .stat-icon { color: #28a745; }
        .stat-card.warning .stat-icon { color: #ffc107; }
        .stat-card.danger .stat-icon { color: #dc3545; }
        .stat-card.info .stat-icon { color: #17a2b8; }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: #495057;
            margin-bottom: 0.25rem;
        }

        .stat-label {
            color: #6c757d;
            font-size: 0.9rem;
            font-weight: 500;
        }

        /* Sections */
        .dashboard-section {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #e9ecef;
        }

        .section-title {
            color: #495057;
            font-size: 1.2rem;
            font-weight: 600;
            margin: 0;
        }

        /* Table Styles */
        .dashboard-table {
            margin: 0;
        }

        .dashboard-table th {
            background: #f8f9fa;
            border-bottom: 2px solid #e9ecef;
            color: #495057;
            font-weight: 600;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .dashboard-table td {
            vertical-align: middle;
            border-bottom: 1px solid #e9ecef;
        }

        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .status-present { background: #d4edda; color: #155724; }
        .status-absent { background: #f8d7da; color: #721c24; }
        .status-late { background: #fff3cd; color: #856404; }

        /* Action Buttons */
        .btn-clear {
            background: #dc3545;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .btn-clear:hover {
            background: #c82333;
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(220, 53, 69, 0.3);
        }

        /* Responsive */
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
            
            .stats-grid {
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
                    <a href="{{ route('admin.dashboard') }}" class="menu-item active">
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
                    <a href="{{ route('admin.classes.index') }}" class="menu-item">
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
            <div class="header-left">
                <h2 class="page-title">Admin Dashboard</h2>
                <p class="page-subtitle">System overview and attendance statistics</p>
            </div>
            <div class="header-right">
                <div class="text-muted">
                    <i class="fas fa-clock me-2"></i>
                    {{ now()->format('M d, Y - h:i A') }}
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="stats-grid">
            <div class="stat-card success">
                <div class="stat-icon">
                    <i class="fas fa-chalkboard-teacher"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $totalTeachers ?? 0 }}</div>
                    <div class="stat-label">Teachers</div>
                </div>
            </div>
            <div class="stat-card info">
                <div class="stat-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $totalStudents ?? 0 }}</div>
                    <div class="stat-label">Students</div>
                </div>
            </div>
        </div>

        <!-- Recent Attendance Activity -->
        <div class="dashboard-section">
            <div class="section-header">
                <h5 class="section-title">
                    <i class="fas fa-history me-2"></i>
                    Recent Attendance Activity
                </h5>
                <form method="POST" action="{{ route('admin.clear-attendance') }}" onsubmit="return confirm('Are you sure you want to clear all attendance records? This action cannot be undone.')">
                    @csrf
                    <button type="submit" class="btn-clear">
                        <i class="fas fa-trash me-2"></i>
                        Clear All
                    </button>
                </form>
            </div>

            <div class="table-responsive">
                <table class="table dashboard-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Student</th>
                            <th>Teacher</th>
                            <th>Classroom</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($recentAttendances) && $recentAttendances->count() > 0)
                            @foreach($recentAttendances as $attendance)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($attendance->date)->format('M d, Y') }}</td>
                                <td>
                                    <div>
                                        <strong>{{ $attendance->student->name ?? 'N/A' }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $attendance->student->email ?? 'N/A' }}</small>
                                    </div>
                                </td>
                                <td>{{ $attendance->teacher->name ?? 'N/A' }}</td>
                                <td>{{ $attendance->classroom ?? 'N/A' }}</td>
                                <td>
                                    <span class="status-badge status-{{ $attendance->status }}">
                                        {{ ucfirst($attendance->status) }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <i class="fas fa-inbox fa-2x text-muted mb-2"></i>
                                    <p class="text-muted">No recent attendance activity found</p>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="row">
            <div class="col-md-6">
                <div class="dashboard-section">
                    <div class="section-header">
                        <h5 class="section-title">
                            <i class="fas fa-chart-pie me-2"></i>
                            Attendance Overview
                        </h5>
                    </div>
                    <div class="row text-center">
                        <div class="col-4">
                            <div class="stat-number text-success">{{ $presentCount ?? 0 }}</div>
                            <div class="stat-label">Present</div>
                        </div>
                        <div class="col-4">
                            <div class="stat-number text-danger">{{ $absentCount ?? 0 }}</div>
                            <div class="stat-label">Absent</div>
                        </div>
                        <div class="col-4">
                            <div class="stat-number text-warning">{{ $lateCount ?? 0 }}</div>
                            <div class="stat-label">Late</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="dashboard-section">
                    <div class="section-header">
                        <h5 class="section-title">
                            <i class="fas fa-door-open me-2"></i>
                            Classrooms
                        </h5>
                    </div>
                    <div class="text-center">
                        <div class="stat-number">{{ $totalClassrooms ?? 0 }}</div>
                        <div class="stat-label">Active Classrooms</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Menu Toggle -->
    <button class="btn btn-primary mobile-menu-toggle d-md-none" style="position: fixed; top: 20px; left: 20px; z-index: 1001;">
        <i class="fas fa-bars"></i>
    </button>

    <script>
        // Mobile menu toggle
        document.querySelector('.mobile-menu-toggle')?.addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('show');
        });

        // Close mobile menu when clicking outside
        document.addEventListener('click', function(e) {
            const sidebar = document.querySelector('.sidebar');
            const toggle = document.querySelector('.mobile-menu-toggle');
            
            if (window.innerWidth <= 768 && 
                !sidebar.contains(e.target) && 
                !toggle.contains(e.target) &&
                sidebar.classList.contains('show')) {
                sidebar.classList.remove('show');
            }
        });

        // Logout confirmation
        function confirmLogout() {
            if (confirm('Are you sure you want to logout?')) {
                document.getElementById('logout-form').submit();
            }
        }
    </script>

    <!-- Hidden Logout Form -->
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
</body>
</html>
