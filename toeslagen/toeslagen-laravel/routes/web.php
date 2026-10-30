<?php

use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AuthController;
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
