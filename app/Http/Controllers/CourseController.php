<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Module;
use App\Models\CourseUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    // Method enroll view
    public function view($slug)
    {
        $user = Auth::user();

        if (empty($user)) {
            return redirect()->route('login');
        }

        $courses = Course::with('mentor:id,name')->where('course_slug', $slug)->firstOrFail();
        if (!$courses) {
            return response()->json('data tidak ditemukan.');
        }

        return view('mentee.enroll', compact('courses'));
    }

    // Method to add the authenticated user to a course
    public function enroll($slug)
    {
        $user = Auth::user();
        $course = Course::where('course_slug', $slug)->first();

        if (!$course) {
            return redirect()->back()->with('error', 'Course not found.');
        }

        $allreadyEnrolled = CourseUser::where('user_id', $user->id)->count();

        if ($allreadyEnrolled >= 1) {
            return redirect()->back()->with('error', 'You are already enrolled some course.');
        }

        $enrollUser = CourseUser::where('user_id', $user->id)
            ->where('course_id', $course->course_id)
            ->get();

        if ($enrollUser->isNotEmpty()) {
            return redirect()->back()->with('error', 'You are already enrolled in this course.');
        }

        CourseUser::create([
            'user_id' => $user->id,
            'course_id' => $course->course_id,
        ]);

        if ($user->role === 'mente') {
            return redirect()->route('courses.show', $slug)->with('message', 'Successfully enrolled in the course!');
        } else {
            return redirect()->route('mentor.mentoring', $slug)->with('message', 'Successfully enrolled in the course!');
        }
    }

    public function unenroll($slug)
    {
        try {
            $user = Auth::user();
            $course = Course::where('course_slug', $slug)->first();

            if (!$course) {
                return redirect()->back()->with('error', 'Course not found.');
            }

            $enrolledCourse = CourseUser::where('user_id', $user->id)
                ->where('course_id', $course->course_id)
                ->first();

            if (!$enrolledCourse) {
                return redirect()->back()->with('error', 'You are not enrolled in this course.');
            }

            $enrolledCourse->delete();

            return redirect()->route('courses.index')->with('message', 'Successfully unenrolled from the course.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while unenrolling: ' . $e->getMessage());
        }
    }
}
