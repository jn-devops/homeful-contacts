<?php

use Illuminate\Foundation\Testing\{RefreshDatabase, WithFaker};
use Illuminate\Support\Facades\Notification;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\{Contact, User};
use App\Imports\UsersImport;

uses(RefreshDatabase::class, WithFaker::class);

beforeEach(function () {
    Notification::fake();
});

test('users import works', function () {
    expect(file_exists(storage_path('app/private/contacts.csv')))->toBeTrue();

    Excel::import($import = app( UsersImport::class), storage_path('app/private/contacts.csv'));
    expect(User::all()->count())->toBe(Contact::all()->count());
});
