<?php

use App\Enums\{AddressType, CivilStatus, Nationality, Ownership, Sex};
use App\Data\UserData;
use Illuminate\Support\Str;
use Illuminate\Foundation\Testing\{RefreshDatabase, WithFaker};
use App\Classes\{AddressMetadata, ContactMetaData};
use Spatie\LaravelData\DataCollection;
use App\Models\{Contact, User};

uses(RefreshDatabase::class, WithFaker::class);

test('user has attributes', function () {
    $first_name = 'Juan';
    $last_name = 'de la Cruz';
    $user = User::create([
        'name' => $first_name . ' ' . $last_name,
        'email' => 'juandelacruz@gmail.com',
        'mobile' => '09181234567',
        'password' => Str::password(),
    ]);
    expect($user)->toBeInstanceOf(User::class);
    expect(UserData::from($user->toArray()))->toBeInstanceOf(UserData::class);
});

test('user registration via end point persists contact', function () {
    expect(Contact::all())->toHaveCount(0);
    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'mobile' => '09171234567',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);
    $user = User::where('email', 'test@example.com')->first();
    $contact = Contact::where('email', 'test@example.com')->first();
    expect($user)->toBeInstanceOf(User::class);
    expect($contact)->toBeInstanceOf(Contact::class);
    expect($user->contact->is($contact));
});
