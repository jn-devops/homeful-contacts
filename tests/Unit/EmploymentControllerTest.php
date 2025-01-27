<?php

use Illuminate\Foundation\Testing\{RefreshDatabase, WithFaker};
use Homeful\Contacts\Classes\EmploymentMetadata;
use Spatie\LaravelData\DataCollection;
use App\Models\{Contact, User};

uses(RefreshDatabase::class, WithFaker::class);

$mobile = '09171234567';
$response = null;

beforeEach(function () use (&$response, $mobile) {
    $this->response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'mobile' => $mobile,
        'password' => 'password',
        'password_confirmation' => 'password',
        'date_of_birth' => '1999-03-17',
        'monthly_gross_income' => 15000
    ]);
});

dataset('employment_records', function () {
    return [
        [fn() => [
            [
                "type" => "Primary",
                "monthly_gross_income" => 88000,
                "employment_status" => "Regular",
                "employment_type" => "Locally Employed",
                "current_position" => "MD",
                "employer" => [
                    "name" => "3neti",
                    "email" => "lester@hurtado.ph",
                    "contact_no" => "09181234567",
                    "nationality" => "Filipino",
                    "industry" => "Accounting",
                    "address" => [
                        "type" => "Work",
                        "ownership" => "Owned",
                        "address1" => "8 West Maya Drive, Philam Homes, QC",
                        "locality" => "Pasig City",
                        "administrative_area" => "Metro Manila",
                        "postal_code" => "1400",
                        "region" => "NCR",
                        "country" => "PH"
                    ]
                ],
                "id" => [
                    "tin" => "GGG 123 123 123",
                    "pagibig" => "ABC-123",
                    "sss" => '',
                    "gsis" => null
                ]
            ]
        ]]
    ];
});

test('employment assignment works', function (array $employment_records) use ($mobile) {
    $user = User::where('mobile', $mobile)->first();
    if (($contact = $user->contact) instanceof Contact) {
        $contact->employment = $employment_records;
        $contact->save();
        expect($contact->employment)->toBeInstanceOf(DataCollection::class);
        expect($contact->employment->first())->toBeInstanceOf(EmploymentMetadata::class);
        expect(filter_trim_recursive_array($contact->employment->first()->toArray()))
            ->toBe(filter_trim_recursive_array(EmploymentMetadata::from($employment_records[0])->toArray()));
    }
})->with('employment_records');

test('employment assignment also works', function (array $employment_records) use ($mobile) {
    $user = User::where('mobile', $mobile)->first();
    if (($user->contact) instanceof Contact) {
        $user->contact->employment = $employment_records;
        $user->contact->save();
        expect($user->contact->employment)->toBeInstanceOf(DataCollection::class);
        expect($user->contact->employment->first())->toBeInstanceOf(EmploymentMetadata::class);
        expect(filter_trim_recursive_array($user->contact->employment->first()->toArray()))
            ->toBe(filter_trim_recursive_array(EmploymentMetadata::from($employment_records[0])->toArray()));
    }
})->with('employment_records');
