<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\User;
use App\Models\Course;
use Illuminate\Http\Request;


class LogbookController extends Controller
{
    //get all logbook
    public function index()
    {
        $courses = Course::with('reports')->get();

        return response()->json($courses);
        // return view('admin.report', compact('courses'));
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
