<?php

namespace App\Http\Controllers\Mentee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AttendanceUser;
use App\Models\Attendance;
use App\Models\Course;
use App\Models\CourseUser;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function showByModule($module_id)
    {
        try {
            $attendance = Attendance::with(['module', 'attendanceUsers'])
                ->where('module_id', $module_id)
                ->first();

            if (!$attendance) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Attendance not found for the given module ID',
                ], 404);
            }

            $user = Auth::user();

            $userAttendance = $attendance->attendanceUsers->firstWhere('user_id', $user->id);
            $status = $userAttendance->status ?? null;

            // Format attendance_open and deadline
            $formattedDate = \Carbon\Carbon::parse($attendance->attendance_open)->format('D, d F Y');
            $formattedTime = \Carbon\Carbon::parse($attendance->attendance_open)->format('g:i A') . ' - ' .
                \Carbon\Carbon::parse($attendance->deadline)->format('g:i A');
            $attendanceDetails = $formattedDate . "\n" . $formattedTime;

            return view('mentee.presence', compact('attendance', 'status', 'attendanceDetails'));
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'attendance_id' => 'required|exists:attendances,attendance_id',
            'user_id' => 'required|exists:users,id',
            'status' => 'required|in:hadir,tidak hadir,izin',
        ]);

        $attendance = Attendance::find($request->attendance_id);

        if (!$attendance) {
            return redirect()->back()->with('error', 'Attendance record not found.');
        }

        if (!$attendance->module) {
            return redirect()->back()->with('error', 'Module not found for this attendance.');
        }

        $course = $attendance->module->course;

        if (!$course) {
            return redirect()->back()->with('error', 'Course not found for this module.');
        }

        $user = Auth::user();

        $isEnrolled = CourseUser::where('user_id', $user->id)
            ->where('course_id', $course->course_id)
            ->exists();

        if (!$isEnrolled) {
            return redirect()->back()->with('error', 'You are not enrolled in this course.');
        }

        $now = Carbon::now('Asia/Jakarta');
        $deadline = Carbon::createFromFormat('Y-m-d H:i:s', $attendance->deadline, 'Asia/Jakarta')->startOfSecond();
        $attendanceOpen = Carbon::createFromFormat('Y-m-d H:i:s', $attendance->attendance_open, 'Asia/Jakarta')->startOfSecond();

        if ($now->lt($attendanceOpen)) {
            return redirect()->back()->with('error', 'Attendance is not open yet.');
        }

        if ($now->gt($deadline)) {
            $attendanceUser = AttendanceUser::create([
                'attendance_id' => $request->attendance_id,
                'user_id' => $request->user_id,
                'status' => 'tidak hadir',
            ]);

            return redirect()->back()->with('success', 'Attendance deadline has passed.');
        }

        $attendanceUser = AttendanceUser::create([
            'attendance_id' => $request->attendance_id,
            'user_id' => $request->user_id,
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Attendance created successfully.');
    }
}
