<?php

use Illuminate\Foundation\Testing\{RefreshDatabase, WithFaker};
use Homeful\Contacts\Models\Contact;

uses(RefreshDatabase::class, WithFaker::class);

test('contact has minimum attributes', function () {
    $contact = Contact::create([
        'first_name' => $this->faker->firstName(),
        'last_name' => $this->faker->lastName(),
        'email' => $this->faker->email(),
        'mobile' => '09181234567',

        'middle_name' => $this->faker->lastName(), //should be  optional
        'civil_status' => $this->faker->word(),
        'sex' => $this->faker->word(),
        'nationality' => $this->faker->word(),
        'date_of_birth' => $this->faker->date(),
    ]);

    expect($contact)->toBeInstanceOf(Contact::class);
});
