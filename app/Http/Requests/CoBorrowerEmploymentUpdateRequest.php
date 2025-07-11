<?php

namespace App\Http\Requests;

use Homeful\Contacts\Enums\{AddressType, Employment, EmploymentStatus, EmploymentType, Industry, Nationality, Ownership};
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CoBorrowerEmploymentUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'co_borrower_employment' => ['required', Rule::enum(Employment::class)],
            'employment_status' => ['required', Rule::enum(EmploymentStatus::class)],
            'monthly_gross_income' => ['required', 'numeric', 'min:0'],
            'current_position' =>  ['required', 'string', 'max:100'],
            'employment_type' =>  ['required', Rule::enum(EmploymentType::class)],
            'employer_name' =>  ['nullable', 'string', 'max:100'],
            'employer_email' =>  ['required_with:employer_name', 'email'],
            'employer_contact_no' =>  ['required_with:employer_name', 'string', 'min:10'],
            'employer_nationality' => ['nullable', Rule::enum(Nationality::class)],
            'employer_industry' => ['nullable', Rule::enum(Industry::class)],
            'employment_rank' => ['nullable', 'string'],
            'employment_years_in_service' => ['nullable', 'string'],
            'employer_year_established' => ['nullable', 'string'],

            'employer_address_type' => ['nullable', Rule::enum(AddressType::class)],
            'employer_address_ownership' => ['required_with:employer_address_type', Rule::enum(Ownership::class)],
            'employer_total_number_of_employees' => ['nullable', 'string', 'max:100'],
            'employer_address_address1' => ['required_with:employer_address_type', 'string', 'max:100'],
            'employer_address_locality' => ['required_with:employer_address_type', 'string', 'max:100'],
            'employer_address_sublocality' => ['required_with:employer_address_type', 'string', 'max:100'],
            'employer_address_administrative_area' => ['required_with:employer_address_type', 'string', 'max:100'],
            'employer_address_postal_code' => ['nullable', 'string', 'max:25'],
            'employer_address_region' => ['required_with:employer_address_type', 'string', 'max:100'],
            'employer_address_country' => ['required_with:employer_address_type', 'string', 'max:100'],

            'tin' => ['required', 'string', 'max:20'],
            'pagibig' => ['nullable', 'string', 'max:20'],
            'sss' => ['nullable', 'string', 'max:20'],
            'gsis' => ['nullable', 'string', 'max:20'],
        ];
    }
}
