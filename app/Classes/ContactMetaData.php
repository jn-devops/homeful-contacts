<?php

namespace App\Classes;

use Spatie\LaravelData\Transformers\DateTimeInterfaceTransformer;
use Spatie\LaravelData\Attributes\{WithCast, WithTransformer};
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\{Data, DataCollection};
use App\Enums\{CivilStatus, Nationality, Sex};
use Illuminate\Support\Carbon;
use App\Traits\WithAck;

class ContactMetaData extends Data
{
    use WithAck;

    public string $name;

    public function __construct(
        public string $first_name,
        public ?string $middle_name,
        public string $last_name,
        public ?string $name_suffix,
        public ?string $mothers_maiden_name,
        public string $email,
        public string $mobile,
        public ?string $other_mobile,
        public ?string $help_number,
        public ?string $landline,
        public CivilStatus|null $civil_status,
        public Sex|null $sex,
        public Nationality|null $nationality,
        #[WithTransformer(DateTimeInterfaceTransformer::class, format: 'Y-m-d')]
        #[WithCast(DateTimeInterfaceCast::class, timeZone: 'Asia/Manila')]
        public Carbon $date_of_birth,
        /** @var AddressMetadata[] */
        public ?DataCollection $addresses,
        /** @var EmploymentMetadata[] */
        public ?DataCollection $employment,
        public ?SpouseMetadata $spouse,
        /** @var CoBorrowerMetadata[] */
        public ?DataCollection $co_borrowers,
        #[MapInputName('order')]
        public ?AIFMetadata $aif
    ) {
        $this->name = implode(' ', array_filter([$this->first_name, $this->middle_name, $this->last_name]));
    }
}
