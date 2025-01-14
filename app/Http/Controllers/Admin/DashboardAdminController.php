<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardAdminController extends Controller
{
    public function index()
    {
        $totalClasses = Course::count();
        $totalMentees = User::where('role', 'mente')->count();
        $totalMentors = User::where('role', 'mentor')->count();

        $courses = Course::with(['mentor', 'users'])
            ->get()
            ->map(function ($course) {
                return [
                    'id' => $course->course_id,
                    'name' => $course->course_title,
                    'mentor_name' => $course->mentor ? $course->mentor->name : 'No mentor assigned',
                    'participants_count' => $course->users->count(),
                ];
            });

        return view('admin.dashboard', compact('totalClasses', 'totalMentees', 'totalMentors', 'courses'));
    }
}
