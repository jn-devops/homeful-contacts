<?php

use App\Enums\{AddressType, CivilStatus, Employment, EmploymentStatus, Nationality, Ownership, Sex};
use App\Classes\{AddressMetadata, ContactMetaData, EmploymentMetadata};
use Illuminate\Foundation\Testing\{RefreshDatabase, WithFaker};
use Spatie\LaravelData\DataCollection;
use App\Models\Contact;

uses(RefreshDatabase::class, WithFaker::class);

test('contact has attributes', function () {
    $contact = Contact::create([
        'first_name' => $this->faker->firstName(),
        'last_name' => $this->faker->lastName(),
        'email' => $this->faker->email(),
        'mobile' => '09181234567',
        'middle_name' => $this->faker->lastName(), //should be  optional
        'civil_status' => CivilStatus::random()->value,
        'sex' => Sex::random()->value,
        'nationality' => Nationality::random()->value,
        'date_of_birth' => $this->faker->date(),
    ]);
    expect($contact)->toBeInstanceOf(Contact::class);
    expect(ContactMetaData::from($contact->toArray()))->toBeInstanceOf(ContactMetaData::class);
});

test('contact has minimum attributes', function () {
    $contact = Contact::create([
        'first_name' => $this->faker->firstName(),
        'last_name' => $this->faker->lastName(),
        'email' => $this->faker->email(),
        'mobile' => '09181234567',
    ]);
    expect($contact)->toBeInstanceOf(Contact::class);
    expect(ContactMetaData::from($contact->toArray()))->toBeInstanceOf(ContactMetaData::class);
});

dataset('contact', function () {
    return [
        fn () => Contact::create([
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'email' => $this->faker->email(),
            'mobile' => '09181234567',
            'middle_name' => $this->faker->lastName(), //should be  optional
            'civil_status' => CivilStatus::random()->value,
            'sex' => Sex::random()->value,
            'nationality' => Nationality::random()->value,
            'date_of_birth' => $this->faker->date(),
        ])
    ];
});

test('contact can accept addresses', function (Contact $contact) {
    $contact->addresses = [
        [
            'type' => AddressType::default(),
            'ownership' => Ownership::random(),
            'address1' => $this->faker->address(),
            'locality' => $this->faker->city(),
            'administrative_area' => $this->faker->randomElement(['NCR', 'Metro Manila', 'Cebu']),
            'postal_code' => $this->faker->postcode(),
            'region' => $this->faker->word(),
            'country' => 'PH',
        ]
    ];
    expect($contact->addresses)->toBeInstanceOf(DataCollection::class);
    expect($contact->addresses->first())->toBeInstanceOf(AddressMetadata::class);
})->with('contact');

test('contact can accept minimum address attributes', function (Contact $contact) {
    $contact->addresses = [
        [
            'type' => AddressType::default(),
            'ownership' => Ownership::random(),
            'address1' => $this->faker->address(),
            'locality' => $this->faker->city(),
            'administrative_area' => $this->faker->randomElement(['NCR', 'Metro Manila', 'Cebu']),
            'postal_code' => $this->faker->postcode(),
            'region' => $this->faker->word(),
            'country' => 'PH',
        ]
    ];
    expect($contact->addresses)->toBeInstanceOf(DataCollection::class);
    expect($contact->addresses->first())->toBeInstanceOf(AddressMetadata::class);
})->with('contact');

test('contact can accept employment', function (Contact $contact) {
    $employment = EmploymentMetadata::from([
        'type' => Employment::default(),
        'monthly_gross_income' => 100000,
        'employment_status' => EmploymentStatus::default()
    ]);
    $contact->employment = [$employment];
    $contact->save();
    expect($contact->employment)->toBeInstanceOf(DataCollection::class);
    expect($contact->employment->first())->toBeInstanceOf(EmploymentMetadata::class);
})->with('contact');
