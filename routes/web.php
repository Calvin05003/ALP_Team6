<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CvController;

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
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/cv', [CvController::class, 'create'])->name('cv.create');
    Route::post('/cv', [CvController::class, 'store'])->name('cv.store');
    Route::get('/cv/{id}/result', [CvController::class, 'result'])->name('cv.result');
    Route::get('/cv/history', [CvController::class, 'history'])
    ->name('cv.history')
    ->middleware('auth');

    Route::get('/cv/{id}/compare', [CvController::class, 'compareForm'])
    ->name('cv.compare.form')
    ->middleware('auth');

    Route::post('/cv/compare', [CvController::class, 'compare'])
    ->name('cv.compare')
    ->middleware('auth');


    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';