<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\ClassroomController;

// Default Route
Route::get('/', function () {
    return view('welcome');
});

// Register Route
Route::get('/register', [RegisterController::class, 'showForm'])->name('register.form');
Route::post('/register', [RegisterController::class, 'register'])->name('register.submit');

// Login Route
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Dashboard Route
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware('auth');

// Forgot Password Route
Route::get('forgot-password', [ForgotPasswordController::class, 'showForgotForm'])->name('password.request');
Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLink'])->name('password.email');

// Reset-Password Route
Route::get('reset-password/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('reset-password', [ForgotPasswordController::class, 'resetPassword'])->name('password.ResetNew');

// User Edit Profile Route
Route::get('/profile/edit', [DashboardController::class, 'edit'])->name('profile.edit');
Route::post('/profile/update', [DashboardController::class, 'update'])->name('profile.update');

// User Change Password Route
Route::middleware(['auth'])->group(function () {
    Route::get('/change-password', [PasswordController::class, 'showChangeForm'])->name('password.change');
    Route::post('/change-password', [PasswordController::class, 'update'])->name('password.update');
});

// Role-Based Routes
Route::middleware('auth')->group(function () {
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/users', [AdminController::class, 'index'])->name('users.index')->middleware('role:admin');
        Route::get('/users/create', [AdminController::class, 'create'])->name('users.create')->middleware('role:admin');
        Route::post('/users', [AdminController::class, 'store'])->name('users.store')->middleware('role:admin');
        Route::get('/users/{user}/edit', [AdminController::class, 'edit'])->name('users.edit')->middleware('role:admin');
        Route::put('/users/{user}', [AdminController::class, 'update'])->name('users.update')->middleware('role:admin');
        Route::delete('/users/{user}', [AdminController::class, 'destroy'])->name('users.destroy')->middleware('role:admin');
        Route::post('/users/bulk-update-status', [AdminController::class, 'bulkUpdateStatus'])
            ->name('users.bulk-update-status')->middleware('role:admin');

        Route::get('/exams', [ExamController::class, 'index'])->name('exams.index')->middleware('role:admin');
        Route::get('/exams/create', [ExamController::class, 'create'])->name('exams.create')->middleware('role:admin');
        Route::post('/exams', [ExamController::class, 'store'])->name('exams.store')->middleware('role:admin');
        Route::get('/exams/{exam}/edit', [ExamController::class, 'edit'])->name('exams.edit')->middleware('role:admin');
        Route::put('/exams/{exam}', [ExamController::class, 'update'])->name('exams.update')->middleware('role:admin');
        Route::delete('/exams/{exam}', [ExamController::class, 'destroy'])->name('exams.destroy')->middleware('role:admin');
        Route::post('/exams/bulk-update-status', [ExamController::class, 'bulkUpdateStatus'])
            ->name('exams.bulk-update-status')->middleware('role:admin');

        Route::get('/classrooms', [ClassroomController::class, 'index'])->name('classrooms.index')->middleware('role:admin');
        Route::get('/classrooms/create', [ClassroomController::class, 'create'])->name('classrooms.create')->middleware('role:admin');
        Route::post('/classrooms', [ClassroomController::class, 'store'])->name('classrooms.store')->middleware('role:admin');
        Route::get('/classrooms/{classroom}/edit', [ClassroomController::class, 'edit'])->name('classrooms.edit')->middleware('role:admin');
        Route::put('/classrooms/{classroom}', [ClassroomController::class, 'update'])->name('classrooms.update')->middleware('role:admin');
        Route::delete('/classrooms/{classroom}', [ClassroomController::class, 'destroy'])->name('classrooms.destroy')->middleware('role:admin');

        Route::get('/subjects', [SubjectController::class, 'index'])->name('subjects.index')->middleware('role:admin');
        Route::get('/subjects/create', [SubjectController::class, 'create'])->name('subjects.create')->middleware('role:admin');
        Route::post('/subjects', [SubjectController::class, 'store'])->name('subjects.store')->middleware('role:admin');
        Route::get('/subjects/{subject}/edit', [SubjectController::class, 'edit'])->name('subjects.edit')->middleware('role:admin');
        Route::put('/subjects/{subject}', [SubjectController::class, 'update'])->name('subjects.update')->middleware('role:admin');
        Route::delete('/subjects/{subject}', [SubjectController::class, 'destroy'])->name('subjects.destroy')->middleware('role:admin');
        Route::get('/subjects/assign', [SubjectController::class, 'assign'])->name('subjects.assign')->middleware('role:admin');
        Route::post('/subjects/assign', [SubjectController::class, 'storeAssignments'])->name('subjects.storeAssignments')->middleware('role:admin');
    });

    Route::prefix('teacher')->name('teacher.')->group(function () {
        Route::get('/dashboard', [TeacherController::class, 'dashboard'])->name('dashboard')->middleware('role:teacher');
        Route::get('/profile', [TeacherController::class, 'profile'])->name('profile')->middleware('role:teacher');
        Route::put('/profile', [TeacherController::class, 'updateProfile'])->name('profile.update')->middleware('role:teacher');
    });

    Route::prefix('student')->name('student.')->group(function () {
        Route::get('/dashboard', [StudentController::class, 'dashboard'])->name('dashboard')->middleware('role:student');
        Route::get('/profile', [StudentController::class, 'profile'])->name('profile')->middleware('role:student');
        Route::put('/profile', [StudentController::class, 'updateProfile'])->name('profile.update')->middleware('role:student');
    });
});