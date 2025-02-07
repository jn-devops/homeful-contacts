<?php

use Illuminate\Foundation\Testing\{RefreshDatabase, WithFaker};
use Homeful\Contacts\Classes\ContactMetaData;
use Illuminate\Support\Facades\Notification;
use App\Actions\RegisterContact;
use App\Models\{Contact, User};

uses(RefreshDatabase::class, WithFaker::class);

beforeEach(function () {
    Notification::fake();
});

test('register contact action works', function () {
    $action = app(RegisterContact::class);
    $attribs = [
        'name' => $name = 'Mary Cruz',
        'email' => $email = 'mcruz@hotmail.com',
        'mobile' => $mobile = '09171234567',
        'password' => 'password',
        'password_confirmation' => 'password',
        'date_of_birth' => $date_of_birth = '1999-03-17',
        'monthly_gross_income' => $monthly_gross_income = 100000.0
    ];

    $user = $action->run($attribs);
    expect($user)->toBeInstanceOf(User::class);
    expect($user->name)->toBe($name);
    expect($user->email)->toBe($email);
    expect($user->mobile)->toBe($mobile);
    expect($user->contact)->toBeInstanceOf(Contact::class);
    expect($user->contact->email)->toBe($email);
    expect($user->contact->mobile)->toBe($mobile);
    tap($user->contact->getData(), function (ContactMetaData $contact) use ($name, $email, $mobile, $date_of_birth, $monthly_gross_income) {
        expect($contact->name)->toBe($name);
        expect($contact->email)->toBe($email);
        expect($contact->mobile)->toBe($mobile);
        expect($contact->date_of_birth->format('Y-m-d'))->toBe($date_of_birth);
        expect($contact->employment->first()->monthly_gross_income)->toBe($monthly_gross_income);
    });
});

test('register contact end point works', function () {
    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => $email = 'test@example.com',
        'mobile' => '09171234567',
        'password' => 'password',
        'password_confirmation' => 'password',
        'date_of_birth' => $date_of_birth = '1999-03-17',
        'monthly_gross_income' => $monthly_gross_income = 100000.0
    ]);
    expect($response->status())->toBe(302);
    $user = User::where('email', $email)->first();
    $contact = $user->contact;

    expect($contact->date_of_birth->format('Y-m-d'))->toBe($date_of_birth);
    expect($contact->employment->first()->monthly_gross_income)->toBe($monthly_gross_income);
});
