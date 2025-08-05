<?php

namespace App\Http\Requests;

use Homeful\Contacts\Enums\{AddressType, Employment, EmploymentStatus, EmploymentType, Industry, Nationality, Ownership};
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EmploymentUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'type' => ['required', Rule::enum(Employment::class)],
            'monthly_gross_income' => ['required', 'numeric', 'min:0'],
            'employment_status' => ['nullable', 'string'],

            'employer_name' =>  ['required', 'string', 'max:100'],
            'employment_type' =>  ['required_with:employer_name', 'string'],
            'current_position' =>  ['nullable', 'string', 'max:100'],
            'rank' =>  ['nullable', 'string', 'max:100'],
            'years_in_service' =>  ['nullable', 'string', 'max:100'],
            'employer_year_established' =>  ['nullable', 'string', 'max:100'],
            'employer_total_number_of_employees' =>  ['nullable', 'string'],

            'employer_email' =>  ['required_with:employer_name', 'email'],
            'employer_contact_no' =>  ['required_with:employer_name', 'string', 'min:10'],
            'employer_nationality' => ['nullable', 'string'],
            'employer_industry' => ['nullable', 'string'],
            'employer_address_type' => ['nullable', 'string'],
            'employer_address_ownership' => ['required_with:employer_name', 'string'],
            'employer_address_address1' => ['required_with:employer_name', 'string', 'max:100'],
            'employer_address_locality' => ['nullable', 'string', 'max:100'],
            'employer_address_sublocality' => ['nullable', 'string', 'max:100'],
            'employer_address_administrative_area' => ['nullable', 'string', 'max:100'],
            'employer_address_postal_code' => ['nullable', 'string', 'max:25'],
            'employer_address_region' => ['nullable', 'string', 'max:100'],
            'employer_address_country' => ['required_with:employer_name', 'nullable', 'string', 'max:100'],

            'tin' => ['nullable', 'string', 'max:20'],
            'pagibig' => ['nullable', 'string', 'max:20'],
            'sss' => ['nullable', 'string', 'max:20'],
            'gsis' => ['nullable', 'string', 'max:20'],
        ];
    }
}
