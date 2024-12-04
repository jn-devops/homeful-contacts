<?php

namespace App\Http\Requests;

use App\Enums\{CivilStatus, CoBorrowerType, Nationality, Sex};
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
            'civil_status' => ['nullable', Rule::enum(CivilStatus::class)],
            'sex' => ['nullable', Rule::enum(Sex::class)],
            'nationality' => ['nullable', Rule::enum(Nationality::class)],
            'date_of_birth' => ['nullable', 'date'],
            'email' => ['nullable', 'email'],
            'mobile' => ['nullable', 'string', 'max:11'],
        ];
    }
}
