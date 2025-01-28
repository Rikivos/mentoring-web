<?php

namespace App\Http\Controllers\Mentee;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class AssignmentController extends Controller
{
    public function store(Request $request, $task_id)
    {
        $validatedData = $request->validate([
            'file' => 'required|file|mimes:pdf,doc,docx,zip|max:2048',
        ]);

        $validatedData['assignment_date'] = now();

        $user = Auth::user();
        $validatedData['user_id'] = $user->id;

        $validatedData['task_id'] = $task_id;

        $folderPath = 'assignments';
        if (!Storage::disk('public')->exists($folderPath)) {
            Storage::disk('public')->makeDirectory($folderPath);
        }

        $file = $request->file('file');
        $filename = $file->getClientOriginalName();
        $path = $file->storeAs($folderPath, $filename, 'public');

        $validatedData['file'] = $path;

        $assignment = Assignment::create($validatedData);

        return redirect()->route('mentee.task', ['task_id' => $task_id])->with('message', 'Assignment created successfully!');
    }

    public function edit(Request $request, $assignment_id)
    {
        $validatedData = $request->validate([
            'file' => 'required|file|mimes:pdf,doc,docx,zip|max:2048',
        ]);

        $assignment = Assignment::findOrFail($assignment_id);

        if (Storage::disk('public')->exists($assignment->file)) {
            Storage::disk('public')->delete($assignment->file);
        }

        $file = $request->file('file');
        $folderPath = 'assignments';
        $filename = $file->getClientOriginalName();
        $path = $file->storeAs($folderPath, $filename, 'public');

        $assignment->file = $path;
        $assignment->assignment_date = now();
        $assignment->save();

        return redirect()->route('mentee.task', ['task_id' => $assignment->task_id])
            ->with('message', 'Assignment updated successfully!');
    }


    public function getAssignmentByTaskAndUser($task_id)
    {
        $user = Auth::user();

        $task = Task::findOrFail($task_id);

        $opened = Carbon::parse($task->created_at)->format('l, d F Y, g:i A');
        $deadline = Carbon::parse($task->deadline)->format('l, d F Y, g:i A');

        return view('mentee.taskSubmit', compact('opened', 'deadline', 'task'));
    }
}
