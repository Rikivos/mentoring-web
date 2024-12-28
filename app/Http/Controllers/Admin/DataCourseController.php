<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DataCourseController extends Controller
{
    //get all Course
    public function getAllCourse()
    {
        //mentors
        $mentors = User::where('role', 'mentor')->with('courses')->get();

        //Course
        $courses = Course::with(['mentor'])
            ->withCount('users')
            ->get();

        return view('admin.class', compact('mentors', 'courses'));
    }


    //Add course
    public function storeCourse(Request $request)
    {
        $validated = $request->validate([
            'course_title' => 'required|string|max:255',
            'mentor_id' => 'required|exists:users,id',
        ]);

        $mentor = User::where('id', $validated['mentor_id'])->where('role', 'mentor')->first();

        if (!$mentor) {
            return back()->withErrors(['error' => 'Mentor dengan ID tersebut tidak ditemukan atau bukan seorang mentor.']);
        }

        $course_slug = Str::slug($validated['course_title'], '-');

        $course = Course::create([
            'course_title' => $validated['course_title'],
            'course_slug' => $course_slug,
            'mentor_id' => $mentor->id,
        ]);

        return redirect()->back()->with('success', 'Course berhasil ditambahkan!');
    }


    //Show Edit
    public function editCourse($id)
    {
        $course = Course::find($id);

        if (!$course) {
            return redirect()->route('courses.index')->with('error', 'Course tidak ditemukan.');
        }

        return view('admin.edit_course', compact('course'));
    }


    //Update Course
    public function updateCourse(Request $request, $id)
    {
        $validated = $request->validate([
            'course_title' => 'required|string|max:255',
            'mentor_nim' => 'required|string|exists:users,nim',
        ]);

        $course = Course::find($id);

        if (!$course) {
            return response()->json([
                'message' => 'Course tidak ditemukan.',
            ], 404);
        }

        $mentor = User::where('nim', $validated['mentor_nim'])->where('role', 'mentor')->first();

        if (!$mentor) {
            return response()->json([
                'message' => 'Mentor dengan NIM tersebut tidak ditemukan atau bukan seorang mentor.',
            ], 404);
        }

        $course->update([
            'course_title' => $validated['course_title'],
            'course_slug' => Str::slug($validated['course_title'], '-'),
            'mentor_id' => $mentor->id,
        ]);

        return response()->json([
            'message' => 'Course berhasil diperbarui!',
            'course' => $course,
        ]);
    }

    //Delete Course
    public function destroyCourse($id)
    {
        $course = Course::find($id);

        if (!$course) {
            return response()->json([
                'message' => 'Course tidak ditemukan.',
            ], 404);
        }

        $course->delete();

        return response()->json([
            'message' => 'Course berhasil dihapus.',
        ]);
    }
}
