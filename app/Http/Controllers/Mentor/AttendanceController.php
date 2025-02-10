<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\AttendanceUser;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AttendanceController extends Controller
{
    //create attendaces
    public function createAttendance(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'module_id' => 'required|exists:modules,module_id',
            'title' => 'required|string',
            'attendance_open' => 'required|date_format:Y-m-d\TH:i',
            'deadline' => 'required|date_format:Y-m-d\TH:i|after:attendance_open',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $attendance_open = Carbon::createFromFormat('Y-m-d\TH:i', $request->attendance_open)->format('Y-m-d H:i:s');
            $deadline = Carbon::createFromFormat('Y-m-d\TH:i', $request->deadline)->format('Y-m-d H:i:s');

            $attendance = Attendance::create([
                'module_id' => $request->module_id,
                'title' => $request->title,
                'attendance_open' => $attendance_open,
                'deadline' => $deadline,
            ]);

            return redirect()->back()->with('success', 'Attendance created successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    // Update attendance
    public function updateAttendance(Request $request, $attendance_id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'attendance_open' => 'required|date',
            'deadline' => 'required|date|after:attendance_open',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $attendance = Attendance::find($attendance_id);

            if (!$attendance) {
                return redirect()->back()->with('error', 'Attendance record not found');
            }

            $attendance->attendance_open = $request->attendance_open;
            $attendance->deadline = $request->deadline;
            $attendance->save();

            return redirect()->back()->with('success', 'Attendance updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    // show attendance
    public function show($attendance_id)
    {
        try {
            $user = Auth::user();

            if ($user->role !== 'mentor') {
                return redirect()->route('notMentor');
            }

            $attendances = AttendanceUser::where('attendance_id', $attendance_id)
                ->with('user')
                ->get()
                ->map(function ($attendance) {
                    $attendance->formatted_date = Carbon::parse($attendance->created_at)->format('d M Y, H:i');
                    return $attendance;
                });

            if (!$attendances) {
                return redirect()->back()->with('error', 'Attendance record not found');
            }

            return view('mentor.checkPrecense', compact('attendances'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
