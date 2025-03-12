<?php

use App\Http\Controllers\{ContactController, ReferenceController};
use Illuminate\Support\Facades\Route;
use App\Actions\RegisterContact;
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
Route::post('get-contact-by-id', [ContactController::class, 'getContactById']);
Route::delete('delete-contact-data/email/{email}', [ContactController::class, 'destroy_email']);
Route::delete('delete-contact-data/mobile/{mobile}', [ContactController::class, 'destroy_mobile']);
Route::post('get-contact-media/{id}', function($id){
    $customer = Customer::find($id);
    dd($customer->getMedia(), $customer->birthCertificateDocument);
});

