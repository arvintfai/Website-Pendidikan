<?php

use App\Models\SubjectMatter;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\Front\QuizController;
use App\Http\Controllers\Front\SubjectMatterController;
use App\Http\Controllers\Front\WorkResultController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentQuizController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

// Route::prefix('')->group(function () {
//     Route::get('/', function () {
//         return view('front.index');
//     });

//     Route::prefix('subjectMatter')->group(function () {

//         Route::get('{slug}', [SubjectMatterController::class, 'index'])->name('subjectMatterView');
//         Route::post('upload', [SubjectMatterController::class, 'store'])->name('subjectMatterUpload');
//         Route::put('{id}', [SubjectMatterController::class, 'update'])->name('subjectMatterUpdate');
//     });

//     Route::prefix('/Quiz')->group(function () {

//         Route::post('/', [QuizController::class, 'index'])->name('QuizIndex');
//     });
// });



// Halaman utama
Route::prefix('/')->group(function () {
    Route::get('', function () {
        return view('front.index');
    })->name('index');

    Route::prefix('works')->group(function () {
        Route::get('', [WorkResultController::class, 'index'])->name('works');
    });

    Route::get('work/{slug}', [WorkResultController::class, 'show'])->name('workShow');
});


Route::get('/test', function () {
    return view('welcome');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/admin/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/admin/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/admin/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/users/{id}/send-verification', [EmailController::class, 'send'])->name('users.sendVerification');
    Route::post('/profile/upload-avatar', [ProfileController::class, 'uploadAvatar'])->name('profile.uploadAvatar');
    Route::delete('/profile/destory-avatar', [ProfileController::class, 'destroyAvatar'])->name('profile.destroyAvatar');
    // Route::get('/quiz/{quiz}', [StudentQuizController::class, 'show'])->name('student.quiz.show');
    Route::post('/quiz/{quiz}', [StudentQuizController::class, 'store'])->name('student.quiz.store');

    //Front

    // Routes untuk Subject Matter
    Route::prefix('subject-matter')->group(function () {
        Route::get('{slug}', [SubjectMatterController::class, 'index'])->name('subjectMatterView');
        Route::post('upload', [SubjectMatterController::class, 'store'])->name('subjectMatterUpload');
        Route::put('{id}', [SubjectMatterController::class, 'update'])->name('subjectMatterUpdate');
    });

    // Routes untuk Quiz
    Route::prefix('quiz')->group(function () {
        Route::post('/', [QuizController::class, 'index'])->name('quizIndex');
        Route::get('/', [QuizController::class, 'show'])->name('quizShow');
    });

    Route::prefix('work')->group(function () {
        Route::post('store', [WorkResultController::class, 'store'])->name('workStore');
        Route::delete('delete/{slug}', [WorkResultController::class, 'destroy'])->name('workDestroy');
    });
});

require __DIR__ . '/auth.php';
