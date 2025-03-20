<?php

namespace App\Http\Requests;

use Homeful\Contacts\Enums\{CivilStatus, CoBorrowerType, Nationality, Sex};
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CoBorrowerUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'type' => ['required', Rule::enum(CoBorrowerType::class)],
            'first_name' => ['required', 'string', 'max:50'],
            'middle_name' => ['nullable', 'string', 'max:50'],
            'last_name' => ['required', 'string', 'max:50'],
            'name_suffix' => ['nullable', 'string', 'max:10'],
            'mothers_maiden_name' => ['nullable', 'string', 'max:510'],
            'civil_status' => ['required', Rule::enum(CivilStatus::class)],
            'sex' => ['required', Rule::enum(Sex::class)],
            'nationality' => ['required', Rule::enum(Nationality::class)],
            'date_of_birth' => ['required', 'date'],
            'email' => ['nullable', 'email'],
            'mobile' => ['nullable', 'string', 'max:11'],
            'other_mobile' => 'nullable|string|max:20',
            'landline' => 'nullable|string|max:20',
            'employment' => ['nullable']
        ];
    }
}
