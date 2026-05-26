<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Teacher Attendance - Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(135deg, #1a1a2e 0%, #0f0f1e 100%);
            color: white;
        }
        
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 1rem 1.5rem;
            transition: all 0.3s ease;
        }
        
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: white;
            background: rgba(255, 255, 255, 0.1);
        }
        
        .sidebar .nav-link i {
            width: 20px;
            margin-right: 10px;
        }
        
        .main-content {
            padding: 2rem;
        }
        
        .page-header {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .teacher-info {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .teacher-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, #1a1a2e 0%, #0f0f1e 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 2rem;
        }
        
        .stats-cards {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
        }
        
        .stat-card {
            flex: 1;
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        
        .stat-card.present {
            border-top: 4px solid #28a745;
        }
        
        .stat-card.absent {
            border-top: 4px solid #dc3545;
        }
        
        .stat-card.late {
            border-top: 4px solid #ffc107;
        }
        
        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        .stat-card.present .stat-number {
            color: #28a745;
        }
        
        .stat-card.absent .stat-number {
            color: #dc3545;
        }
        
        .stat-card.late .stat-number {
            color: #ffc107;
        }
        
        .attendance-table {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .table thead th {
            border-bottom: 2px solid #e9ecef;
            font-weight: 600;
            color: #495057;
        }
        
        .badge-status {
            font-size: 0.8rem;
            padding: 0.25rem 0.5rem;
        }
        
        .btn-back {
            background: linear-gradient(135deg, #1a1a2e 0%, #0f0f1e 100%);
            border: none;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }
        
        .btn-back:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }
        
        .search-filter {
            background: white;
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        .search-filter input {
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 0.75rem;
        }
        
        .search-filter input:focus {
            border-color: #1a365d;
            box-shadow: 0 0 0 3px rgba(26, 54, 93, 0.1);
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                <div class="position-sticky pt-3">
                    <div class="text-center mb-4">
                        <h4><i class="fas fa-user-shield me-2"></i>Admin Panel</h4>
                    </div>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.dashboard') }}">
                                <i class="fas fa-tachometer-alt"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="{{ route('admin.teachers') }}">
                                <i class="fas fa-chalkboard-teacher"></i> Teachers
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.students') }}">
                                <i class="fas fa-graduation-cap"></i> Students
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.attendance') }}">
                                <i class="fas fa-calendar-check"></i> Attendance
                            </a>
                        </li>
                        <li class="nav-item mt-3">
                            <a class="nav-link" href="{{ route('attendance.index') }}">
                                <i class="fas fa-user"></i> Teacher View
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content">
                <div class="page-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h1 class="h3 mb-0">Teacher Attendance Records</h1>
                            <p class="text-muted mb-0">View detailed attendance records for {{ $teacher->name }}</p>
                        </div>
                        <a href="{{ route('admin.teachers') }}" class="btn btn-back">
                            <i class="fas fa-arrow-left me-2"></i>Back to Teachers
                        </a>
                    </div>
                </div>

                <!-- Teacher Information -->
                <div class="teacher-info">
                    <div class="row align-items-center">
                        <div class="col-md-3">
                            <div class="teacher-avatar mx-auto">
                                {{ strtoupper(substr($teacher->name, 0, 1)) }}
                            </div>
                        </div>
                        <div class="col-md-9">
                            <h3>{{ $teacher->name }}</h3>
                            <p class="text-muted mb-2">{{ $teacher->email }}</p>
                            <div class="d-flex gap-3">
                                <span class="badge bg-primary">Teacher</span>
                                @if($teacher->classroom)
                                    <span class="badge bg-info">
                                        <i class="fas fa-door-open me-1"></i>{{ $teacher->classroom }}
                                    </span>
                                @endif
                                <span class="badge bg-secondary">
                                    <i class="fas fa-calendar-check me-1"></i>{{ $attendanceStats['total'] }} Records
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Statistics Cards -->
                <div class="stats-cards">
                    <div class="stat-card present">
                        <div class="stat-number">{{ $attendanceStats['present'] }}</div>
                        <div class="text-muted">Present</div>
                    </div>
                    <div class="stat-card absent">
                        <div class="stat-number">{{ $attendanceStats['absent'] }}</div>
                        <div class="text-muted">Absent</div>
                    </div>
                    <div class="stat-card late">
                        <div class="stat-number">{{ $attendanceStats['late'] }}</div>
                        <div class="text-muted">Late</div>
                    </div>
                </div>

                <!-- Attendance Table -->
                <div class="attendance-table">
                    <h5 class="mb-3"><i class="fas fa-list me-2"></i>Attendance Records</h5>
                    
                    <!-- Search/Filter -->
                    <div class="search-filter">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-search"></i>
                                    </span>
                                    <input type="text" class="form-control" placeholder="Search students..." id="searchInput">
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($attendances->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover" id="attendanceTable">
                                <thead>
                                    <tr>
                                        <th>Student</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                        <th>Classroom</th>
                                        <th>Note</th>
                                        <th>Recorded</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($attendances as $attendance)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-2" style="width: 30px; height: 30px; font-size: 0.8rem;">
                                                        {{ strtoupper(substr($attendance->student->name ?? 'Unknown', 0, 1)) }}
                                                    </div>
                                                    <div>
                                                        <strong>{{ $attendance->student->name ?? 'Unknown Student' }}</strong>
                                                        <br>
                                                        <small class="text-muted">{{ $attendance->student->email ?? 'No email' }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <i class="fas fa-calendar me-2"></i>{{ $attendance->date->format('M j, Y') }}
                                            </td>
                                            <td>
                                                <span class="badge badge-status {{ $attendance->status === 'present' ? 'bg-success' : ($attendance->status === 'absent' ? 'bg-danger' : 'bg-warning') }}">
                                                    {{ ucfirst($attendance->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($attendance->classroom)
                                                    <span class="badge bg-info">{{ $attendance->classroom }}</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($attendance->note)
                                                    <small class="text-muted">{{ Str::limit($attendance->note, 50) }}</small>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                <small class="text-muted">{{ $attendance->created_at->diffForHumans() }}</small>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-calendar-times fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted">No attendance records found</h5>
                            <p class="text-muted">This teacher hasn't recorded any attendance yet.</p>
                        </div>
                    @endif

                    <!-- Pagination -->
                    @if($attendances->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $attendances->links() }}
                        </div>
                    @endif
                </div>
            </main>
        </div>
    </div>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Search functionality
        document.getElementById('searchInput').addEventListener('keyup', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = document.querySelectorAll('#attendanceTable tbody tr');
            
            rows.forEach(function(row) {
                const studentName = row.querySelector('td:first-child strong').textContent.toLowerCase();
                const studentEmail = row.querySelector('td:first-child small').textContent.toLowerCase();
                
                if (studentName.includes(searchTerm) || studentEmail.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>
