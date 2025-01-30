<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseUser;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;


class LogbookController extends Controller
{
    // Logbook by course
    public function indexByCourse()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        if (!in_array($user->role, ['petugas', 'mentor'])) {
            return redirect()->route('notMentor');
        }

        $courseUsers = CourseUser::where('user_id', $user->id)->get();

        if ($courseUsers->isEmpty()) {
            return view('mentor.emptyCourse');
        }

        $course = Course::where('mentor_id', $user->id)->first();

        if (!$course) {
            return view('mentor.emptyCourse');
        }

        $reports = Report::where('course_id', $course->course_id)->get();

        return view('mentor.logbook', compact('course', 'reports'));
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
            'image' => 'nullable',
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

        if ($request->has('image')) {
            $base64Image = $request->input('image');
            $image = str_replace('data:image/jpeg;base64,', '', $base64Image);
            $image = str_replace(' ', '+', $image);
            $imageName = time() . '.jpg';
            File::put(public_path('uploads/') . $imageName, base64_decode($image));
            $imagePath = 'uploads/' . $imageName;
        }

        $report = new Report();
        $report->report_name = $request->input('report_name');
        $report->report_photo = $imagePath;
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

    // Delete logbook
    public function deleteReport($id)
    {
        $report = Report::findOrFail($id);
        $report->delete();

        return redirect()->route('logbook.show')->with('success', 'Laporan berhasil dihapus.');
    }
}
