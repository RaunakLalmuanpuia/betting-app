<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PaymentCallbackController;


use App\Http\Controllers\Player\EventController as PlayerEventController;
use App\Http\Controllers\Player\BetController as PlayerBetController;


use App\Http\Controllers\Admin\EventController as AdminEventController;

Route::get('/', function () {
    return Inertia::render('Home');
});

//Auth
Route::group([], function () {
    Route::get('login', [AuthController::class, 'create'])->name('login');
    Route::get('forgot-password', [AuthController::class, 'forgotPassword'])->name('login.forgot');
    Route::post('forgot-password/send-otp', [AuthController::class, 'sendOtp'])->name('forgot.send');
    Route::post('forgot-password/verify-otp', [AuthController::class, 'verifyOtp'])->name('forgot.verify');
    Route::post('forgot-password/set-password', [AuthController::class, 'changePassword'])->name('forgot.password');
    Route::post('login', [AuthController::class, 'store'])->name('login.store');
    Route::delete('logout', [AuthController::class, 'destroy'])->name('login.destroy');
});

//Register
Route::group([], function () {
    Route::get('register', [RegisterController::class, 'create'])->name('register.create');
    Route::post('register/send-otp', [RegisterController::class, 'sendOtp'])->name('register.send-otp');
    Route::post('register/confirm-otp', [RegisterController::class, 'confirmOtp'])->name('register.confirm-otp');
});

// Profie
Route::group(['middleware'=>'auth'], function () {

    Route::get('edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('update', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('edit-password', [ProfileController::class, 'editPassword'])->name('profile.edit-password');
    Route::put('update-password', [ProfileController::class, 'updatePassword'])->name('profile.update-password');
});


//Dashboard
Route::group(['middleware'=>'auth'], function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

});

//Player Event
Route::group(['middleware' => 'auth', 'prefix' => 'player', 'as' => 'player.'], function () {
    Route::get('event', [PlayerEventController::class, 'index'])->name('events.index');
    Route::get('event/{event}/show', [PlayerEventController::class, 'show'])->name('events.show');
    Route::post('event/{event}/place-bet', [PlayerEventController::class, 'placeBet'])->name('events.place-bet');
    Route::post('event/payment/{orderId}/verify', [PlayerEventController::class, 'verifyPayment'])->name('events.payment.verify');
});

// Player Bets
Route::group(['middleware' => 'auth', 'prefix' => 'player', 'as' => 'player.'], function () {
    Route::get('bets', [PlayerBetController::class, 'index'])->name('bets.index');
});


//Payment Callback
Route::group(['prefix'=>'callback'], function () {
    Route::post('place-bet', [PaymentCallbackController::class, 'callback'])->name('callback.place-bet');
});

// Admin Events
Route::group(['middleware' => 'auth', 'prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::get('event', [AdminEventController::class, 'index'])->name('events.index');
    Route::get('event/create', [AdminEventController::class, 'create'])->name('events.create');
    Route::post('event/store', [AdminEventController::class, 'store'])->name('events.store');
    Route::get('event/{event}/edit', [AdminEventController::class, 'edit'])->name('events.edit');
    Route::post('event/{event}', [AdminEventController::class, 'update'])->name('events.update');
    Route::delete('events/{event}', [AdminEventController::class, 'destroy'])->name('events.destroy');
});




