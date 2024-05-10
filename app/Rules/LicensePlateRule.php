<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class LicensePlateRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $regexPattern = config('define.regex.license_plate');
        if (!preg_match($regexPattern, $value)) {
            $fail("The $attribute format is invalid.");
        }
    }
}
