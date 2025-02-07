<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\ValidationRule;
use Closure;

class FullNameRequired implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $firstName = request('first_name') ?? '';
        $lastName = request('last_name') ?? '';

        // If both first_name and last_name are empty
        if (empty($firstName) && empty($lastName)) {
            // Ensure the name is not empty and meets the regex requirement
            if (empty($value) || !is_string($value) || !preg_match('/^[\w.]+(\s+[\w.]+)+$/', $value)) {
                $fail('The name must contain at least a first name and a last name.');
            }
        }
    }
}
