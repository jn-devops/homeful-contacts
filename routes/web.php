<?php

use App\Http\Controllers\{AddressController, AIFController, CoBorrowerAddressController, CoBorrowerController, CoBorrowerEmploymentController, CoBorrowerSpouseController, EmploymentController, MediaController, PersonalController, ProfileController, RedirectControllers, SpouseController, SpouseEmploymentController, UnqualifiedUser};
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return redirect()->route('personal.edit');
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::prefix('review')->middleware('auth')->group(function () {
    Route::get('/personal', [PersonalController::class, 'edit'])->name('personal.edit');
    Route::patch('/personal', [PersonalController::class, 'update'])->name('personal.update');

    Route::get('/address', [AddressController::class, 'edit'])->name('address.edit');
    Route::patch('/address', [AddressController::class, 'update'])->name('address.update');

    Route::get('/employment', [EmploymentController::class, 'edit'])->name('employment.edit');
    Route::patch('/employment', [EmploymentController::class, 'update'])->name('employment.update');

    Route::get('/spouse', [SpouseController::class, 'edit'])->name('spouse.edit');
    Route::patch('/spouse', [SpouseController::class, 'update'])->name('spouse.update');

    Route::get('/spouse-employment', [SpouseEmploymentController::class, 'edit'])->name('spouse-employment.edit');
    Route::patch('/spouse-employment', [SpouseEmploymentController::class, 'update'])->name('spouse-employment.update');

    Route::get('/co_borrower', [CoBorrowerController::class, 'edit'])->name('co_borrower.edit');
    Route::patch('/co_borrower', [CoBorrowerController::class, 'update'])->name('co_borrower.update');
    
    Route::patch('/co_borrower-address', [CoBorrowerAddressController::class, 'update'])->name('co_borrower-address.update');
    Route::patch('/co_borrower-spouse', [CoBorrowerSpouseController::class, 'update'])->name('co_borrower-spouse.update');

    Route::get('/co_borrower-employment', [CoBorrowerEmploymentController::class, 'edit'])->name('co_borrower-employment.edit');
    Route::patch('/co_borrower-employment', [CoBorrowerEmploymentController::class, 'update'])->name('co_borrower-employment.update');

    Route::get('/aif', [AIFController::class, 'edit'])->name('aif.edit');
    Route::patch('/aif', [AIFController::class, 'update'])->name('aif.update');

    Route::get('/media', [MediaController::class, 'edit'])->name('media.edit');
    Route::patch('/media', [MediaController::class, 'update'])->name('media.update');
    Route::post('/media', [MediaController::class, 'store'])->name('media.store');
    Route::delete('/media', [MediaController::class, 'destroy'])->name('media.destroy');
});

Route::post('/upload-file', [MediaController::class, 'file_upload'])->name('filepond-upload-file');
Route::get('/signature', function () {
    return Inertia::render('Signature');
});

Route::get('/consult-page', [RedirectControllers::class, 'redirect_to_consult'])->name('consult-page');
Route::resource('unqualified-user', UnqualifiedUser::class)->only(['create', 'store']);


Route::mediaLibrary();

require __DIR__.'/auth.php';
