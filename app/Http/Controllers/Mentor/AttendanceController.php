<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Http\Request;
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


    public function updateAttendance(Request $request, $attendance_id)
    {
        $validator = Validator::make($request->all(), [
            'attendance_open' => 'required|date',
            'deadline' => 'required|date|after:attendance_open',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $attendance = Attendance::find($attendance_id);

            if (!$attendance) {
                return response()->json([
                    'success' => false,
                    'message' => 'Attendance not found',
                ], 404);
            }

            $attendance->attendance_open = $request->attendance_open;
            $attendance->deadline = $request->deadline;
            $attendance->save();

            return response()->json([
                'success' => true,
                'message' => 'Attendance updated successfully',
                'data' => $attendance,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating attendance',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
