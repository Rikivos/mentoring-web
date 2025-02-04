<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SubmissionController extends Controller
{
    public function index($task_id)
    {
        try {
            $user = Auth::user();

            if ($user->role == 'mentor') {
                $assignments = Assignment::where('task_id', $task_id)->get();

                return view('mentor.assignment', compact('assignments'));
            } else {
                return redirect()->route('notMentor');
            }
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function download($assignment_id)
    {
        try {
            $assignment = Assignment::findOrFail($assignment_id);

            $filePath = $assignment->file;

            if (!Storage::disk('public')->exists($filePath)) {
                return redirect()->back()->with('error', 'File not found!');
            }

            $fullPath = Storage::disk('public')->path($filePath);

            if (file_exists($filePath)) {
                return response()->download($filePath);
            }
            return response()->download($fullPath);
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
}
