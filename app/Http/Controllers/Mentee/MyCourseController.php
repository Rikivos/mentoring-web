<?php

namespace App\Http\Controllers\Mentee;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseUser;
use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MyCourseController extends Controller
{
    //Get my course
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        $courseUsers = CourseUser::where('user_id', $user->id)->get();

        if ($courseUsers->isEmpty()) {
            return view('mentee.emptyCourse');
        }

        $roles = $user->role;

        $courseId = $courseUsers->first()->course_id;
        $courses = Course::with('mentor:id,name')->whereIn('course_id', $courseUsers->pluck('course_id'))->get();

        return view('mentee.mycourse', compact('courses', 'roles'));
    }

    //Get detail course
    public function showDetail($slug)
    {
        $course = Course::where('course_slug', $slug)->firstOrFail();
        $modules = Module::with(['tasks', 'attendances'])->where('course_id', $course->course_id)->get();

        return view('mentee.mentoring', compact('course', 'modules'));
    }

    //Get participant
    public function showParticipant($slug)
    {
        $course = Course::where('course_slug', $slug)->firstOrFail();

        $user_course = CourseUser::where('course_id', $course->course_id)
            ->with('user')
            ->get();

        $user_course = $user_course->map(function ($courseUser) {
            return [
                'id' => $courseUser->id,
                'name' => $courseUser->user->name,
                'nim' => $courseUser->user->nim,
                'role' => $courseUser->user->role,
            ];
        });

        return view('mentee.participant', compact('user_course', 'course'));
    }
}
