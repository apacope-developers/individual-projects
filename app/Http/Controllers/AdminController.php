<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function dashboard()
    {
        // Get statistics
        $totalTeachers = User::where('role', 'teacher')->count();
        $totalStudents = User::where('role', 'student')->count();
        $totalAttendanceRecords = Attendance::count();
        $todayAttendance = Attendance::whereDate('date', now()->toDateString())->count();

        // Get recent attendance activities
        $recentAttendances = Attendance::with(['student', 'teacher'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return view('admin.dashboard', compact(
            'totalTeachers',
            'totalStudents', 
            'totalAttendanceRecords',
            'todayAttendance',
            'recentAttendances'
        ));
    }

    public function teachers()
    {
        $teachers = User::where('role', 'teacher')
            ->withCount(['teacherAttendances as attendance_count'])
            ->orderBy('name')
            ->paginate(15);

        return view('admin.teachers', compact('teachers'));
    }

    
    public function students()
    {
        $students = User::where('role', 'student')
            ->withCount(['studentAttendances as attendance_count'])
            ->orderBy('name')
            ->paginate(15);

        return view('admin.students', compact('students'));
    }

    public function attendance()
    {
        $date = request('date', now()->toDateString());
        $classroom = request('classroom');
        
        $query = Attendance::with(['student', 'teacher'])
            ->whereDate('date', $date);

        if ($classroom) {
            $query->where('classroom', $classroom);
        }

        $attendances = $query->orderBy('created_at', 'desc')->paginate(20);

        // Get available classrooms
        $classrooms = User::where('role', 'student')
            ->whereNotNull('classroom')
            ->distinct()
            ->pluck('classroom')
            ->sort();

        return view('admin.attendance', compact('attendances', 'date', 'classrooms', 'classroom'));
    }

    public function clearAllAttendance()
    {
        try {
            // Delete all attendance records (PostgreSQL-compatible)
            $deletedCount = Attendance::count();
            Attendance::query()->delete();
            
            return response()->json([
                'success' => true,
                'message' => "Successfully deleted {$deletedCount} attendance records."
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error clearing attendance records: ' . $e->getMessage()
            ], 500);
        }
    }

    public function createTeacher()
    {
        // Get available classrooms
        $classrooms = User::where('role', 'student')
            ->whereNotNull('classroom')
            ->distinct()
            ->pluck('classroom')
            ->sort();
        
        return view('admin.teachers-create', compact('classrooms'));
    }

    public function storeTeacher(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'classroom' => 'required|string',
        ]);

        try {
            User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'role' => 'teacher',
                'classroom' => $data['classroom'],
            ]);

            return redirect()->route('admin.teachers')
                ->with('success', 'Teacher created successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error creating teacher: ' . $e->getMessage());
        }
    }

    public function createStudent()
    {
        // Get available classrooms
        $classrooms = User::where('role', 'teacher')
            ->whereNotNull('classroom')
            ->distinct()
            ->pluck('classroom')
            ->sort();
        
        return view('admin.students-create', compact('classrooms'));
    }

    public function storeStudent(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'classroom' => 'required|string',
        ]);

        try {
            User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'role' => 'student',
                'classroom' => $data['classroom'],
            ]);

            return redirect()->route('admin.students')
                ->with('success', 'Student created successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error creating student: ' . $e->getMessage());
        }
    }
}
