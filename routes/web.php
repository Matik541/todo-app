<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\FulfillController;
use App\Http\Controllers\GoogleCalendarController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TaskHistoryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Spatie\GoogleCalendar\GoogleCalendar;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('/tasks', TaskController::class);

    Route::post('/tasks/{task}/share', [TaskController::class, 'generateShareLink'])->name('tasks.generate_share_link');

    // Route::get('/google-calendar/auth', function () {
    //     $googleCalendar = app(GoogleCalendar::class);
    //     return redirect($googleCalendar->createAuthUrl());
    // })->name('google-calendar.auth');

    // Route::get('/oauth/google/calendar', function (Illuminate\Http\Request $request) {
    //     $googleCalendar = app(GoogleCalendar::class);
    //     $googleCalendar->fetchAccessToken($request->code);
    //     return redirect()->route('dashboard')->with('success', 'Google Calendar został pomyślnie połączony!');
    // });
});

Route::get('/tasks/share/{token}', [TaskController::class, 'showSharedTask'])->name('tasks.share');

Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware(['auth'])->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function () {
    $request = fulfill();
    return view('/dashboard');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function () {
    $request->user()->sendEmailVerificationNotification();

    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

require __DIR__ . '/auth.php';
