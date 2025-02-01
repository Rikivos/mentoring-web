<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $today = Carbon::now();
        $nextWeek = Carbon::now()->addDays(7);

        // Ambil daftar course yang diikuti user
        $userCourses = DB::table('course_user')
            ->where('user_id', $userId)
            ->pluck('course_id');

        // Ambil daftar module_id yang terkait dengan course yang diikuti user
        $userModules = DB::table('modules')
            ->whereIn('course_id', $userCourses)
            ->pluck('module_id');

        // Ambil tasks berdasarkan module yang terkait dengan course yang diikuti user
        $tasks = DB::table('tasks')
            ->whereIn('module_id', $userModules)
            ->whereBetween('deadline', [$today, $nextWeek])
            ->select('title', 'deadline')
            ->get();

        // Ambil attendances berdasarkan module yang terkait dengan course yang diikuti user
        $attendances = DB::table('attendances')
            ->whereIn('module_id', $userModules)
            ->whereBetween('attendance_open', [$today, $nextWeek])
            ->select('title', 'attendance_open as start', 'deadline as end')
            ->get();

        $events = [];

        foreach ($tasks as $task) {
            $events[] = [
                'title' => 'Tugas: ' . $task->title,
                'start' => $task->deadline,
            ];
        }

        foreach ($attendances as $attendance) {
            $events[] = [
                'title' => 'Kehadiran: ' . $attendance->title,
                'start' => $attendance->start,
                'end' => $attendance->end,
            ];
        }

        // return response()->json($events);

        return view('mentee.dashboard', compact('events'));
    }
}
