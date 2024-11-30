<?php

namespace App\Models;

use App\Enums\{AddressType, CivilStatus, Employment, EmploymentStatus, EmploymentType, Nationality, Sex};
use App\Classes\EmploymentMetadata;
use Homeful\Contacts\Models\Contact as BaseContact;
use Spatie\LaravelData\DataCollection;
use App\Classes\AddressMetadata;
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
            'addresses' => DataCollection::class . ':' . AddressMetadata::class,
            'employment' => DataCollection::class . ':' . EmploymentMetadata::class,
        ];
    }
}
