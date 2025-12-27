<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CvController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminDashboardController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::middleware('role:user')->group(function () {

        Route::get('/dashboard', function () {
            return view('dashboard');
        })->name('dashboard');

        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

        Route::get('/cv', [CvController::class, 'create'])->name('cv.create');
        Route::post('/cv', [CvController::class, 'store'])->name('cv.store');
        Route::get('/cv/{analysis}/result', [CvController::class, 'result'])->name('cv.result');
        Route::get('/cv/history', [CvController::class, 'history'])->name('cv.history');
        Route::delete('/cv/{analysis}/history', [CvController::class, 'destroyHistory'])->name('cv.history.delete');
        Route::post('/cv/{submission}/push-to-admin', [CvController::class, 'submitToAdmin'])
            ->name('cv.push-to-admin');
});

Route::prefix('admin')
->name('admin.')
        ->middleware('role:admin')
        ->group(function () {

            Route::get('/dashboard', [AdminDashboardController::class, 'index'])
                ->name('dashboard');

            Route::get('/cv/{analysis}', [CvController::class, 'result'])
                ->name('cv.result');

            Route::get('/cv/compare', [AdminController::class, 'compareForm'])
                ->name('cv.compare.form');

            Route::post('/cv/compare', [AdminController::class, 'compareResult'])
                ->name('cv.compare');
        });
    });