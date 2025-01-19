<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class TaskController extends Controller
{
    //Add new task
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'file' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            'description' => 'required|string',
            'deadline' => 'required|date|after:now',
            'module_id' => 'required|exists:modules,module_id',
        ]);

        $filePath = $request->file('file')->store('tasks');

        $task = Task::create([
            'file' => $filePath,
            'description' => $validatedData['description'],
            'deadline' => $validatedData['deadline'],
            'module_id' => $validatedData['module_id'],
        ]);

        return response()->json([
            'message' => 'Task created successfully!',
            'task' => $task,
        ], 201);
    }

    //Update task
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'file' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            'description' => 'nullable|string',
            'deadline' => 'nullable|date|after:now',
        ]);

        $task = Task::findOrFail($id);

        if ($request->has('description')) {
            $task->description = $validatedData['description'];
        }

        if ($request->has('deadline')) {
            $task->deadline = $validatedData['deadline'];
        }

        if ($request->hasFile('file')) {
            if ($task->file) {
                Storage::delete($task->file);
            }

            $filePath = $request->file('file')->store('tasks');
            $task->file = $filePath;
        }

        $task->save();

        return response()->json([
            'message' => 'Task updated successfully!',
            'task' => $task,
        ], 200);
    }
}
