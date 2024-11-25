<?php

use Illuminate\Foundation\Testing\{RefreshDatabase, WithFaker};
use App\Models\Contact;

uses(RefreshDatabase::class, WithFaker::class);

test('contact has minimum attributes', function () {
    $contact = Contact::create([
        'first_name' => $this->faker->firstName(),
        'last_name' => $this->faker->lastName(),
        'email' => $this->faker->email(),
        'mobile' => '09181234567',

        'middle_name' => $this->faker->lastName(), //should be  optional
        'civil_status' => \App\Enums\CivilStatus::random()->value,
        'sex' => \App\Enums\Sex::random()->value,
        'nationality' => \App\Enums\Nationality::random()->value,
        'date_of_birth' => $this->faker->date(),
    ]);

    expect($contact)->toBeInstanceOf(Contact::class);
});
