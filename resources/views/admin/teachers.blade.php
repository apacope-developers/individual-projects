<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Teachers - Admin Dashboard</title>
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
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
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
        .stat-card.info { border-left-color: #17a2b8; }

        .stat-icon {
            font-size: 1.5rem;
            margin-bottom: 1rem;
            color: #6c757d;
        }

        .stat-card.primary .stat-icon { color: #007bff; }
        .stat-card.success .stat-icon { color: #28a745; }
        .stat-card.warning .stat-icon { color: #ffc107; }
        .stat-card.info .stat-icon { color: #17a2b8; }

        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: #495057;
            margin-bottom: 0.25rem;
        }

        .stat-label {
            color: #6c757d;
            font-size: 0.9rem;
        }

        /* Filter Section */
        .filter-section {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        /* Records Section */
        .records-section {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.5rem;
            border-bottom: 1px solid #e9ecef;
            background: #f8f9fa;
        }

        .section-title {
            color: #495057;
            font-size: 1.1rem;
            font-weight: 600;
            margin: 0;
        }

        .record-count {
            color: #6c757d;
            font-size: 0.9rem;
        }

        /* Table Styles */
        .teachers-table {
            margin: 0;
        }

        .teachers-table th {
            background: #f8f9fa;
            border-bottom: 2px solid #e9ecef;
            color: #495057;
            font-weight: 600;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .teachers-table td {
            vertical-align: middle;
            border-bottom: 1px solid #e9ecef;
        }

        .teacher-info {
            display: flex;
            flex-direction: column;
        }

        .teacher-name {
            font-weight: 600;
            color: #495057;
        }

        .teacher-role {
            font-size: 0.85rem;
        }

        .attendance-count {
            font-size: 0.9rem;
            color: #6c757d;
        }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }

        .pagination-wrapper {
            padding: 1.5rem;
            border-top: 1px solid #e9ecef;
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
            
            .action-buttons {
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
                <i class="fas fa-users me-2"></i>
                Admin Panel
            </h4>
            
            <div class="menu-section">
                <h6 class="menu-header">Main Menu</h6>
                <div class="menu-items">
                    <a href="{{ route('admin.dashboard') }}" class="menu-item">
                        <i class="fas fa-tachometer-alt me-2"></i>
                        Dashboard
                    </a>
                    <a href="{{ route('admin.teachers') }}" class="menu-item active">
                        <i class="fas fa-chalkboard-teacher me-2"></i>
                        Teachers
                    </a>
                    <a href="{{ route('admin.students') }}" class="menu-item">
                        <i class="fas fa-graduation-cap me-2"></i>
                        Students
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
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Header -->
        <div class="content-header">
            <div class="header-left">
                <h2 class="page-title">Teachers Management</h2>
                <p class="page-subtitle">Manage and monitor all teachers in the system</p>
            </div>
            <div class="header-right">
                <a href="{{ route('admin.teachers.create') }}" class="btn btn-primary">
                    <i class="fas fa-user-plus me-2"></i>
                    Add New Teacher
                </a>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="stats-grid">
            <div class="stat-card primary">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $teachers->total() }}</div>
                    <div class="stat-label">Total Teachers</div>
                </div>
            </div>
            <div class="stat-card success">
                <div class="stat-icon">
                    <i class="fas fa-door-open"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $teachers->where('classroom', '!=', null)->count() }}</div>
                    <div class="stat-label">With Classrooms</div>
                </div>
            </div>
            <div class="stat-card warning">
                <div class="stat-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $teachers->where('classroom', null)->count() }}</div>
                    <div class="stat-label">No Classroom</div>
                </div>
            </div>
            <div class="stat-card info">
                <div class="stat-icon">
                    <i class="fas fa-chart-bar"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $teachers->sum('attendance_count') ?? 0 }}</div>
                    <div class="stat-label">Total Records</div>
                </div>
            </div>
        </div>

        <!-- Search and Filter -->
        <div class="filter-section">
            <form method="GET" action="{{ route('admin.teachers') }}">
                <div class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label">Search Teachers</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="text" name="search" class="form-control" 
                                   placeholder="Search by name, email, or classroom"
                                   value="{{ request()->get('search') }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search me-1"></i>
                                Search
                            </button>
                            <a href="{{ route('admin.teachers') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-1"></i>
                                Clear
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Teachers List -->
        <div class="records-section">
            <div class="section-header">
                <h5 class="section-title">
                    <i class="fas fa-list me-2"></i>
                    Teachers List
                </h5>
                <div class="record-count">
                    {{ $teachers->total() }} teachers found
                </div>
            </div>

            <div class="table-responsive">
                <table class="table teachers-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Classroom</th>
                            <th>Attendance Records</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($teachers->count() > 0)
                            @foreach($teachers as $teacher)
                            <tr>
                                <td>
                                    <div class="teacher-info">
                                        <div class="teacher-name">{{ $teacher->name }}</div>
                                        <div class="teacher-role text-muted">Teacher</div>
                                    </div>
                                </td>
                                <td>{{ $teacher->email }}</td>
                                <td>
                                    @if($teacher->classroom)
                                        <span class="badge bg-info">
                                            <i class="fas fa-door-open me-1"></i>{{ $teacher->classroom }}
                                        </span>
                                    @else
                                        <span class="text-muted">No classroom assigned</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="attendance-count">
                                        {{ $teacher->attendance_count ?? 0 }} records
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-success">
                                        <i class="fas fa-circle me-1"></i>
                                        Active
                                    </span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="{{ route('attendance.reports', ['teacher_id' => $teacher->id]) }}" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye me-1"></i>
                                            View
                                        </a>
                                        <a href="#" class="btn btn-sm btn-outline-secondary">
                                            <i class="fas fa-edit me-1"></i>
                                            Edit
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <i class="fas fa-chalkboard-teacher fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">No teachers found</h5>
                                    <p class="text-muted">
                                        @if(request()->get('search'))
                                            No teachers match your search criteria.
                                        @else
                                            No teachers have been added yet.
                                        @endif
                                    </p>
                                    <a href="{{ route('admin.teachers.create') }}" class="btn btn-primary">
                                        <i class="fas fa-user-plus me-2"></i>
                                        Add First Teacher
                                    </a>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            @if($teachers->hasPages())
            <div class="pagination-wrapper">
                {{ $teachers->links() }}
            </div>
            @endif
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
    </script>
</body>
</html>
