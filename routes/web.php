<?php

use App\Http\Controllers\Admin\DataMentorController as AdminDataMentorController;
use App\Http\Controllers\Admin\DataCourseController as AdminDataCourseController;
use App\Http\Controllers\Admin\AnnouncementController as AnnouncementController;
use App\Http\Controllers\Admin\DashboardAdminController as DashboardAdminController;
use App\Http\Controllers\Home\HomeController as HomeController;
use App\Http\Controllers\MyCourse\MyCourseController as MyCourseController;
use App\Http\Controllers\MyCourseMentor\MyCourseMentorController as MyCourseMentorController;
use App\Http\Controllers\Attendance\AttendanceController as AttendanceController;
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

//logbook



//Admin start

//Admin mentor
Route::get('/admin/mentor', [AdminDataMentorController::class, 'getMentor']);
Route::post('/admin/mentor/add', [AdminDataMentorController::class, 'addMentor'])->name('addMentor');
Route::post('/admin/mentor/edit-role', [AdminDataMentorController::class, 'editMentorRole'])->name('admin.mentor.editRole');
Route::post('/admin/mentor/destroy', [AdminDataMentorController::class, 'destroyMentor']);

//Admin course
Route::post('/admin/course/add', [AdminDataCourseController::class, 'storeCourse'])->name('store.course');
Route::post('/admin/course/update/{id}', [AdminDataCourseController::class, 'updateCourse']);
Route::delete('/admin/course/delete/{id}', [AdminDataCourseController::class, 'destroyCourse']);

Route::prefix('admin')->group(function () {
    Route::get('/dashboard', [DashboardAdminController::class, 'index'], 'admin.dashboard')->name('admin.dashboard');
    Route::get('/dashboard/{id}/download-pdf', [DashboardAdminController::class, 'downloadPdf'])->name('admin.dashboard.download-pdf');
    Route::get('/mentor', [AdminDataMentorController::class, 'getMentor'])->name('admin.mentor');
    Route::get('/class', [AdminDataCourseController::class, 'getAllCourse'])->name('admin.class');
    Route::get('/attendance', [AdminAttendanceController::class, 'index'])->name('admin.attendance');
    Route::get('/attendance/pdf/{id}', [AdminAttendanceController::class, 'generateRecapPDF'])->name('admin.attendance.pdf');
    Route::get('/report', [AdminLogbookController::class, 'index'])->name('admin.report');
    Route::post('/report/{id}/update', [AdminLogbookController::class, 'updateLogbook'])->name('admin.update.report');
});
//end admin

//mentor
Route::prefix('mentor')->group(function () {
    Route::get('/mentoring/{slug}', [MyCourseMentorController::class, 'index'])->name('mentor.mentoring');
    Route::get('/home',  [HomeController::class, 'index'])->name('courses.index');
    Route::post('/logbook', [LogbookController::class, 'add'])->name('logbook.add');
    Route::get('/logbook', [LogbookController::class, 'indexByCourse'])->name('logbook.show');
    Route::post('/module/store', [MyCourseMentorController::class, 'store'])->name('module.store');
});


//attendace
Route::post('/attendance', [AttendanceController::class, 'createAttendance'])->name('attendance.create');


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
