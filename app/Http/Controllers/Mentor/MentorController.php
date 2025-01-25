<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MentorController extends Controller
{
    // Details course
    public function index($slug)
    {
        $course = Course::where('course_slug', $slug)->firstOrFail();
        $modules = Module::with(['tasks', 'attendances'])->where('course_id', $course->course_id)->get();

        return view('mentor.mentoring', compact('modules', 'course'));
    }

    // Add new module
    public function store(Request $request)
    {
        $request->validate([
            'module_title' => 'required|string|max:255',
            'content' => 'required|string',
            'course_id' => 'required|exists:courses,course_id',
            'file_path' => 'nullable|file',
        ]);

        $module = Module::create([
            'module_title' => $request->module_title,
            'content' => $request->content,
            'course_id' => $request->course_id,
            'file_path' => $request->file_path ? $request->file('file_path')->store('modules', 'public') : null,
        ]);

        return response()->json(['message' => 'Module successfully created.', 'data' => $module], 201);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'module_title' => 'required|string|max:255',
            'content' => 'required|string',
            'file_path' => 'nullable|file',
        ]);

        $module = Module::findOrFail($id);

        $module->module_title = $validatedData['module_title'];
        $module->content = $validatedData['content'];

        if ($request->hasFile('file')) {
            if ($module->file_path) {
                Storage::delete($module->file_path);
            }

            $path = $request->file('file')->store('modules');
            $module->file_path = $path;
        }

        $module->save();

        return response()->json([
            'message' => 'Module updated successfully!',
            'module' => $module,
        ], 200);
    }
}
