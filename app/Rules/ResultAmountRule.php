<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ResultAmountRule implements ValidationRule
{
    protected $amount;
    protected $rate;

    public function __construct($amount, $rate)
    {
        $this->amount = $amount;
        $this->rate = $rate;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $expectedResultAmount = $this->amount * $this->rate;
        if (abs($expectedResultAmount - $value) >= 0.00001) {
            $fail('The result amount does not match the expected value.');
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return 'The result amount does not match the expected value.';
    }
}
