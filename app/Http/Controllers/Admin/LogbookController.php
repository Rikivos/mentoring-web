<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\User;
use App\Models\Course;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LogbookController extends Controller
{
    //get all logbook
    public function index()
    {
        $role = session('role');

        if ($role !== 'petugas') {
            return redirect()->route('dashboard');
        }

        $courses = Course::with('reports')->get();

        $courses->each(function ($course) {
            $course->reports->each(function ($report) {
                $report->start_time = Carbon::parse($report->start_time)->format('H:i');
                $report->end_time = Carbon::parse($report->end_time)->format('H:i');
            });
        });
        return view('admin.report', compact('courses'));
    }

    public function updateLogbook(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected',
            'comment' => 'nullable|string|max:255',
        ]);

        $report = Report::find($id);

        if (!$report) {
            return response()->json(['message' => 'Report not found.'], 404);
        }

        $report->status = $request->input('status');
        $report->comment = $request->input('comment', '');
        $report->save();

        return response()->json([
            'message' => 'Report updated successfully.',
            'data' => $report,
        ], 200);
    }
}
