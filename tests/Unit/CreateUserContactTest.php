<?php

use Illuminate\Foundation\Testing\{RefreshDatabase, WithFaker};
use App\Actions\CreateUserContact;
use App\Models\{Contact, User};

uses(RefreshDatabase::class, WithFaker::class);

test('create contact user works', function () {
    $attribs = [
        'first_name' => $first_name = $this->faker->firstName(),
        'last_name' => $last_name = $this->faker->lastName(),
        'email' => $email = $this->faker->email(),
        'mobile' => '09171234567',

        'middle_name' => $middle_name = $this->faker->lastName(),
        'civil_status' => $civil_status = \Homeful\Contacts\Enums\CivilStatus::random()->value,
        'sex' => $sex = \Homeful\Contacts\Enums\Sex::random()->value,
        'nationality' => $nationality = \Homeful\Contacts\Enums\Nationality::random()->value,
        'date_of_birth' => $date_of_birth = $this->faker->date(),
    ];

   $reference = app(CreateUserContact::class)->run($attribs);
   $user = User::where('email', $email)->firstOrFail();

    expect($reference->getEntities(Contact::class)->first()->is($user->contact))->toBeTrue();

    expect($user->contact->first_name)->toBe($first_name);
    expect($user->contact->middle_name)->toBe($middle_name);
    expect($user->contact->last_name)->toBe($last_name);
    expect($user->contact->civil_status)->toBe(\Homeful\Contacts\Enums\CivilStatus::from($civil_status));
    expect($user->contact->sex)->toBe(\Homeful\Contacts\Enums\Sex::from($sex));
    expect($user->contact->nationality)->toBe(\Homeful\Contacts\Enums\Nationality::from($nationality));
    expect($user->contact->date_of_birth->format('Y-m-d'))->toBe($date_of_birth);
})->skip();
