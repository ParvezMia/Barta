<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

/**
 * Auth
 */
Route::prefix('/')->group(function () {
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/', [AuthController::class, 'index'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('store.login');
    Route::get('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/register/store', [AuthController::class, 'store'])->name('register.store');
});

/**
 * Dashboard
 */

 Route::prefix('/dashboard')->middleware(['auth'])->group(function () {
    Route::get('/',  [DashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [DashboardController::class, 'profile'])->name('dashboard.profile');
    Route::get('/profile/edit', [DashboardController::class, 'edit'])->name('edit.profile');
    Route::put('/profile/edit', [DashboardController::class, 'update'])->name('profile.update');
});
