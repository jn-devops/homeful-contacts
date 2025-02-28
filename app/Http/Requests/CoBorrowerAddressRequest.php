<?php

namespace App\Http\Requests;

use Homeful\Contacts\Enums\{AddressType, CoBorrowerType, Employment, EmploymentStatus, EmploymentType, Industry, Nationality, Ownership};
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CoBorrowerAddressRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'co_borrower_type' => ['required', Rule::enum(CoBorrowerType::class)],
            'type' => ['required', Rule::enum(AddressType::class)],
            'ownership' => ['required', Rule::enum(Ownership::class)],
            'address1' => ['required', 'string', 'max:100'],
            'locality' => ['required', 'string', 'max:100'],
            'sublocality' => ['required', 'string', 'max:100'],
            'administrative_area' => ['required', 'string', 'max:100'],
            'postal_code' => ['required', 'string', 'max:25'],
            'region' => ['required', 'string', 'max:100'],
            'country' => ['required', 'string', 'max:100'],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'co_borrower_type.required' => 'The coborrower type is required.',
            'type.required' => 'The address type is required.',
            'ownership.required' => 'The ownership status is required.',
            'address1.required' => 'The address field cannot be empty.',
            'address1.max' => 'The address must not exceed 100 characters.',
            'locality.required' => 'The City field is required.',
            'locality.max' => 'The City must not exceed 100 characters.',
            'sublocality.required' => 'The Barangay field is required.',
            'sublocality.max' => 'The Barangay must not exceed 100 characters.',
            'administrative_area.required' => 'The Province is required.',
            'administrative_area.max' => 'The Province must not exceed 100 characters.',
            'postal_code.required' => 'The Zip Code is required.',
            'postal_code.max' => 'The Zip Code must not exceed 25 characters.',
            'region.required' => 'The Region is required.',
            'region.max' => 'The Region must not exceed 100 characters.',
            'country.required' => 'The Country is required.',
            'country.max' => 'The country must not exceed 100 characters.',
        ];
    }
}
