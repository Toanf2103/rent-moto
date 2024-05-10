<?php

namespace App\Rules;

use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class DateDifferenceRule implements ValidationRule
{

    public function __construct(protected $startDate, protected $maxDateDiff)
    {
        //
    }
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $startDate = Carbon::parse(request()->get($this->startDate));
        $endDate = Carbon::parse($value);
        $daysDifference = $endDate->diffInDays($startDate) + 1;
        if ($daysDifference > $this->maxDateDiff) {
            $fail('validation.customers.date_diff')->translate(['value' => $this->maxDateDiff]);
        }
    }
}
