<?php

use App\Http\Controllers\{ContactController, LazarusAPIController, ReferenceController};
use Illuminate\Support\Facades\Route;
use App\Actions\RegisterContact;
use App\Http\Controllers\Auth\RegisteredUserController;
use Homeful\Contacts\Models\Customer;
use Illuminate\Http\Request;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::resource('contacts', ContactController::class)->only(['show']);
Route::resource('references', ReferenceController::class)->only(['show']);

Route::post('register', RegisterContact::class)->name('register-contact');

// For Internal Testing Purposes
Route::post('update-contact-data', [ContactController::class, 'updateContactUsingId']);
Route::get('get-contact-by-id', [ContactController::class, 'getContactById']);
Route::delete('delete-contact-data/email/{email}', [ContactController::class, 'destroy_email']);
Route::delete('delete-contact-data/mobile/{mobile}', [ContactController::class, 'destroy_mobile']);
Route::post('get-contact-media/{id}', function($id){
    $customer = Customer::find($id);
    dd($customer->getMedia(), $customer->birthCertificateDocument);
});

Route::post('/auth/login', [LazarusAPIController::class, 'login']);
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/create-contacts-seller-app', [RegisteredUserController::class, 'createContactForSellerApp']);

    Route::get('/contact/{id}', [LazarusAPIController::class, 'getContactByID']);
    Route::put('/update-contact', [LazarusAPIController::class, 'updateContactFromLazarus']);
    Route::post('/set-lazarus-contact', [LazarusAPIController::class, 'setLazarusContact']);
    Route::post('/set-contact', [LazarusAPIController::class, 'createContact']);
    Route::get('/get-attachment-requirement/{id}', [LazarusAPIController::class, 'getAttachmentRequirementByID']);
    Route::post('/set-attachment-requirement', [LazarusAPIController::class, 'setAttachmentRequirementByID']);
    Route::post('/get-contact-by-homeful-id', [LazarusAPIController::class, 'getContactByVoucherCode']);
    Route::post('/update-contact-by-homeful-id', [ContactController::class, 'updateContactByHomefulId']);
    Route::get('/check-contact-if-referral-exists/{homeful_id}', [ContactController::class, 'checkReferralContact']);
});
