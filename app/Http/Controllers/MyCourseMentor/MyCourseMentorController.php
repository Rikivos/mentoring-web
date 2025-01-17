<?php

namespace App\Http\Controllers\MyCourseMentor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Module;
use Illuminate\Http\Request;

class MyCourseMentorController extends Controller
{
    // Get all modules by course slug
    public function index($slug)
    {
        $course = Course::where('course_slug', $slug)->firstOrFail();
        $modules = Module::where('course_id', $course->course_id)->get();

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
}
