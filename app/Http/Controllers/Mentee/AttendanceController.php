<?php

namespace App\Http\Controllers\Mentee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AttendanceUser;
use App\Models\Attendance;
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

        // Get the logged-in user's ID
        // $userId = auth()->id();

        // Cek user enrolled in the course
        // $isEnrolled = $user->courses()->whereHas('modules.attendances', function ($query) use ($attendanceId) {
        //     $query->where('attendance_id', $attendanceId);
        // })->exists();

        // if (!$isEnrolled) {
        //     return response()->json(['message' => 'You are not enrolled in this course.'], 403);
        // }

        if (!$attendance) {
            return response()->json([
                'message' => 'Attendance record not found.',
            ], 404);
        }

        if (Carbon::now()->gt($attendance->deadline)) {
            return response()->json([
                'message' => 'Cannot create attendance_user, the deadline has passed.',
            ], 400);
        }

        $attendanceUser = AttendanceUser::create([
            'attendance_id' => $request->attendance_id,
            'user_id' => $request->user_id,
            'status' => $request->status,
        ]);

        return response()->json([
            'message' => 'Attendance user created successfully.',
            'data' => $attendanceUser,
        ], 201);
    }
}
