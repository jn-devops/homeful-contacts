<?php

namespace App\Classes;

use Spatie\LaravelData\{Attributes\MapInputName,
    Attributes\WithCast,
    Casts\DateTimeInterfaceCast,
    Data,
    DataCollection};
use App\Enums\{CivilStatus, Nationality, Sex};
use Illuminate\Support\Carbon;
use App\Traits\WithAck;

class ContactMetaData extends Data
{
    use WithAck;

    public string $name;

    public function __construct(
        public string $first_name,
        public string $middle_name,
        public string $last_name,
        public ?string $name_suffix,
        public ?string $mothers_maiden_name,
        public string $email,
        public string $mobile,
        public ?string $other_mobile,
        public ?string $help_number,
        public ?string $landline,
        public CivilStatus $civil_status,
        public Sex $sex,
        public Nationality $nationality,
        #[WithCast(DateTimeInterfaceCast::class, timeZone: 'Asia/Manila')]
        public Carbon $date_of_birth,
        /** @var AddressMetadata[] */
        public DataCollection $addresses,
        /** @var EmploymentMetadata[] */
        public DataCollection $employment,
        public ?SpouseMetadata $spouse,
        /** @var CoBorrowerMetadata[] */
        public ?DataCollection $co_borrowers,
        #[MapInputName('order')]
        public ?AIFMetadata $aif
    ) {
        $this->name = implode(' ', array_filter([$this->first_name, $this->middle_name, $this->last_name]));
    }
}
