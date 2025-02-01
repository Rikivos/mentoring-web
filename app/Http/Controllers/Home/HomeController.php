<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Course;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    // Method to display all courses with names and mentor names
    public function index()
    {
        $courses = Course::with('mentor:id,name')->get();
        $announcements = Announcement::orderBy('created_at', 'desc')->get();
        return view('mentee.home', compact('courses', 'announcements'));
    }

    // Method to search
    public function search($slug)
    {
        $course = Course::where('course_slug', $slug)->first();

        if (!$course) {
            return redirect()->back()->with('error', 'Kelas tidak ditemukan.');
        }

        return view('courses.show', compact('course'));
    }

    // Method to get all announcements
    public function getAnnouncements()
    {
        $announcements = Announcement::orderBy('created_at', 'desc')->get();

        return response()->json([
            'status' => 'success',
            'data' => $announcements,
        ], 200);
    }
}
