<?php

namespace App\Classes;

use App\Enums\{Employment, EmploymentStatus, EmploymentType};
use Spatie\LaravelData\Data;

class EmploymentMetadata extends Data
{
    public function __construct(
        public Employment $type,
        public EmploymentStatus $employment_status,
        public EmploymentType $employment_type,
        public float $monthly_gross_income,
        public string $current_position,
        public EmployerMetadata|null $employer,
        public IdMetadata|null $id
    ) {}
}
