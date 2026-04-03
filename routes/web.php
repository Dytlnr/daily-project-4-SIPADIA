<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AlumniController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::post('/admin/import', [AdminController::class, 'import'])->name('admin.import');
    Route::get('/admin/alumni/{alumni}', [AdminController::class, 'show'])->name('admin.alumni.show');
    Route::post('/admin/alumni/{alumni}', [AdminController::class, 'update'])->name('admin.alumni.update');
    Route::get('/alumni/dashboard', [AlumniController::class, 'dashboard'])->name('alumni.dashboard');
    Route::post('/alumni/update', [AlumniController::class, 'update'])->name('alumni.update');
});
