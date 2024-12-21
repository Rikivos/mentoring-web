<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LogbookController extends Controller
{
    // Display all Logbook


    // Logbook by course
    public function indexByCourse()
    {
        $user = Auth::user();

        if (empty($user)) {
            return redirect()->route('login');
        }

        if (!in_array($user->role, ['admin', 'mentor'])) {
            return redirect()->route('courses.index');
        }

        $courses = Course::where('mentor_id', $user->id)->firstOrFail();

        $reports = Report::where('user_id', $user->id)
            ->where('course_id', $courses->course_id)
            ->get();

        $data = [
            'mentor_id' => $user->id,
            'course_id' => $courses->course_id,
            'reports' => $reports
        ];

        return view('logbook', compact('data'));
    }


    // Add logbook
    public function add(Request $request)
    {
        $validated = $request->validate([
            'report_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'upload_date' => 'required|date',
        ]);

        $user = Auth::user();

        if (!in_array($user->role, ['admin', 'mentor'])) {
            return redirect()->route('courses.index');
        }

        $course = Course::where('mentor_id', $user->id)->firstOrFail();

        $start_time = $request->upload_date . ' ' . $request->start_time . ':00';
        $end_time = $request->upload_date . ' ' . $request->end_time . ':00';

        $start_time = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $start_time);
        $end_time = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $end_time);

        $report = new Report();
        $report->report_name = $request->input('report_name');
        $report->report_photo = 'default_photo.jpg'; // Ganti dengan logika upload foto jika ada
        $report->description = $request->input('description');
        $report->start_time = $start_time;
        $report->end_time = $end_time;
        $report->upload_date = $request->input('upload_date');
        $report->course_id = $course->course_id;
        $report->user_id = $user->id;
        $report->status = 'pending';
        $report->save();

        return redirect()->route('logbook.show')->with('success', 'Logbook berhasil ditambahkan');
    }

    // Edit logbook


    // 
}