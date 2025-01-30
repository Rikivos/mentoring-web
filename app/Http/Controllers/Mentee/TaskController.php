<?php

namespace App\Http\Controllers\Mentee;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\Assignment;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function show($task_id)
    {
        try {
            $task = Task::findOrFail($task_id);

            $opened = Carbon::parse($task->created_at)->format('l, d F Y, g:i A');
            $deadline = Carbon::parse($task->deadline)->format('l, d F Y, g:i A');

            $user = Auth::user();
            $submission = Assignment::where('task_id', $task_id)
                ->where('user_id', $user->id)
                ->first();

            $now = Carbon::now();
            if ($submission) {
                $submissionTime = Carbon::parse($submission->assignment_date);
                $deadline = Carbon::parse($task->deadline);

                // Check if the submission is before or after the deadline
                if ($submissionTime->lessThan($deadline)) {
                    $timeDifference = $submissionTime->diff($deadline);
                    $timeRemaining = 'Submitted ' . "{$timeDifference->d} days {$timeDifference->h} hours {$timeDifference->i} minutes early";
                    $lastModified = Carbon::parse($submission->updated_at)
                        ->setTimezone('Asia/Jakarta')
                        ->format('l, d F Y, g:i A');
                    $file = $submission->file;
                    $submission_id = $submission->assignment_id;
                } else {
                    $timeDifference = $deadline->diff($submissionTime);
                    $timeRemaining = 'Submitted ' . "{$timeDifference->d} days {$timeDifference->h} hours {$timeDifference->i} minutes late";
                    $lastModified = Carbon::parse($submission->updated_at)
                        ->setTimezone('Asia/Jakarta')
                        ->format('l, d F Y, g:i A');
                    $file = $submission->file;
                    $submission_id = $submission->assignment_id;
                }
                $submissionStatus = 'Submitted for grading';
            } else {
                if ($now->greaterThan(Carbon::parse($task->deadline))) {
                    $timeDifference = $now->diff(Carbon::parse($task->deadline));
                    $timeRemaining = "Late by {$timeDifference->d} days {$timeDifference->h} hours {$timeDifference->i} minutes";
                    $lastModified = null;
                    $file = null;
                    $submission_id = null;
                } else {
                    $timeDifference = $now->diff(Carbon::parse($task->deadline));
                    $timeRemaining = "{$timeDifference->d} days {$timeDifference->h} hours {$timeDifference->i} minutes remaining";
                    $lastModified = null;
                    $file = null;
                    $submission_id = null;
                }

                $submissionStatus = 'No submission has been made yet';
            }

            $gradingStatus = 'Not graded';

            // return response()->json([
            //     $submission_id
            // ]);

            return view('mentee.task', compact('task', 'opened', 'deadline', 'submissionStatus', 'gradingStatus', 'timeRemaining', 'lastModified', 'file', 'submission_id'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function download($assignment_id)
    {
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
    }
}
