<?php

namespace App\Http\Controllers\Mentee;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class AssignmentController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'file' => 'required|file|mimes:pdf,doc,docx,zip|max:2048',
            'assignment_date' => 'required|date',
            'task_id' => 'required|exists:tasks,task_id',
            'user_id' => 'required|exists:users,id',
        ]);

        if (!Storage::disk('public')->exists('assignments')) {
            Storage::disk('public')->makeDirectory('assignments');
        }

        $path = $request->file('file')->store('assignments', 'public');

        $validatedData['file'] = $path;


        $assignment = Assignment::create($validatedData);

        return response()->json([
            'message' => 'Assignment created successfully!',
            'assignment' => $assignment,
        ], 201);
    }

    public function edit(Request $request, $id)
    {
        $assignment = Assignment::findOrFail($id);

        $validatedData = $request->validate([
            'file' => 'sometimes|file|mimes:pdf,doc,docx,zip|max:2048',
            'assignment_date' => 'sometimes|date',
            'task_id' => 'sometimes|exists:tasks,task_id',
            'user_id' => 'sometimes|exists:users,id',
        ]);

        if ($request->hasFile('file')) {
            if ($assignment->file && Storage::disk('public')->exists($assignment->file)) {
                Storage::disk('public')->delete($assignment->file);
            }

            $path = $request->file('file')->store('assignments', 'public');
            $validatedData['file'] = $path;
        }

        $assignment->update($validatedData);

        return response()->json([
            'message' => 'Assignment updated successfully!',
            'assignment' => $assignment,
        ], 200);
    }

    public function getAssignmentByTaskAndUser($task_id)
    {
        $user_id = Auth::id();

        $assignment = Assignment::where('task_id', $task_id)
            ->where('user_id', $user_id)
            ->first();

        if (!$assignment) {
            return response()->json([
                'message' => 'Assignment not found',
            ], 404);
        }

        return response()->json([
            'message' => 'Assignment details retrieved successfully',
            'assignment' => $assignment,
        ], 200);
    }
}
