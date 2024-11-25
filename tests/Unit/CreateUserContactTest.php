<?php

use Illuminate\Foundation\Testing\{RefreshDatabase, WithFaker};
use App\Actions\CreateUserContact;
use App\Models\User;

uses(RefreshDatabase::class, WithFaker::class);

test('create contact user works', function () {
    $attribs = [
        'first_name' => $first_name = $this->faker->firstName(),
        'last_name' => $last_name = $this->faker->lastName(),
        'email' => $email = $this->faker->email(),
        'mobile' => '09171234567',

        'middle_name' => $middle_name = $this->faker->lastName(),
        'civil_status' => $civil_status = $this->faker->word(),
        'sex' => $sex = $this->faker->word(),
        'nationality' => $nationality = $this->faker->word(),
        'date_of_birth' => $date_of_birth = $this->faker->date(),
    ];

   $reference = app(CreateUserContact::class)->run($attribs);
   $user = User::where('email', $email)->firstOrFail();

    expect($reference->getContact()->is($user->contact))->toBeTrue();

    expect($user->contact->first_name)->toBe($first_name);
    expect($user->contact->middle_name)->toBe($middle_name);
    expect($user->contact->last_name)->toBe($last_name);
    expect($user->contact->civil_status)->toBe($civil_status);
    expect($user->contact->sex)->toBe($sex);
    expect($user->contact->nationality)->toBe($nationality);
    expect($user->contact->date_of_birth->format('Y-m-d'))->toBe($date_of_birth);
});
