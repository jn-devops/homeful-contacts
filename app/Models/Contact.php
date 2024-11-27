<?php

namespace App\Models;

use Homeful\Contacts\Models\Contact as BaseContact;
use App\Enums\{AddressType, CivilStatus, Nationality, Sex};
use DateTimeInterface;

class Contact extends BaseContact
{
    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('Y-m-d');
    }

    protected function casts(): array
    {
        return [
            'sex' => Sex::class,
            'civil_status' => CivilStatus::class,
            'nationality' => Nationality::class,
            'addresses.*.type' => AddressType::class
        ];
    }
}
