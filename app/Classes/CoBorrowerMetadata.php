<?php

namespace App\Classes;

use Spatie\LaravelData\{Data, DataCollection};
use App\Enums\{CivilStatus, Nationality, Sex};
use App\Enums\CoBorrowerType;
use App\Traits\WithAck;

class CoBorrowerMetadata extends Data
{
    use WithAck;

    public string $name;

    public function __construct(
        public CoBorrowerType $type,
        public string $first_name,
        public string $middle_name,
        public string $last_name,
        public CivilStatus $civil_status,
        public Sex $sex,
        public Nationality $nationality,
        public $date_of_birth,
        /** @var EmploymentMetadata[] */
        public DataCollection $employment,
        public ?string $email,
        public ?string $mobile,
    ) {
        $this->name = implode(' ', array_filter([$this->first_name, $this->middle_name, $this->last_name]));
    }
}
