@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 sidebar">
            <div class="sidebar-content">
                <h4 class="sidebar-title">
                    <i class="fas fa-chart-line me-2"></i>
                    Reports Menu
                </h4>
                
                <div class="menu-section">
                    <h6 class="menu-header">Quick Filters</h6>
                    <div class="menu-items">
                        <a href="{{ route('attendance.reports') }}" class="menu-item active">
                            <i class="fas fa-calendar-day me-2"></i>
                            Recent (7 days)
                        </a>
                        <a href="{{ route('attendance.reports', ['view' => 'weekly']) }}" class="menu-item">
                            <i class="fas fa-calendar-week me-2"></i>
                            This Week
                        </a>
                        <a href="{{ route('attendance.reports', ['view' => 'monthly']) }}" class="menu-item">
                            <i class="fas fa-calendar-alt me-2"></i>
                            This Month
                        </a>
                    </div>
                </div>

                @if(auth()->user()->role === 'admin' && isset($teachers) && $teachers->count() > 0)
                <div class="menu-section">
                    <h6 class="menu-header">Teachers</h6>
                    <div class="menu-items">
                        <a href="{{ route('attendance.reports') }}" class="menu-item {{ !request()->get('teacher_id') ? 'active' : '' }}">
                            <i class="fas fa-users me-2"></i>
                            All Teachers
                        </a>
                        @foreach($teachers as $t)
                        <a href="{{ route('attendance.reports', ['teacher_id' => $t->id]) }}" 
                           class="menu-item {{ request()->get('teacher_id') == $t->id ? 'active' : '' }}">
                            <i class="fas fa-user me-2"></i>
                            {{ $t->name }}
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif

                <div class="menu-section">
                    <h6 class="menu-header">Actions</h6>
                    <div class="menu-items">
                        <a href="{{ route('attendance.index') }}" class="menu-item">
                            <i class="fas fa-plus me-2"></i>
                            Mark Attendance
                        </a>
                        <a href="#" class="menu-item" onclick="exportReport()">
                            <i class="fas fa-download me-2"></i>
                            Export Report
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-md-9 main-content">
            <!-- Header -->
            <div class="content-header">
                <div class="header-left">
                    <h2 class="page-title">
                        @if(auth()->user()->role === 'admin' && auth()->user()->id !== $teacher->id)
                            {{ $teacher->name }}'s Reports
                        @else
                            My Reports
                        @endif
                    </h2>
                    <p class="page-subtitle">
                        Attendance statistics and records
                    </p>
                </div>
                <div class="header-right">
                    <div class="classroom-badge">
                        <i class="fas fa-door-open me-2"></i>
                        {{ $classroom ?? 'Not Assigned' }}
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="stats-grid">
                <div class="stat-card primary">
                    <div class="stat-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number">{{ $totalAttendance ?? 0 }}</div>
                        <div class="stat-label">Total Records</div>
                    </div>
                </div>
                <div class="stat-card success">
                    <div class="stat-icon">
                        <i class="fas fa-user-check"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number">{{ $presentCount ?? 0 }}</div>
                        <div class="stat-label">Present</div>
                    </div>
                </div>
                <div class="stat-card danger">
                    <div class="stat-icon">
                        <i class="fas fa-user-times"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number">{{ $absentCount ?? 0 }}</div>
                        <div class="stat-label">Absent</div>
                    </div>
                </div>
                <div class="stat-card warning">
                    <div class="stat-icon">
                        <i class="fas fa-user-clock"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number">{{ $lateCount ?? 0 }}</div>
                        <div class="stat-label">Late</div>
                    </div>
                </div>
            </div>

            <!-- Search and Filter -->
            <div class="filter-section">
                <form method="GET" action="{{ route('attendance.reports') }}">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Search Students</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-search"></i>
                                </span>
                                <input type="text" name="student_search" class="form-control" 
                                       placeholder="Search by name or email"
                                       value="{{ request()->get('student_search') }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Start Date</label>
                            <input type="date" name="start_date" class="form-control"
                                   value="{{ request()->get('start_date') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">End Date</label>
                            <input type="date" name="end_date" class="form-control"
                                   value="{{ request()->get('end_date') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-filter me-1"></i>
                                    Filter
                                </button>
                                <a href="{{ route('attendance.reports') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-times me-1"></i>
                                    Clear
                                </a>
                            </div>
                        </div>
                    </div>
                    @if(request()->get('teacher_id'))
                    <input type="hidden" name="teacher_id" value="{{ request()->get('teacher_id') }}">
                    @endif
                </form>
            </div>

            <!-- Attendance Records -->
            <div class="records-section">
                <div class="section-header">
                    <h5 class="section-title">
                        <i class="fas fa-list me-2"></i>
                        Attendance Records
                    </h5>
                    <div class="record-count">
                        {{ $attendances->total() }} records found
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table attendance-table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Student Name</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th>Classroom</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($attendances) && $attendances->count() > 0)
                                @foreach($attendances as $attendance)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($attendance->date)->format('M d, Y') }}</td>
                                    <td>{{ $attendance->student->name ?? 'N/A' }}</td>
                                    <td>{{ $attendance->student->email ?? 'N/A' }}</td>
                                    <td>
                                        <span class="badge status-{{ $attendance->status }}">
                                            {{ ucfirst($attendance->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $attendance->classroom ?? 'N/A' }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary" onclick="viewDetails({{ $attendance->id }})">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <i class="fas fa-inbox fa-2x text-muted mb-2"></i>
                                        <p class="text-muted">No attendance records found</p>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                @if(isset($attendances) && $attendances->hasPages())
                <div class="pagination-wrapper">
                    {{ $attendances->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
/* Sidebar Styles */
.sidebar {
    background: #f8f9fa;
    border-right: 1px solid #e9ecef;
    min-height: 100vh;
    padding: 0;
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
    padding: 2rem;
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

.classroom-badge {
    background: #e9ecef;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.9rem;
    color: #495057;
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
.stat-card.danger { border-left-color: #dc3545; }
.stat-card.warning { border-left-color: #ffc107; }

.stat-icon {
    font-size: 1.5rem;
    margin-bottom: 1rem;
    color: #6c757d;
}

.stat-card.primary .stat-icon { color: #007bff; }
.stat-card.success .stat-icon { color: #28a745; }
.stat-card.danger .stat-icon { color: #dc3545; }
.stat-card.warning .stat-icon { color: #ffc107; }

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
.attendance-table {
    margin: 0;
}

.attendance-table th {
    background: #f8f9fa;
    border-bottom: 2px solid #e9ecef;
    color: #495057;
    font-weight: 600;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.attendance-table td {
    vertical-align: middle;
    border-bottom: 1px solid #e9ecef;
}

.status-present { background: #d4edda; color: #155724; }
.status-absent { background: #f8d7da; color: #721c24; }
.status-late { background: #fff3cd; color: #856404; }

.pagination-wrapper {
    padding: 1.5rem;
    border-top: 1px solid #e9ecef;
}

/* Responsive */
@media (max-width: 768px) {
    .sidebar {
        display: none;
    }
    
    .main-content {
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

<script>
function viewDetails(id) {
    // Implementation for viewing attendance details
    console.log('View details for attendance ID:', id);
}

function exportReport() {
    // Implementation for exporting reports
    console.log('Export report');
}
</script>
@endsection
