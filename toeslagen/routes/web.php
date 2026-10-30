<?php

use App\Http\Controllers\AanvraagController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ToeslagController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => auth()->check() ? view('home') : redirect()->route('login'))->name('home');
Route::view('/rollen', 'rollen')->name('rollen');

Route::middleware('guest')->group(function () {
    Route::get('/inloggen', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/inloggen', [AuthController::class, 'login'])->middleware('throttle:10,1');
    Route::get('/registreren', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/registreren', [AuthController::class, 'register']);
});

Route::post('/uitloggen', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/gebruikers', [AdminUserController::class, 'index'])->name('users');
    Route::patch('/gebruikers/{user}/rol', [AdminUserController::class, 'updateRole'])->name('users.rol');
    Route::patch('/gebruikers/{user}/blokkeer', [AdminUserController::class, 'toggleBlock'])->name('users.block');
    Route::delete('/gebruikers/{user}', [AdminUserController::class, 'destroy'])->name('users.delete');
});

// Toeslagen en aanvragen (Ahmet)
Route::get('/toeslagen', [ToeslagController::class, 'index'])->name('toeslagen.index');

Route::middleware(['auth', 'role:burger'])->group(function () {
    Route::get('/toeslagen/{toeslag}/aanvragen', [AanvraagController::class, 'create'])->name('aanvragen.create');
    Route::post('/toeslagen/{toeslag}/aanvragen', [AanvraagController::class, 'store'])->name('aanvragen.store');
    Route::get('/mijn-aanvragen/{aanvraag}', [AanvraagController::class, 'show'])->name('aanvragen.show');
});
