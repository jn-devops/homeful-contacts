<?php

use App\Http\Controllers\ContactController;
use Illuminate\Support\Facades\Route;
use App\Actions\RegisterContact;
use Illuminate\Http\Request;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::resource('contacts', ContactController::class)->only(['show']);

Route::post('register', RegisterContact::class)->name('register-contact');
