<?php

namespace App\Http\Requests;

use App\Enums\{Employment, EmploymentStatus, EmploymentType};
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
            'employment_status' => ['required', Rule::enum(EmploymentStatus::class)],
            'monthly_gross_income' => ['required', 'numeric', 'min:0'],
            'current_position' =>  ['required', 'string', 'max:100'],
            'employment_type' =>  ['required', Rule::enum(EmploymentType::class)],
        ];
    }
}
