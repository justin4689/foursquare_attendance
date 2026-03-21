<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\CulteController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\PointageController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AttendanceController::class, 'index'])->name('attendance.index');
Route::get('/attendance/search', [AttendanceController::class, 'search'])->name('attendance.search');
Route::get('/attendance/pointage', [AttendanceController::class, 'pointage'])->name('attendance.pointage');
Route::post('/attendance', [AttendanceController::class, 'store'])->name('attendance.store');

Route::post('/members/public', [MemberController::class, 'storePublic'])->name('members.store.public');

Route::get('/pointage', [PointageController::class, 'intelligent'])->name('pointage.intelligent');

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth'])->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('members', MemberController::class);
    Route::get('members/export/pdf', [MemberController::class, 'exportPDF'])->name('members.export.pdf');

    Route::resource('cultes', CulteController::class);
    Route::resource('categories', CategoryController::class);
    
    // Routes utilisateurs protégées par le middleware admin
    Route::middleware('admin')->group(function () {
        Route::resource('users', UserController::class);
    });

    Route::get('cultes/{culte}/pointage', [CulteController::class, 'pointage'])->name('cultes.pointage');
    Route::get('cultes/{culte}/presence', [CulteController::class, 'editPresence'])->name('cultes.presence.edit');
    Route::post('cultes/{culte}/presence', [CulteController::class, 'updatePresence'])->name('cultes.presence.update');

    Route::get('cultes/{culte}/pdf', [CulteController::class, 'generatePDF'])->name('cultes.pdf');
});

require __DIR__.'/auth.php';
