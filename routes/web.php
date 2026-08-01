<?php

use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ExchangeRequestController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserSkillController;
use App\Models\UserSkill;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/dashboard', function (\Illuminate\Http\Request $request) {
    $user = auth()->user();
    $search = trim((string) $request->get('q', ''));

    $feedQuery = UserSkill::with(['user', 'skill.category'])
        ->where('user_id', '!=', $user->id)
        ->latest();

    if ($search !== '') {
        $feedQuery->where(function ($q) use ($search) {
            $q->whereHas('user', function ($u) use ($search) {
                $u->where('name', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%");
            })->orWhereHas('skill', function ($s) use ($search) {
                $s->where('name', 'like', "%{$search}%");
            })->orWhereHas('skill.category', function ($c) use ($search) {
                $c->where('name', 'like', "%{$search}%");
            })->orWhere('description', 'like', "%{$search}%");
        });
    }

    $memberResults = collect();
    if ($search !== '') {
        $memberResults = \App\Models\User::where('id', '!=', $user->id)
            ->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%")
                    ->orWhereHas('userSkills.skill', fn ($s) => $s->where('name', 'like', "%{$search}%"));
            })
            ->with(['userSkills.skill'])
            ->orderBy('name')
            ->take(8)
            ->get();
    }

    return view('dashboard', [
        'offresCount' => $user->userSkills()->where('type', 'offre')->count(),
        'besoinsCount' => $user->userSkills()->where('type', 'besoin')->count(),
        'pendingReceived' => $user->receivedRequests()->where('status', 'en_attente')->count(),
        'pendingSent' => $user->sentRequests()->where('status', 'en_attente')->count(),
        'unreadMessages' => \App\Models\Message::whereHas('conversation', function ($q) use ($user) {
            $q->where(function ($inner) use ($user) {
                $inner->where('user_one_id', $user->id)->orWhere('user_two_id', $user->id);
            });
        })->where('user_id', '!=', $user->id)->whereNull('read_at')->count(),
        'recentReceived' => $user->receivedRequests()
            ->with(['sender', 'skill'])
            ->where('status', 'en_attente')
            ->latest()
            ->take(3)
            ->get(),
        'feed' => $feedQuery->take(20)->get(),
        'memberResults' => $memberResults,
        'search' => $search,
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/members', [MemberController::class, 'index'])->name('members.index');
    Route::get('/members/{user}', [MemberController::class, 'show'])->name('members.show');

    Route::get('/my-skills', [UserSkillController::class, 'index'])->name('user-skills.index');
    Route::post('/my-skills', [UserSkillController::class, 'store'])->name('user-skills.store');
    Route::delete('/my-skills/{userSkill}', [UserSkillController::class, 'destroy'])->name('user-skills.destroy');

    Route::post('/catalog/categories', [CatalogController::class, 'storeCategory'])->name('catalog.categories.store');
    Route::post('/catalog/skills', [CatalogController::class, 'storeSkill'])->name('catalog.skills.store');

    Route::get('/exchange-requests', [ExchangeRequestController::class, 'index'])->name('exchange-requests.index');
    Route::post('/exchange-requests', [ExchangeRequestController::class, 'store'])->name('exchange-requests.store');
    Route::patch('/exchange-requests/{exchangeRequest}', [ExchangeRequestController::class, 'update'])->name('exchange-requests.update');
    Route::delete('/exchange-requests/{exchangeRequest}', [ExchangeRequestController::class, 'destroy'])->name('exchange-requests.destroy');

    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::get('/chat/start/{user}', [ChatController::class, 'start'])->name('chat.start');
    Route::get('/chat/{conversation}', [ChatController::class, 'show'])->name('chat.show');
    Route::post('/chat/{conversation}', [ChatController::class, 'store'])->name('chat.store');
    Route::get('/chat/{conversation}/messages', [ChatController::class, 'messages'])->name('chat.messages');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.submit');

    Route::middleware('admin')->group(function () {
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

        Route::get('/users', [AdminDashboardController::class, 'users'])->name('users');
        Route::delete('/users/{user}', [AdminDashboardController::class, 'destroyUser'])->name('users.destroy');

        Route::get('/categories', [AdminDashboardController::class, 'categories'])->name('categories');
        Route::post('/categories', [AdminDashboardController::class, 'storeCategory'])->name('categories.store');
        Route::delete('/categories/{category}', [AdminDashboardController::class, 'destroyCategory'])->name('categories.destroy');

        Route::post('/skills', [AdminDashboardController::class, 'storeSkill'])->name('skills.store');
        Route::delete('/skills/{skill}', [AdminDashboardController::class, 'destroySkill'])->name('skills.destroy');

        Route::get('/requests', [AdminDashboardController::class, 'requests'])->name('requests');
        Route::delete('/requests/{exchangeRequest}', [AdminDashboardController::class, 'destroyRequest'])->name('requests.destroy');

        Route::get('/user-skills', [AdminDashboardController::class, 'userSkills'])->name('user-skills');
        Route::delete('/user-skills/{userSkill}', [AdminDashboardController::class, 'destroyUserSkill'])->name('user-skills.destroy');
    });
});

require __DIR__.'/auth.php';
