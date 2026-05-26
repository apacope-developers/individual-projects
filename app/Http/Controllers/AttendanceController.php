<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\User;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $date = $request->input('date', now()->toDateString());
        $user = $request->user();
        
        // Get teacher's assigned classroom
        $classroom = $user->classroom;
        
        $students = collect();
        $attendances = collect();

        // Only show students if teacher has a classroom assigned
        if ($classroom) {
            $students = User::where('role', 'student')
                ->where('classroom', $classroom)
                ->orderBy('name')
                ->get();

            // Get existing attendance records for this date and classroom
            $attendances = Attendance::with(['student', 'teacher'])
                ->whereDate('date', $date)
                ->where('classroom', $classroom)
                ->get()
                ->keyBy('student_id');
        }

        return view('attendance.index', compact('date', 'classroom', 'students', 'attendances'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'date' => 'required|date',
            'statuses' => 'required_without:absent_count|array',
            'statuses.*' => 'in:present,absent,late',
            'absent_count' => 'nullable|integer|min:0',
            'absent_names' => 'nullable|string',
        ]);

        $teacher = $request->user();
        $teacherId = $teacher->id;
        $classroom = $teacher->classroom;

        $summary = null;
        if (isset($data['absent_count']) || isset($data['absent_names'])) {
            $summaryParts = [];

            if (isset($data['absent_count'])) {
                $summaryParts[] = 'Absent count: ' . $data['absent_count'];
            }

            if (! empty($data['absent_names'])) {
                $summaryParts[] = 'Names: ' . $data['absent_names'];
            }

            $summary = implode(' | ', $summaryParts);
        }

        $attendanceData = [
            'teacher_id' => $teacherId,
            'classroom' => $classroom,
        ];

        if ($summary !== null) {
            $attendanceData['note'] = $summary;
        }

        foreach ($data['statuses'] ?? [] as $studentId => $status) {
            // First try to find existing record using whereDate
            $existing = Attendance::whereDate('date', $data['date'])
                ->where('student_id', $studentId)
                ->first();
            
            if ($existing) {
                // Update existing record
                $existing->update(array_merge($attendanceData, ['status' => $status]));
            } else {
                // Create new record
                Attendance::create(array_merge(
                    ['student_id' => $studentId, 'date' => $data['date']],
                    $attendanceData,
                    ['status' => $status]
                ));
            }
        }

        return redirect()->route('attendance.index', ['date' => $data['date']])
            ->with('success', 'Attendance saved for ' . $classroom);
    }

    public function reports(Request $request)
    {
        $user = request()->user();
        $teacherId = $request->get('teacher_id');
        
        // Determine which teacher's data to show
        if ($user->role === 'admin' && $teacherId) {
            $teacher = User::where('role', 'teacher')->findOrFail($teacherId);
        } else {
            $teacher = $user;
        }
        
        $classroom = $teacher->classroom;
        
        // Get filter parameters
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $studentSearch = $request->get('student_search');
        
        // Build base query
        $query = Attendance::with(['student'])
            ->where('teacher_id', $teacher->id);
        
        // Apply date filters
        if ($startDate && $endDate) {
            $query->whereBetween('date', [$startDate, $endDate]);
        } else {
            // Default to last 30 days
            $query->whereDate('date', '>=', now()->subDays(30));
        }
        
        // Apply student search filter
        if ($studentSearch) {
            $query->whereHas('student', function($q) use ($studentSearch) {
                $q->where('name', 'like', '%' . $studentSearch . '%')
                  ->orWhere('email', 'like', '%' . $studentSearch . '%');
            });
        }
        
        // Get attendance records
        $attendances = $query->orderBy('date', 'desc')->paginate(20);
        
        // Get statistics
        $attendanceQuery = Attendance::where('teacher_id', $teacher->id);
        
        if ($startDate && $endDate) {
            $attendanceQuery->whereBetween('date', [$startDate, $endDate]);
        } else {
            $attendanceQuery->whereDate('date', '>=', now()->subDays(30));
        }
        
        $totalAttendance = $attendanceQuery->count();
        $presentCount = $attendanceQuery->where('status', 'present')->count();
        $absentCount = $attendanceQuery->where('status', 'absent')->count();
        $lateCount = $attendanceQuery->where('status', 'late')->count();
        
        // Get all teachers for admin sidebar
        $teachers = [];
        if ($user->role === 'admin') {
            $teachers = User::where('role', 'teacher')
                ->orderBy('name')
                ->get();
        }
        
        return view('attendance.reports', compact(
            'teacher', 'classroom', 'totalAttendance', 
            'presentCount', 'absentCount', 'lateCount',
            'attendances', 'teachers'
        ));
    }
}
