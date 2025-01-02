<?php

namespace App\Classes;

use App\Enums\{Employment, EmploymentStatus, EmploymentType};
use Spatie\LaravelData\Data;
use App\Traits\WithAck;

/**
 * @deprecated
 */
class EmploymentMetadata extends Data
{
    use WithAck;

    public function __construct(
        public Employment $type,
        public float $monthly_gross_income,
        public EmploymentStatus $employment_status,
        public EmployerMetadata|null $employer,
        public EmploymentType|null $employment_type,
        public string|null $current_position,
        public IdMetadata|null $id
    ) {}
}
