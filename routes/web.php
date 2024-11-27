<?php

use App\Http\Controllers\{AddressController, PersonalController, ProfileController};
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

//Route::get('/personal', function () {
//    return Inertia::render('Personal/Edit');
//})->middleware(['auth', 'verified'])->name('personal');

Route::get('/spouse', function () {
    return Inertia::render('Spouse/Edit');
})->middleware(['auth', 'verified'])->name('spouse');

Route::get('/aif', function () {
    return Inertia::render('AIF/Edit');
})->middleware(['auth', 'verified'])->name('aif');

Route::get('/co-borrower', function () {
    return Inertia::render('Co-Borrower/Edit');
})->middleware(['auth', 'verified'])->name('co-borrower');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/personal', [PersonalController::class, 'edit'])->name('personal.edit');
    Route::patch('/personal', [PersonalController::class, 'update'])->name('personal.update');

    Route::get('/address', [AddressController::class, 'edit'])->name('personal.edit');
    Route::patch('/address', [AddressController::class, 'update'])->name('address.update');
});


require __DIR__.'/auth.php';
