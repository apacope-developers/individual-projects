<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Attendance Records - Admin Dashboard</title>
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
        
        .attendance-table {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .filter-section {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        .filter-section input,
        .filter-section select {
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 0.75rem;
        }
        
        .filter-section input:focus,
        .filter-section select:focus {
            border-color: #1a365d;
            box-shadow: 0 0 0 3px rgba(26, 54, 93, 0.1);
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
        
        .avatar-sm {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.8rem;
            color: white;
        }
        
        .avatar-teacher {
            background: linear-gradient(135deg, #1a1a2e 0%, #0f0f1e 100%);
        }
        
        .avatar-student {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        }
        
        .btn-filter {
            background: linear-gradient(135deg, #1a1a2e 0%, #0f0f1e 100%);
            border: none;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }
        
        .btn-filter:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }
        
        .stats-row {
            display: flex;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        
        .stat-item {
            flex: 1;
            text-align: center;
            padding: 1rem;
            background: #f8f9fa;
            border-radius: 10px;
        }
        
        .stat-item .number {
            font-size: 1.5rem;
            font-weight: 700;
        }
        
        .stat-item.present .number {
            color: #28a745;
        }
        
        .stat-item.absent .number {
            color: #dc3545;
        }
        
        .stat-item.late .number {
            color: #ffc107;
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
                            <a class="nav-link" href="{{ route('admin.teachers') }}">
                                <i class="fas fa-chalkboard-teacher"></i> Teachers
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.students') }}">
                                <i class="fas fa-graduation-cap"></i> Students
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="{{ route('admin.attendance') }}">
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
                            <h1 class="h3 mb-0">Attendance Records</h1>
                            <p class="text-muted mb-0">View and filter all attendance records in the system</p>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-primary">{{ $attendances->total() }} Records</span>
                        </div>
                    </div>
                </div>

                <div class="attendance-table">
                    <!-- Filter Section -->
                    <div class="filter-section">
                        <form method="GET" action="{{ route('admin.attendance') }}">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label class="form-label">Date</label>
                                    <input type="date" name="date" class="form-control" value="{{ $date }}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Classroom</label>
                                    <select name="classroom" class="form-select">
                                        <option value="">All Classrooms</option>
                                        @foreach($classrooms as $classroom)
                                            <option value="{{ $classroom }}" {{ $classroom === $classroom ? 'selected' : '' }}>
                                                {{ $classroom }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Search</label>
                                    <input type="text" name="search" class="form-control" placeholder="Search students/teachers..." value="{{ request('search') }}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">&nbsp;</label>
                                    <div>
                                        <button type="submit" class="btn btn-filter">
                                            <i class="fas fa-filter me-2"></i>Filter
                                        </button>
                                        <a href="{{ route('admin.attendance') }}" class="btn btn-outline-secondary ms-2">
                                            <i class="fas fa-times me-2"></i>Clear
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Statistics -->
                    <div class="stats-row">
                        <div class="stat-item present">
                            <div class="number">{{ $attendances->where('status', 'present')->count() }}</div>
                            <div class="text-muted">Present</div>
                        </div>
                        <div class="stat-item absent">
                            <div class="number">{{ $attendances->where('status', 'absent')->count() }}</div>
                            <div class="text-muted">Absent</div>
                        </div>
                        <div class="stat-item late">
                            <div class="number">{{ $attendances->where('status', 'late')->count() }}</div>
                            <div class="text-muted">Late</div>
                        </div>
                    </div>

                    <!-- Attendance Table -->
                    @if($attendances->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Student</th>
                                        <th>Teacher</th>
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
                                                    <div class="avatar-sm avatar-student me-2">
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
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm avatar-teacher me-2">
                                                        {{ strtoupper(substr($attendance->teacher->name ?? 'Unknown', 0, 1)) }}
                                                    </div>
                                                    <div>
                                                        <strong>{{ $attendance->teacher->name ?? 'Unknown Teacher' }}</strong>
                                                        <br>
                                                        <small class="text-muted">{{ $attendance->teacher->email ?? 'No email' }}</small>
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
                                                    <small class="text-muted">{{ \Illuminate\Support\Str::limit($attendance->note, 50) }}</small>
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
                            <p class="text-muted">Try adjusting your filters or check back later for new records.</p>
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
</body>
</html>
