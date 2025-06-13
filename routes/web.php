<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\admin\TeacherController;
use App\Http\Controllers\admin\StudentController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\admin\PasswordController;
use App\Http\Controllers\admin\AdminController;
use App\Http\Controllers\admin\ExamController;
use App\Http\Controllers\admin\ExamResultController;
use App\Http\Controllers\admin\SubjectController;
use App\Http\Controllers\admin\ClassroomController;
use App\Http\Controllers\admin\QuestionController;
use App\Http\Controllers\NotificationController;

// Default Route
Route::get('/', function () {
    return redirect()->route('login');
})->middleware('guest');

// Role-Based Routes
Route::middleware('auth')->group(function () {
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/change-password', [PasswordController::class, 'showChangeForm'])->name('password.change');
        Route::post('/change-password', [PasswordController::class, 'update'])->name('password.update');
        Route::get('/profile/edit', [DashboardController::class, 'edit'])->name('profile.edit');
        Route::post('/profile/update', [DashboardController::class, 'update'])->name('profile.update');
        Route::get('/users', [AdminController::class, 'index'])->name('users.index')->middleware('role:admin');
        Route::get('/users/create', [AdminController::class, 'create'])->name('users.create')->middleware('role:admin');
        Route::post('/users', [AdminController::class, 'store'])->name('users.store')->middleware('role:admin');
        Route::get('/users/{user}/edit', [AdminController::class, 'edit'])->name('users.edit')->middleware('role:admin');
        Route::put('/users/{user}', [AdminController::class, 'update'])->name('users.update')->middleware('role:admin');
        Route::delete('/users/{user}', [AdminController::class, 'destroy'])->name('users.destroy')->middleware('role:admin');
        Route::post('/users/bulk-update-status', [AdminController::class, 'bulkUpdateStatus'])->name('users.bulk-update-status')->middleware('role:admin');

        Route::get('/exams', [ExamController::class, 'index'])->name('exams.index')->middleware('role:admin');
        Route::post('/student/exams/{exam}/save-answer', [ExamController::class, 'saveAnswer'])->name('student.exams.save-answer');
        Route::get('/exams/create', [ExamController::class, 'create'])->name('exams.create')->middleware('role:admin');
        Route::post('/exams', [ExamController::class, 'store'])->name('exams.store')->middleware('role:admin');
        Route::get('/exams/{exam}/edit', [ExamController::class, 'edit'])->name('exams.edit')->middleware('role:admin');
        Route::put('/exams/{exam}', [ExamController::class, 'update'])->name('exams.update')->middleware('role:admin');
        Route::delete('/exams/{exam}', [ExamController::class, 'destroy'])->name('exams.destroy')->middleware('role:admin');
        Route::post('/exams/bulk-update-status', [ExamController::class, 'bulkUpdateStatus'])->name('exams.bulk-update-status')->middleware('role:admin');
        Route::get('/exams/{exam}/instructions', [ExamController::class, 'instructions'])->name('exams.instructions')->middleware('role:admin');
        Route::post('/exams/{exam}/instructions', [ExamController::class, 'updateInstructions'])->name('exams.update-instructions')->middleware('role:admin');

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

        Route::get('/questions', [QuestionController::class, 'index'])->name('questions.index')->middleware('role:admin');
        Route::get('/questions/create', [QuestionController::class, 'create'])->name('questions.create')->middleware('role:admin');
        Route::post('/questions', [QuestionController::class, 'store'])->name('questions.store')->middleware('role:admin');
        Route::get('/questions/{question}/edit', [QuestionController::class, 'edit'])->name('questions.edit')->middleware('role:admin');
        Route::put('/questions/{question}', [QuestionController::class, 'update'])->name('questions.update')->middleware('role:admin');
        Route::delete('/questions/{question}', [QuestionController::class, 'destroy'])->name('questions.destroy')->middleware('role:admin');
        Route::get('/questions/bulk-upload', [QuestionController::class, 'bulkUpload'])->name('questions.bulk-upload')->middleware('role:admin');
        Route::post('/questions/bulk-store', [QuestionController::class, 'bulkStore'])->name('questions.bulk-store')->middleware('role:admin');
        Route::get('/questions/download-template', [QuestionController::class, 'downloadTemplate'])->name('questions.download-template')->middleware('role:admin');

        Route::get('/results', [ExamResultController::class, 'index'])->name('results.index');
        Route::post('/results/{result}/publish', [ExamResultController::class, 'publish'])->name('results.publish');
        Route::post('/results/{result}/allow-answers', [ExamResultController::class, 'allow_answers'])->name('results.allow_answers');
        Route::get('/results/export/{format}', [ExamResultController::class, 'export'])->name('results.export');
        Route::get('/results/{result}/edit', [ExamResultController::class, 'edit'])->name('results.edit');
        Route::put('/results/{result}', [ExamResultController::class, 'update'])->name('results.update');
    });

        Route::prefix('teacher')->name('teacher.')->group(function () {
            Route::get('/dashboard', [TeacherController::class, 'dashboard'])->name('dashboard')->middleware('role:teacher');
            Route::get('/profile', [TeacherController::class, 'profile'])->name('profile')->middleware('role:teacher');
            Route::put('/profile', [TeacherController::class, 'updateProfile'])->name('profile.update')->middleware('role:teacher');
    });

    Route::prefix('student')->name('student.')->group(function () {
        Route::get('/dashboard', [StudentController::class, 'index'])->name('dashboard')->middleware('role:student');
        Route::post('/exams/{exam}/save-answer', [StudentController::class, 'saveAnswer'])->name('exams.save-answer')->middleware('role:student'); // Fixed name and path
        Route::get('/profile', [StudentController::class, 'profile'])->name('profile')->middleware('role:student');
        Route::put('/profile', [StudentController::class, 'updateProfile'])->name('profile.update')->middleware('role:student');
        Route::get('/exams', [StudentController::class, 'exams'])->name('exams')->middleware('role:student');
        Route::get('/exams/{exam}/instructions', [StudentController::class, 'instructions'])->name('exams.instructions')->middleware('role:student');
        Route::get('/exams/{exam}/start', [StudentController::class, 'startExam'])->name('exams.start')->middleware('role:student');
        Route::post('/exams/{exam}/submit', [StudentController::class, 'submitExam'])->name('exams.submit')->middleware('role:student');
        Route::get('/results', [StudentController::class, 'results'])->name('results')->middleware('role:student');
        Route::get('/thank-you', [StudentController::class, 'thankYou'])->name('thank-you')->middleware('role:student');
        Route::get('/results/{examResult}', [StudentController::class, 'showResult'])->name('results.show')->middleware('role:student');
        Route::get('/results/{examResult}/answers', [StudentController::class, 'showAnswers'])->name('results.answers')->middleware('role:student');
        });
    });

        Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login')->middleware('guest')->defaults('type', 'user');
        Route::post('/login', [LoginController::class, 'login'])->name('user.login.submit')->defaults('type', 'user');
        Route::post('/logout', [LoginController::class, 'logout'])->name('user.logout')->defaults('type', 'user');
        Route::get('/admin/login', [LoginController::class, 'showLoginForm'])->name('admin.login')->defaults('type', 'admin');
        Route::post('/admin/login', [LoginController::class, 'login'])->name('admin.login.submit')->defaults('type', 'admin');
        Route::post('/admin/logout', [LoginController::class, 'logout'])->name('admin.logout')->defaults('type', 'admin');
        // Register Route
        Route::get('/register', [RegisterController::class, 'showForm'])->name('register.form');
        Route::post('/register', [RegisterController::class, 'register'])->name('register.submit');
        // Password Reset Routes
        Route::get('forgot-password', [ForgotPasswordController::class, 'showForgotForm'])->name('password.request');
        Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLink'])->name('password.email');
        Route::get('reset-password/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
        Route::post('reset-password', [ForgotPasswordController::class, 'resetPassword'])->name('password.ResetNew');

        Route::post('/notifications/{id}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
        Route::post('/notifications/clear', [NotificationController::class, 'clear'])->name('notifications.clear');
