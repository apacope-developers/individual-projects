@extends('layouts.app')

@section('content')
<div class="attendance-page">
    <!-- Header Section -->
    <div class="header-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="page-header">
                        <div class="header-icon">
                            <i class="fas fa-user-check"></i>
                        </div>
                        <div>
                            <h1 class="page-title">Attendance Management</h1>
                            <p class="page-subtitle">Mark and manage student attendance</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 text-md-end">
                    <div class="date-display">
                        <i class="fas fa-calendar-alt me-2"></i>
                        <span>{{ \Carbon\Carbon::parse($date)->format('l, F j, Y') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show custom-alert" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Load Students Section -->
        <div class="load-students-section">
            <div class="section-card">
                <div class="card-header-custom">
                    <h5 class="card-title">
                        <i class="fas fa-search me-2"></i>
                        Attendance for {{ $classroom ?? 'Your Classroom' }}
                    </h5>
                    <div class="badge bg-primary">{{ Auth::user()->name }}</div>
                </div>
                <form method="GET" action="{{ route('attendance.index') }}" class="search-form">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-8">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-calendar me-1"></i>
                                Select Date
                            </label>
                            <input type="date" name="date" value="{{ $date }}" class="form-control form-control-lg" />
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary btn-lg w-100">
                                <i class="fas fa-users me-2"></i>
                                Load Students
                            </button>
                        </div>
                    </div>
                    @if($classroom)
                        <div class="mt-3">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                Showing students for <strong>{{ $classroom }}</strong>. Your assigned classroom.
                            </div>
                        </div>
                    @else
                        <div class="mt-3">
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                You don't have a classroom assigned. Please contact an administrator.
                            </div>
                        </div>
                    @endif
                </form>
            </div>
        </div>

        <form method="POST" action="{{ route('attendance.store') }}" id="attendanceForm">
            @csrf
            <input type="hidden" name="date" value="{{ $date }}">
            <input type="hidden" name="classroom" value="{{ $classroom }}">

            @if($students->isNotEmpty())
                @php
                    $presentCount = 0;
                    $absentCount = 0;
                    $lateCount = 0;
                    $totalCount = 0;
                    
                    foreach($students as $student) {
                        $existing = $attendances[$student->id] ?? null;
                        $status = $existing->status ?? 'present';
                        
                        if($status == 'present') $presentCount++;
                        elseif($status == 'absent') $absentCount++;
                        elseif($status == 'late') $lateCount++;
                        $totalCount++;
                    }
                    
                    $attendanceRate = $totalCount > 0 ? round(($presentCount / $totalCount) * 100, 1) : 0;
                @endphp

                <!-- Summary Cards -->
                <div class="summary-section">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <div class="summary-card present-card">
                                <div class="summary-icon">
                                    <i class="fas fa-user-check"></i>
                                </div>
                                <div class="summary-content">
                                    <div class="summary-number">{{ $presentCount }}</div>
                                    <div class="summary-label">Present</div>
                                    <div class="summary-percentage">{{ round(($presentCount / $totalCount) * 100, 1) }}%</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="summary-card absent-card">
                                <div class="summary-icon">
                                    <i class="fas fa-user-times"></i>
                                </div>
                                <div class="summary-content">
                                    <div class="summary-number">{{ $absentCount }}</div>
                                    <div class="summary-label">Absent</div>
                                    <div class="summary-percentage">{{ round(($absentCount / $totalCount) * 100, 1) }}%</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="summary-card late-card">
                                <div class="summary-icon">
                                    <i class="fas fa-user-clock"></i>
                                </div>
                                <div class="summary-content">
                                    <div class="summary-number">{{ $lateCount }}</div>
                                    <div class="summary-label">Late</div>
                                    <div class="summary-percentage">{{ round(($lateCount / $totalCount) * 100, 1) }}%</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="summary-card total-card">
                                <div class="summary-icon">
                                    <i class="fas fa-users"></i>
                                </div>
                                <div class="summary-content">
                                    <div class="summary-number">{{ $totalCount }}</div>
                                    <div class="summary-label">Total</div>
                                    <div class="summary-percentage">100%</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="quick-actions">
                    <div class="section-card">
                        <div class="card-header-custom">
                            <h5 class="card-title">
                                <i class="fas fa-magic me-2"></i>
                                Quick Actions
                            </h5>
                        </div>
                        <div class="action-buttons">
                            <button type="button" class="btn btn-outline-primary" onclick="markAll('present')">
                                <i class="fas fa-user-check me-2"></i>
                                Mark All Present
                            </button>
                            <button type="button" class="btn btn-outline-dark" onclick="markAll('absent')">
                                <i class="fas fa-user-times me-2"></i>
                                Mark All Absent
                            </button>
                            <button type="button" class="btn btn-outline-secondary" onclick="markAll('late')">
                                <i class="fas fa-user-clock me-2"></i>
                                Mark All Late
                            </button>
                            <button type="button" class="btn btn-outline-secondary" onclick="resetAll()">
                                <i class="fas fa-undo me-2"></i>
                                Reset
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Students Table -->
                <div class="students-table-section">
                    <div class="section-card">
                        <div class="card-header-custom">
                            <h5 class="card-title">
                                <i class="fas fa-users me-2"></i>
                                Student List - {{ $classroom ?? 'All Classes' }}
                            </h5>
                            <div class="badge bg-primary">{{ $totalCount }} Students</div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover custom-table">
                                <thead>
                                    <tr>
                                        <th width="5%">#</th>
                                        <th width="45%">Student Name</th>
                                        <th width="15%">ID</th>
                                        <th width="35%">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($students as $index => $student)
                                        @php
                                            $existing = $attendances[$student->id] ?? null;
                                            $status = $existing->status ?? 'present';
                                        @endphp
                                        <tr class="student-row" data-student-id="{{ $student->id }}">
                                            <td>
                                                <div class="student-number">{{ $index + 1 }}</div>
                                            </td>
                                            <td>
                                                <div class="student-info">
                                                    <div class="student-avatar">
                                                        <i class="fas fa-user-graduate"></i>
                                                    </div>
                                                    <div class="student-details">
                                                        <div class="student-name">{{ $student->name }}</div>
                                                        <div class="student-email">{{ $student->email }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="student-id">#{{ str_pad($student->id, 4, '0', STR_PAD_LEFT) }}</span>
                                            </td>
                                            <td>
                                                <div class="status-select-wrapper">
                                                    <select name="statuses[{{ $student->id }}]" class="form-select status-select" data-student-id="{{ $student->id }}">
                                                        <option value="present" {{ $status == 'present' ? 'selected' : '' }}>
                                                            <i class="fas fa-check-circle"></i> Present
                                                        </option>
                                                        <option value="absent" {{ $status == 'absent' ? 'selected' : '' }}>
                                                            <i class="fas fa-times-circle"></i> Absent
                                                        </option>
                                                        <option value="late" {{ $status == 'late' ? 'selected' : '' }}>
                                                            <i class="fas fa-clock"></i> Late
                                                        </option>
                                                    </select>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Save Section -->
                <div class="save-section">
                    <div class="section-card">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <div class="save-info">
                                    <h6>Ready to Save?</h6>
                                    <p class="text-muted mb-0">
                                        <i class="fas fa-info-circle me-1"></i>
                                        {{ $totalCount }} students marked. Attendance rate: {{ $attendanceRate }}%
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-4 text-md-end">
                                <button type="submit" class="btn btn-primary btn-lg save-btn">
                                    <i class="fas fa-save me-2"></i>
                                    Save Attendance
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

            @else
                <div class="empty-state">
                    <div class="section-card text-center">
                        <div class="empty-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <h4>No Students Found</h4>
                        <p class="text-muted">
                            @if(empty($classroom))
                                You don't have a classroom assigned. Please contact an administrator.
                            @else
                                No students found for "{{ $classroom }}". Please check your classroom assignment.
                            @endif
                        </p>
                        @if(empty($classroom))
                            <div class="mt-3">
                                <a href="{{ route('admin.dashboard') }}" class="btn btn-primary">
                                    <i class="fas fa-user-shield me-2"></i>
                                    Contact Administrator
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

        </form>
    </div>
</div>

@if($students->isNotEmpty())
<style>
.attendance-page {
    background: #f8f9fa;
    min-height: 100vh;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.header-section {
    background: #1a365d;
    color: white;
    padding: 2rem 0;
    margin-bottom: 2rem;
}

.page-header {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.header-icon {
    background: rgba(255, 255, 255, 0.2);
    width: 60px;
    height: 60px;
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
}

.page-title {
    font-size: 2rem;
    font-weight: 700;
    margin: 0;
}

.page-subtitle {
    margin: 0;
    opacity: 0.9;
}

.date-display {
    background: rgba(255, 255, 255, 0.2);
    padding: 0.75rem 1.5rem;
    border-radius: 50px;
    font-weight: 500;
}

.section-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    margin-bottom: 1.5rem;
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.section-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
}

.card-header-custom {
    background: #f8f9fa;
    padding: 1.25rem;
    border-bottom: 1px solid #dee2e6;
}

.card-title {
    margin: 0;
    font-weight: 600;
    color: #000;
}

.summary-card {
    background: white;
    border-radius: 15px;
    padding: 1.5rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
    border-left: 4px solid;
    position: relative;
    overflow: hidden;
}

.summary-card::before {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 100px;
    height: 100px;
    background: linear-gradient(135deg, transparent 0%, rgba(0, 102, 204, 0.05) 100%);
    border-radius: 50%;
    transform: translate(30px, -30px);
}

.summary-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 24px rgba(0, 0, 0, 0.15);
}

.present-card {
    border-left-color: #1a365d;
}

.absent-card {
    border-left-color: #000;
}

.late-card {
    border-left-color: #6c757d;
}

.total-card {
    border-left-color: #1a365d;
}

.summary-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    margin-bottom: 1rem;
}

.present-card .summary-icon {
    background: rgba(26, 54, 93, 0.1);
    color: #1a365d;
}

.absent-card .summary-icon {
    background: rgba(0, 0, 0, 0.1);
    color: #000;
}

.late-card .summary-icon {
    background: rgba(108, 117, 125, 0.1);
    color: #6c757d;
}

.total-card .summary-icon {
    background: rgba(26, 54, 93, 0.1);
    color: #1a365d;
}

.summary-number {
    font-size: 2rem;
    font-weight: 700;
    color: #000;
    line-height: 1;
}

.summary-label {
    color: #6c757d;
    font-weight: 500;
    margin: 0.25rem 0;
}

.summary-percentage {
    color: #495057;
    font-size: 0.875rem;
    font-weight: 600;
}

.action-buttons {
    display: flex;
    gap: 0.75rem;
    flex-wrap: wrap;
}

.action-buttons .btn {
    border-radius: 10px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.action-buttons .btn:hover {
    transform: translateY(-2px);
}

.custom-table {
    border-radius: 10px;
    overflow: hidden;
}

.custom-table thead th {
    background: #f8f9fa;
    border: none;
    font-weight: 600;
    color: #000;
    padding: 1rem;
}

.custom-table tbody tr {
    transition: all 0.3s ease;
}

.custom-table tbody tr:hover {
    background: rgba(26, 54, 93, 0.05);
}

.student-number {
    background: #1a365d;
    color: white;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 0.875rem;
}

.student-info {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.student-avatar {
    width: 40px;
    height: 40px;
    background: #1a365d;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
}

.student-name {
    font-weight: 600;
    color: #000;
}

.student-email {
    font-size: 0.875rem;
    color: #6c757d;
}

.student-id {
    background: #f8f9fa;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-family: monospace;
    font-size: 0.875rem;
    color: #495057;
}

.status-select {
    border-radius: 10px;
    border: 2px solid #e9ecef;
    font-weight: 500;
    transition: all 0.3s ease;
}

.status-select:focus {
    border-color: #1a365d;
    box-shadow: 0 0 0 0.2rem rgba(26, 54, 93, 0.25);
}

.quick-btn {
    border-radius: 8px;
    width: 35px;
    height: 35px;
    padding: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.quick-btn:hover {
    transform: scale(1.1);
}

.save-btn {
    border-radius: 10px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.save-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(26, 54, 93, 0.3);
}

.empty-state {
    padding: 3rem 0;
}

.empty-icon {
    font-size: 4rem;
    color: #dee2e6;
    margin-bottom: 1.5rem;
}

.custom-alert {
    border-radius: 10px;
    border: none;
    animation: slideIn 0.5s ease-out;
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@media (max-width: 768px) {
    .page-header {
        flex-direction: column;
        text-align: center;
        gap: 0.5rem;
    }
    
    .action-buttons {
        justify-content: center;
    }
    
    .quick-status-buttons {
        display: flex;
        gap: 0.25rem;
    }
    
    .quick-btn {
        width: 30px;
        height: 30px;
        font-size: 0.75rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selects = document.querySelectorAll('.status-select');
    
    function updateCounts() {
        let present = 0, absent = 0, late = 0;
        const total = selects.length;
        
        selects.forEach(s => {
            const value = s.value;
            if (value === 'present') present++;
            else if (value === 'absent') absent++;
            else if (value === 'late') late++;
        });
        
        // Update summary cards
        updateSummaryCard('present', present, total);
        updateSummaryCard('absent', absent, total);
        updateSummaryCard('late', late, total);
        updateSummaryCard('total', total, total);
        
        // Update save info
        const attendanceRate = total > 0 ? Math.round((present / total) * 100) : 0;
        const saveInfo = document.querySelector('.save-info p');
        if (saveInfo) {
            saveInfo.innerHTML = `<i class="fas fa-info-circle me-1"></i>${total} students marked. Attendance rate: ${attendanceRate}%`;
        }
    }
    
    function updateSummaryCard(type, count, total) {
        const card = document.querySelector(`.${type}-card`);
        if (card) {
            const number = card.querySelector('.summary-number');
            const percentage = card.querySelector('.summary-percentage');
            
            if (number) number.textContent = count;
            if (percentage) {
                const percent = total > 0 ? Math.round((count / total) * 100) : 0;
                percentage.textContent = percent + '%';
            }
        }
    }
    
    function setStudentStatus(studentId, status) {
        const select = document.querySelector(`select[data-student-id="${studentId}"]`);
        if (select) {
            select.value = status;
            updateCounts();
            
            // Add visual feedback
            const row = select.closest('tr');
            row.style.animation = 'pulse 0.5s ease';
            setTimeout(() => row.style.animation = '', 500);
        }
    }
    
    function markAll(status) {
        selects.forEach(s => {
            s.value = status;
        });
        updateCounts();
        
        // Visual feedback
        document.querySelectorAll('.student-row').forEach((row, index) => {
            setTimeout(() => {
                row.style.animation = 'pulse 0.5s ease';
                setTimeout(() => row.style.animation = '', 500);
            }, index * 50);
        });
    }
    
    function resetAll() {
        selects.forEach(s => {
            s.value = 'present';
        });
        updateCounts();
    }
    
    // Add event listeners
    selects.forEach(s => {
        s.addEventListener('change', updateCounts);
    });
    
    // Initial count update
    updateCounts();
    
    // Add pulse animation
    const style = document.createElement('style');
    style.textContent = `
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.02); }
            100% { transform: scale(1); }
        }
    `;
    document.head.appendChild(style);
    
    // Make functions global
    window.setStudentStatus = setStudentStatus;
    window.markAll = markAll;
    window.resetAll = resetAll;
});
</script>
@endif

@endsection