<?php

namespace App\Http\Requests;

use Homeful\Contacts\Enums\{AddressType, Ownership};
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class AddressUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'type' => ['required', Rule::enum(AddressType::class)],
            'ownership' => ['required', Rule::enum(Ownership::class)],
            'address1' => ['required', 'string', 'max:100'],
            'locality' => ['required', 'string', 'max:100'],
            'administrative_area' => ['required', 'string', 'max:100'],
            'postal_code' => ['required', 'string', 'max:25'],
            'region' => ['required', 'string', 'max:100'],
            'country' => ['required', 'string', 'max:100'],
        ];
    }
}
