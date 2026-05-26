<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Classes - Admin</title>
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
            align-items: center;
            gap: 1rem;
        }

        .add-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .add-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }

        .dashboard-section {
            background: rgba(255, 255, 255, 0.95);
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .section-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #2c3e50;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .search-box {
            position: relative;
            width: 300px;
        }

        .search-input {
            width: 100%;
            padding: 0.5rem 1rem 0.5rem 2.5rem;
            border: 2px solid #e9ecef;
            border-radius: 50px;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .search-input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .search-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
        }

        .classes-table {
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

        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }

        .btn-action {
            padding: 0.25rem 0.75rem;
            border-radius: 6px;
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 500;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }

        .btn-edit {
            background: #ffc107;
            color: #212529;
        }

        .btn-edit:hover {
            background: #e0a800;
            transform: translateY(-1px);
        }

        .btn-delete {
            background: #dc3545;
            color: white;
        }

        .btn-delete:hover {
            background: #c82333;
            transform: translateY(-1px);
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
            
            .search-box {
                width: 100%;
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
                <h2 class="page-title">Classes Management</h2>
                <p class="page-subtitle">Manage school classes and assign teachers</p>
            </div>
            <div class="header-right">
                <a href="{{ route('admin.classes.create') }}" class="add-btn">
                    <i class="fas fa-plus"></i>
                    Add New Class
                </a>
                <a href="#" class="add-btn" onclick="confirmLogout()" style="background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);">
                    <i class="fas fa-sign-out-alt"></i>
                    Logout
                </a>
            </div>
        </div>

        <!-- Classes Table -->
        <div class="dashboard-section">
            <div class="section-header">
                <h5 class="section-title">
                    <i class="fas fa-school me-2"></i>
                    All Classes
                </h5>
                <div class="search-box">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" class="search-input" placeholder="Search classes..." id="searchInput">
                </div>
            </div>

            @if($classes->count() > 0)
                <div class="classes-table">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Class Name</th>
                                <th>Grade Level</th>
                                <th>Teacher</th>
                                <th>Capacity</th>
                                <th>Academic Year</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="classesTableBody">
                            @foreach($classes as $class)
                                <tr>
                                    <td>
                                        <strong>{{ $class->name }}</strong>
                                        @if($class->description)
                                            <br><small class="text-muted">{{ $class->description }}</small>
                                        @endif
                                    </td>
                                    <td>{{ $class->grade_level ?? '-' }}</td>
                                    <td>
                                        @if($class->teacher)
                                            {{ $class->teacher->name }}
                                        @else
                                            <span class="text-muted">Not assigned</span>
                                        @endif
                                    </td>
                                    <td>{{ $class->capacity }}</td>
                                    <td>{{ $class->academic_year ?? '-' }}</td>
                                    <td>
                                        <span class="status-badge {{ $class->is_active ? 'status-active' : 'status-inactive' }}">
                                            {{ $class->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="{{ route('admin.classes.show', $class->id) }}" class="btn-action btn-edit">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.classes.edit', $class->id) }}" class="btn-action btn-edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.classes.destroy', $class->id) }}" method="POST" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-action btn-delete" onclick="return confirmDelete('class')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-state">
                    <i class="fas fa-school"></i>
                    <h5>No Classes Found</h5>
                    <p>Start by adding your first class to the system.</p>
                    <a href="{{ route('admin.classes.create') }}" class="add-btn">
                        <i class="fas fa-plus"></i>
                        Add First Class
                    </a>
                </div>
            @endif
        </div>
    </div>

    <script>
        // Search functionality
        document.getElementById('searchInput').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const rows = document.querySelectorAll('#classesTableBody tr');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });

        // Logout confirmation
        function confirmLogout() {
            if (confirm('Are you sure you want to logout?')) {
                document.getElementById('logout-form').submit();
            }
        }

        // Delete confirmation
        function confirmDelete(item) {
            return confirm(`Are you sure you want to delete this ${item}? This action cannot be undone.`);
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
