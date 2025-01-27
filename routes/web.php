<?php

use App\Http\Controllers\Admin\DataMentorController as AdminDataMentorController;
use App\Http\Controllers\Admin\DataCourseController as AdminDataCourseController;
use App\Http\Controllers\Admin\AnnouncementController as AnnouncementController;
use App\Http\Controllers\Admin\DashboardAdminController as DashboardAdminController;
use App\Http\Controllers\Home\HomeController as HomeController;
use App\Http\Controllers\Mentee\MyCourseController as MyCourseController;
use App\Http\Controllers\Mentee\AttendanceController as MenteeAttendanceController;
use App\Http\Controllers\Mentor\MentorController as MentorController;
use App\Http\Controllers\Mentor\AttendanceController as AttendanceController;
use App\Http\Controllers\Mentor\TaskController as TaskController;
use App\Http\Controllers\Admin\AttendanceController as AdminAttendanceController;
use App\Http\Controllers\Admin\LogbookController as AdminLogbookController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\LogbookController;
use Illuminate\Support\Facades\Route;

//home
Route::get('/', [HomeController::class, 'index'])->name('courses.index');
Route::get('/announcement', [HomeController::class, 'getAnnouncements'])->name('announcement');
Route::get('/search/{slug}', [HomeController::class, 'search'])->name('search');

//auth
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

//Courses
Route::get('/courses/{slug}', [CourseController::class, 'search'])->name('courses.search');
Route::post('/courses/{slug}/enroll', [CourseController::class, 'enroll'])->name('courses.enroll');

//MyCourse
Route::get('/mycourse', [MyCourseController::class, 'index'])->name('mycourse');
Route::get('/mycourse/{slug}', [MyCourseController::class, 'showDetail'])->name('courses.show');
Route::get('/mycourse/participant/{slug}', [MyCourseController::class, 'showParticipant'])->name('participant');

//Enroll
Route::get('/enroll/{slug}', [CourseController::class, 'view'])->name('enroll');;
Route::post('/enroll/{slug}')->name('enroll.post');

//Task
Route::get('/task', function () {
    return view('mentee.task');
})->middleware('auth')->name('task');

Route::get('/task-submission', function () {
    return view('mentee.taskSubmit');
})->middleware('auth')->name('taskSubmit');

//Presence
Route::get('/presence/{module_id}', [MenteeAttendanceController::class, 'showByModule'])->middleware('auth')->name('presence');
Route::post('/presence/store', [MenteeAttendanceController::class, 'store'])->name('presence.store');

//admin
Route::prefix('admin')->middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardAdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/dashboard/{id}/download-pdf', [DashboardAdminController::class, 'downloadPdf'])->name('admin.dashboard.download-pdf');
    Route::prefix('mentor')->group(function () {
        Route::get('/', [AdminDataMentorController::class, 'getMentor'])->name('admin.mentor');
        Route::post('/add', [AdminDataMentorController::class, 'addMentor'])->name('addMentor');
        Route::post('/edit-role', [AdminDataMentorController::class, 'editMentorRole'])->name('admin.mentor.editRole');
        Route::post('/destroy', [AdminDataMentorController::class, 'destroyMentor']);
    });
    Route::prefix('course')->group(function () {
        Route::get('/', [AdminDataCourseController::class, 'getAllCourse'])->name('admin.class');
        Route::post('/add', [AdminDataCourseController::class, 'storeCourse'])->name('store.course');
        Route::post('/update/{id}', [AdminDataCourseController::class, 'updateCourse']);
        Route::delete('/delete/{id}', [AdminDataCourseController::class, 'destroyCourse']);
    });
    Route::prefix('attendance')->group(function () {
        Route::get('/', [AdminAttendanceController::class, 'index'])->name('admin.attendance');
        Route::get('/pdf/{id}', [AdminAttendanceController::class, 'generateRecapPDF'])->name('admin.attendance.pdf');
    });
    Route::prefix('report')->group(function () {
        Route::get('/', [AdminLogbookController::class, 'index'])->name('admin.report');
        Route::post('/{id}/update', [AdminLogbookController::class, 'updateLogbook'])->name('admin.update.report');
    });
});

//mentor
Route::prefix('mentor')->group(function () {
    Route::get('/mentoring/{slug}', [MentorController::class, 'index'])->name('mentor.mentoring');

    Route::post('/logbook', [LogbookController::class, 'add'])->name('logbook.add');
    Route::get('/logbook', [LogbookController::class, 'indexByCourse'])->name('logbook.show');

    Route::post('/module/store', [MentorController::class, 'store'])->name('module.store');
    Route::post('/module/{id}', [MentorController::class, 'update'])->name('module.update');

    Route::post('/attendance', [AttendanceController::class, 'createAttendance'])->name('attendance.create');
    Route::post('/attendance/{id}', [AttendanceController::class, 'updateAttendance'])->name('attendance.update');

    Route::post('/task', [TaskController::class, 'store'])->name('task.store');
    Route::post('/task/{id}', [TaskController::class, 'update'])->name('task.update');
});


//announcement
Route::post('/upload-announcement', [AnnouncementController::class, 'upload']);
Route::get('/download-announcement/{fileName}', [AnnouncementController::class, 'download']);

Route::get('/dashboard', function () {
    return view('mentee.dashboard');
})->middleware('auth')->name('dashboard');

Route::view('/not-mentor', 'mentee.notMentor')->name('notMentor');


Route::get('/get-csrf-token', function () {
    return csrf_token();
});
