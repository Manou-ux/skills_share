<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\MemberController;
use App\Http\Controllers\UserSkillController;
use App\Http\Controllers\ExchangeRequestController;

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
});

Route::middleware('auth')->group(function () {
    Route::get('/members', [MemberController::class, 'index'])->name('members.index');
    Route::get('/members/{user}', [MemberController::class, 'show'])->name('members.show');

    Route::get('/my-skills', [UserSkillController::class, 'index'])->name('user-skills.index');
    Route::post('/my-skills', [UserSkillController::class, 'store'])->name('user-skills.store');
    Route::delete('/my-skills/{userSkill}', [UserSkillController::class, 'destroy'])->name('user-skills.destroy');

    Route::get('/exchange-requests', [ExchangeRequestController::class, 'index'])->name('exchange-requests.index');
    Route::post('/exchange-requests', [ExchangeRequestController::class, 'store'])->name('exchange-requests.store');
    Route::patch('/exchange-requests/{exchangeRequest}', [ExchangeRequestController::class, 'update'])->name('exchange-requests.update');
    Route::delete('/exchange-requests/{exchangeRequest}', [ExchangeRequestController::class, 'destroy'])->name('exchange-requests.destroy');
});

require __DIR__.'/auth.php';
