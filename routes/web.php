<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth'])->name('dashboard');

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
