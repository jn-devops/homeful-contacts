<?php

use App\Http\Controllers\{AddressController, AIFController, CoBorrowerController, CoBorrowerEmploymentController, EmploymentController, PersonalController, ProfileController, SpouseController, SpouseEmploymentController};
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

//Route::get('/spouse', function () {
//    return Inertia::render('Spouse/Edit');
//})->middleware(['auth', 'verified'])->name('spouse');

//Route::get('/aif', function () {
//    return Inertia::render('AIF/Edit');
//})->middleware(['auth', 'verified'])->name('aif');

//Route::get('/co-borrower', function () {
//    return Inertia::render('CoBorrower/Edit');
//})->middleware(['auth', 'verified'])->name('co-borrower');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/personal', [PersonalController::class, 'edit'])->name('personal.edit');
    Route::patch('/personal', [PersonalController::class, 'update'])->name('personal.update');

    Route::get('/address', [AddressController::class, 'edit'])->name('personal.edit');
    Route::patch('/address', [AddressController::class, 'update'])->name('address.update');

    Route::get('/employment', [EmploymentController::class, 'edit'])->name('personal.edit');
    Route::patch('/employment', [EmploymentController::class, 'update'])->name('employment.update');

    Route::get('/spouse', [SpouseController::class, 'edit'])->name('spouse.edit');
    Route::patch('/spouse', [SpouseController::class, 'update'])->name('spouse.update');

    Route::get('/spouse-employment', [SpouseEmploymentController::class, 'edit'])->name('spouse-employment.edit');
    Route::patch('/spouse-employment', [SpouseEmploymentController::class, 'update'])->name('spouse-employment.update');

    Route::get('/co_borrower', [CoBorrowerController::class, 'edit'])->name('co_borrower.edit');
    Route::patch('/co_borrower', [CoBorrowerController::class, 'update'])->name('co_borrower.update');

    Route::get('/co_borrower-employment', [CoBorrowerEmploymentController::class, 'edit'])->name('co_borrower-employment.edit');
    Route::patch('/co_borrower-employment', [CoBorrowerEmploymentController::class, 'update'])->name('co_borrower-employment.update');

    Route::get('/aif', [AIFController::class, 'edit'])->name('aif.edit');
    Route::patch('/aif', [AIFController::class, 'update'])->name('aif.update');
});


require __DIR__.'/auth.php';
