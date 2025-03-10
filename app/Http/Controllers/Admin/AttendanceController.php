<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $role = session('role');

        $search = $request->input('search');

        if ($role === 'petugas') {
            $courses = Course::with(['mentor', 'users' => function ($query) {
                $query->where('role', 'mente');
            }])
                ->when($search, function ($query, $search) {
                    $query->where('course_title', 'like', '%' . $search . '%');
                })
                ->get();
        } elseif ($role === 'pembimbing') {
            $user = Auth::user();

            $courses = Course::where('pembimbing_id', $user->id)
                ->with(['mentor', 'users' => function ($query) {
                    $query->where('role', 'mente');
                }])
                ->when($search, function ($query, $search) {
                    $query->where('course_title', 'like', '%' . $search . '%');
                })
                ->get();
        } else {
            return redirect()->route('dashboard');
        }

        $courses = $courses->map(function ($course) {
            return [
                'id' => $course->course_id,
                'name' => $course->course_title,
                'mentor_name' => $course->mentor ? $course->mentor->name : 'No mentor assigned',
                'participants_count' => $course->users->count(),
            ];
        });

        return view('admin.attendance', compact('courses', 'search'));
    }


    public function generateRecapPDF($id)
    {
        $courseId = $id;

        $recap = DB::table('users as u')
            ->select(
                'u.id as user_id',
                'u.nim',
                'u.name',
                DB::raw('GROUP_CONCAT(au.status ORDER BY a.attendance_open ASC) as statuses'),
                DB::raw('SUM(CASE WHEN au.status = "hadir" THEN 1 ELSE 0 END) as total_hadir')
            )
            ->leftJoin('course_user as cu', 'u.id', '=', 'cu.user_id')
            ->leftJoin('courses as c', 'cu.course_id', '=', 'c.course_id')
            ->leftJoin('attendance_users as au', 'u.id', '=', 'au.user_id')
            ->leftJoin('attendances as a', 'au.attendance_id', '=', 'a.attendance_id')
            ->where('c.course_id', $courseId)
            ->where('u.role', 'mente')
            ->groupBy('u.id', 'u.nim', 'u.name')
            ->orderBy('u.name')
            ->get();

        $course = DB::table('courses as c')
            ->join('users as u', 'c.mentor_id', '=', 'u.id')
            ->where('c.course_id', $courseId)
            ->select('c.course_title', 'u.name as mentor_name')
            ->first();

        $pdf = Pdf::loadView('pdf.recap-pertemuan', [
            'recap' => $recap,
            'course' => $course,
        ]);

        return $pdf->download('recap pertemuan ' . $course->course_title . '.pdf');
    }
}
