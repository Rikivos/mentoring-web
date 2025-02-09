<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Carbon\Carbon;
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

            $deadline = Carbon::parse($validatedData['deadline'])->format('Y-m-d H:i:s');

            $task = Task::create([
                'title' => $validatedData['title'],
                'file' => $fileName,
                'description' => $validatedData['description'],
                'deadline' => $deadline,
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

        if ($request->filled('deadline')) {
            try {
                $validatedData['deadline'] = Carbon::parse($request->deadline)->format('Y-m-d H:i:s');
            } catch (\Exception $e) {
                return response()->json(['error' => 'Invalid deadline format'], 422);
            }
        }

        $folderPath = 'tasks';
        $fileName = $task->file;

        if ($request->hasFile('file')) {
            if ($task->file && Storage::disk('public')->exists("$folderPath/$task->file")) {
                Storage::disk('public')->delete("$folderPath/$task->file");
            }

            $file = $request->file('file');
            $fileName = $file->getClientOriginalName();

            $file->storeAs($folderPath, $fileName, 'public');
        }

        $task->update([
            'title' => $validatedData['title'] ?? $task->title,
            'file' => $fileName,
            'description' => $validatedData['description'] ?? $task->description,
            'deadline' => $validatedData['deadline'] ?? $task->deadline,
        ]);

        return redirect()->back()->with('success', 'Task updated successfully!');
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
