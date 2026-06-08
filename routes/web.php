<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;

// Redirect home to dashboard
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Guest Routes
Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
});

// Public Ticket Submission (Accessible to everyone)
Route::get('submit-ticket', [\App\Http\Controllers\TicketController::class, 'showPublicForm'])->name('tickets.public-create');
Route::post('submit-ticket', [\App\Http\Controllers\TicketController::class, 'storePublicTicket'])->name('tickets.public-store');

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    // Shared routes for admin, it, user roles
    Route::middleware('role:admin,it,user')->group(function () {
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
        
        Route::get('tickets', [\App\Http\Controllers\TicketController::class, 'index'])->name('tickets.index');
        Route::get('tickets/create', [\App\Http\Controllers\TicketController::class, 'create'])->name('tickets.create');
        Route::post('tickets', [\App\Http\Controllers\TicketController::class, 'store'])->name('tickets.store');
        Route::post('tickets/{ticket}/status', [\App\Http\Controllers\TicketController::class, 'updateStatus'])->name('tickets.update-status');
        
        Route::get('settings', function () {
            return view('settings');
        })->name('settings');
    });

    // Admin and IT routes for managing email directory
    Route::middleware('role:admin,it')->group(function () {
        Route::get('users', [\App\Http\Controllers\UserController::class, 'index'])->name('users.index');
        Route::post('users', [\App\Http\Controllers\UserController::class, 'store'])->name('users.store');
        Route::post('users/{user}/toggle-status', [\App\Http\Controllers\UserController::class, 'toggleStatus'])->name('users.toggle-status');
    });

    // Admin only routes
    Route::middleware('role:admin')->group(function () {
        Route::delete('users/{user}', [\App\Http\Controllers\UserController::class, 'destroy'])->name('users.destroy');

        // Master Data CRUD routes
        Route::resource('departments', \App\Http\Controllers\DepartmentController::class)->except(['create', 'show', 'edit']);
        Route::resource('locations', \App\Http\Controllers\LocationController::class)->except(['create', 'show', 'edit']);
        Route::resource('categories', \App\Http\Controllers\CategoryController::class)->except(['create', 'show', 'edit']);
        Route::resource('sub-categories', \App\Http\Controllers\SubCategoryController::class)->except(['create', 'show', 'edit']);
    });
});
