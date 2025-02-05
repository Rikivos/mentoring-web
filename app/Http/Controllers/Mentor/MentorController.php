<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
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

        $folderPath = 'modules';

        if (!Storage::disk('public')->exists($folderPath)) {
            Storage::disk('public')->makeDirectory($folderPath);
        }

        $fileName = null;
        if ($request->hasFile('file_path')) {
            $file = $request->file('file_path');
            $fileName = $file->getClientOriginalName();

            $file->storeAs($folderPath, $fileName, 'public');
        }

        $module = Module::create([
            'module_title' => $request->module_title,
            'content' => $request->content,
            'course_id' => $request->course_id,
            'file_path' => $fileName,
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

        try {
            $module = Module::findOrFail($id);

            $module->module_title = $validatedData['module_title'];
            $module->content = $validatedData['content'];

            if ($request->hasFile('file_path')) {
                $folderPath = 'modules';

                if (!Storage::disk('public')->exists($folderPath)) {
                    Storage::disk('public')->makeDirectory($folderPath);
                }

                if ($module->file_path) {
                    Storage::disk('public')->delete($folderPath . '/' . $module->file_path);
                }

                $file = $request->file('file_path');
                $fileName = $file->getClientOriginalName();

                $file->storeAs($folderPath, $fileName, 'public');

                $module->file_path = $fileName;
            }

            $module->save();

            return redirect()->back()->with('success', 'Module updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function downloadByFileName($fileName)
    {
        $module = Module::where('file_path', $fileName)->first();

        if (!$module) {
            return response()->json(['error' => 'File not found'], 404);
        }

        $fullFilePath = 'modules/' . $module->file_path;

        if (Storage::disk('public')->exists($fullFilePath)) {
            return Response::download(storage_path('app/public/' . $fullFilePath));
        }

        return response()->json(['error' => 'File not found'], 404);
    }
}
