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

// Route::get('update-contact-data/{id}', [ContactController::class, 'updateContactUsingId']);
Route::post('register', RegisterContact::class)->name('register-contact');

// For Internal Testing Purposes
Route::post('get-contact-media/{id}', function($id){
    $customer = Customer::find($id);
    return response()->json($customer->getMedia());
});

