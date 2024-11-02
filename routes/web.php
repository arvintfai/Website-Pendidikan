<?php

use App\Models\SubjectMatter;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::get('/', function () {
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
});

require __DIR__ . '/auth.php';
