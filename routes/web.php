<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\Course;
use App\Models\User;
use App\Models\QuizAttempt;
use App\Http\Controllers\Student\CourseController as StudentCourseController;
use App\Http\Controllers\Student\LessonController as StudentLessonController;
use App\Http\Controllers\Teacher\CourseController as TeacherCourseController;
use App\Http\Controllers\Teacher\LessonController as TeacherLessonController;
use App\Http\Controllers\Student\QuizController as StudentQuizController;
use App\Http\Controllers\Student\EnrollmentController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Teacher\AnalyticsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public landing page
Route::get('/', function () {
    return view('welcome');
});

// Main dashboard redirect
Route::get('/dashboard', function () {
    $user = Auth::user();
    if ($user->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }
    if ($user->role === 'teacher') {
        return redirect()->route('teacher.dashboard');
    }
    return redirect()->route('student.dashboard');
})->middleware(['auth'])->name('dashboard');

// --- ROLE-SPECIFIC ROUTES ---

// Admin routes
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
    
    Route::get('/dashboard', function () { 
        $studentCount = User::where('role', 'student')->count();
        $teacherCount = User::where('role', 'teacher')->count();
        $courseCount = Course::count();
        
        return view('admin.dashboard', [
            'studentCount' => $studentCount,
            'teacherCount' => $teacherCount,
            'courseCount' => $courseCount
        ]);
    })->name('dashboard');

    // Routes for User Management
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create-teacher', [UserController::class, 'createTeacher'])->name('users.create-teacher');
    Route::post('/users/store-teacher', [UserController::class, 'storeTeacher'])->name('users.store-teacher');
    // Changed edit/update routes to a single delete route
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
});

// Teacher routes
Route::middleware(['auth', 'role:teacher'])
    ->prefix('teacher')
    ->name('teacher.')
    ->group(function () {
    
    Route::get('/dashboard', function () {
        $courses = Course::where('teacher_id', Auth::id())->get();
        return view('teacher.dashboard', ['courses' => $courses]);
    })->name('dashboard');

    Route::resource('courses', TeacherCourseController::class);
    Route::resource('lessons', TeacherLessonController::class);
    
    Route::get('/courses/{course}/analytics', [AnalyticsController::class, 'show'])->name('courses.analytics');
});


// Student routes
Route::middleware(['auth', 'role:student'])->group(function () {
    Route::get('/student/dashboard', function () {
        
        $user = Auth::user();
        
        // Enrollment Logic
        $enrolledCourseIds = $user->courses()->pluck('courses.id');
        $enrolledCourses = Course::whereIn('id', $enrolledCourseIds)->get();
        $availableCourses = Course::whereNotIn('id', $enrolledCourseIds)->get();

        // --- Corrected Revision Plan Logic ---
        $attemptedQuizIds = QuizAttempt::where('user_id', Auth::id())
            ->pluck('quiz_id')
            ->unique();

        $revisionLessons = collect();

        foreach ($attemptedQuizIds as $quizId) {
            $latestAttempt = QuizAttempt::where('user_id', Auth::id())
                ->where('quiz_id', $quizId)
                ->latest()
                ->first();

            if ($latestAttempt && $latestAttempt->score < 70) {
                $latestAttempt->load('quiz.lesson'); 
                if ($latestAttempt->quiz && $latestAttempt->quiz->lesson) {
                    $revisionLessons->push($latestAttempt->quiz->lesson);
                }
            }
        }
        
        return view('student.dashboard', [
            'enrolledCourses' => $enrolledCourses,
            'availableCourses' => $availableCourses,
            'revisionLessons' => $revisionLessons->unique('id')->take(5),
        ]);
    })->name('student.dashboard');

    Route::post('/courses/{course}/enroll', [EnrollmentController::class, 'enroll'])->name('student.courses.enroll');
    Route::get('/courses/{course}', [StudentCourseController::class, 'show'])->name('student.courses.show');
    Route::get('/lessons/{lesson}', [StudentLessonController::class, 'show'])->name('student.lessons.show');
    Route::get('/quizzes/{quiz}/take', [StudentQuizController::class, 'take'])->name('student.quizzes.take');
    Route::post('/quizzes/{quiz}/submit', [StudentQuizController::class, 'submit'])->name('student.quizzes.submit');
    Route::get('/quiz-attempts/{attempt}/results', [StudentQuizController::class, 'results'])->name('student.quizzes.results');
});

// Profile routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [App\Http\Controllers\ProfileController::class, 'destroy'])->name('profile.destroy');
});


require __DIR__.'/auth.php';