<?php

use Homeful\Contacts\Enums\{AddressType, CivilStatus, Employment, EmploymentStatus, Nationality, Ownership, Sex};
use Homeful\Contacts\Classes\{AddressMetadata, ContactMetaData, EmploymentMetadata};
use Illuminate\Foundation\Testing\{RefreshDatabase, WithFaker};
use App\Notifications\SendContactReferenceCodeNotification;
use Illuminate\Support\Facades\Notification;
use App\Models\{Contact, Reference, User};
use Spatie\LaravelData\DataCollection;

uses(RefreshDatabase::class, WithFaker::class);

beforeEach(function () {
   Notification::fake();
});

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

test('contact registration triggers notification re contact reference code', function () {
    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => $email = 'test@example.com',
        'mobile' => '09171234567',
        'password' => 'password',
        'password_confirmation' => 'password',
        'date_of_birth' => '1999-03-17',
        'monthly_gross_income' => 50000,
    ]);
    expect($response->status())->toBe(302);
    $user = User::where('email', $email)->first();
    $contact = $user->contact;
    expect($contact)->toBeInstanceOf(Contact::class);
    Notification::assertSentTo($contact, SendContactReferenceCodeNotification::class, function (SendContactReferenceCodeNotification $notification) use ($contact) {
        $reference = Reference::where('code', $notification->getContactReferenceCode())->first();

        return $reference->getContact()->is($contact);
    });
});
