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
        try {
            $validatedData = $request->validate([
                'file' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
                'title' => 'required|string',
                'description' => 'nullable|string',
                'deadline' => 'required|date|after:now',
                'module_id' => 'required|exists:modules,module_id',
            ]);

            $folderPath = 'tasks';

            if (!Storage::disk('public')->exists($folderPath)) {
                Storage::disk('public')->makeDirectory($folderPath);
            }

            $fileName = null;
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $fileName = $file->getClientOriginalName();

                $file->storeAs($folderPath, $fileName, 'public');
            };

            $task = Task::create([
                'title' => $validatedData['title'],
                'file' => $fileName,
                'description' => $validatedData['description'],
                'deadline' => $validatedData['deadline'],
                'module_id' => $validatedData['module_id'],
            ]);

            return redirect()->back()->with('success', 'Task created successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    //Update task
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'title' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            'description' => 'nullable|string',
            'deadline' => 'nullable|date|after:now',
        ]);

        $task = Task::findOrFail($id);

        if ($request->has('title')) {
            $task->title = $validatedData['title'];
        }

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

    public function download($task_id)
    {
        $assignment = Task::findOrFail($task_id);

        $folderPath = 'tasks/';
        $fileName = $assignment->file;
        $filePath = $folderPath . $fileName;

        if (!Storage::disk('public')->exists($filePath)) {
            return redirect()->back()->with('error', 'File not found!');
        }

        $fullPath = Storage::disk('public')->path($filePath);

        // Return the download response
        return response()->download($fullPath);
    }
}
