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

dataset('contact', function () {
    return [
        fn () => Contact::create([
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'email' => $this->faker->email(),
            'mobile' => '09181234567',

            'middle_name' => $this->faker->lastName(), //should be  optional
            'civil_status' => \App\Enums\CivilStatus::random()->value,
            'sex' => \App\Enums\Sex::random()->value,
            'nationality' => \App\Enums\Nationality::random()->value,
            'date_of_birth' => $this->faker->date(),
        ])
    ];
});

test('contact can accept addresses', function (Contact $contact) {
    $contact->addresses = [
        [
            'type' => \App\Enums\AddressType::default(),
            'ownership' => $this->faker->word(),
            'address1' => $this->faker->address(),
            'locality' => $this->faker->city(),
            'administrative_area' => $this->faker->randomElement(['NCR', 'Metro Manila', 'Cebu']),
            'postal_code' => $this->faker->postcode(),
            'region' => $this->faker->word(),
            'country' => 'PH',
            ]
    ];
    dd($contact->addresses[0]);
    dd(collect($contact->addresses)->groupBy('type'));
})->with('contact');
