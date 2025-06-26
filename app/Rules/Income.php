<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\ValidationRule;
use Closure;

class Income implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $value = (float) $value;
        // $minimum_income = config('homeful-contacts.minimum_income');
        $minimum_income = 9999;
        if ($value < 0)
            $fail('The :attribute must not be negative.');
        elseif ($value > 0 && $value < $minimum_income)
            $fail('The :attribute must either be zero or at least ' . number_format($minimum_income) . '.');
    }
}
