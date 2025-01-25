<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class DashboardAdminController extends Controller
{
    public function index()
    {
        $role = session('role');

        if ($role !== 'petugas') {
            return redirect()->route('dashboard');
        }

        $totalClasses = Course::count();
        $totalMentees = User::where('role', 'mente')->count();
        $totalMentors = User::where('role', 'mentor')->count();

        $courses = Course::with(['mentor', 'users' => function ($query) {
            $query->where('role', 'mente');
        }])
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

    public function downloadPdf($id)
    {
        $course = Course::with(['mentor', 'users' => function ($query) {
            $query->where('role', 'mente');
        }])->findOrFail($id);

        $data = [
            'course_name' => $course->course_title,
            'mentor_name' => $course->mentor ? $course->mentor->name : 'No mentor assigned',
            'participants_count' => $course->users->count(),
            'participants' => $course->users,
        ];

        $pdf = PDF::loadView('pdf.dataMente', $data);

        return $pdf->download('Data Mahasiswa mentoring ' . $course->course_title . '.pdf');
    }
}
