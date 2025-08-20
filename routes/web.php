<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProjectController;

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

Route::get('/', function () { return redirect()->route('dashboard'); });

// Auth pages
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.attempt');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected area (requires auth)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    
    // Project settings route
    Route::get('/project/settings', [App\Http\Controllers\ProjectController::class, 'settings'])->name('project.settings');
    
    // Projects resource routes
    Route::resource('projects', App\Http\Controllers\ProjectController::class);
    Route::post('/projects/set-current', [App\Http\Controllers\ProjectController::class, 'setCurrentProject'])->name('projects.set-current');
    Route::get('/projects/get-user-projects', [App\Http\Controllers\ProjectController::class, 'getUserProjects'])->name('projects.get-user-projects');
});
