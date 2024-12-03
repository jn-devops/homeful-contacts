<?php

namespace App\Models;

use App\Classes\{AddressMetadata, ContactMetaData, SpouseMetadata};
use Homeful\Contacts\Models\Contact as BaseContact;
use Spatie\LaravelData\{DataCollection, WithData};
use App\Enums\{CivilStatus, Nationality, Sex};
use App\Classes\EmploymentMetadata;
use DateTimeInterface;

class Contact extends BaseContact
{
    use WithData;

    protected string $dataClass = ContactMetaData::class;

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
            'spouse' => SpouseMetadata::class
        ];
    }
}
