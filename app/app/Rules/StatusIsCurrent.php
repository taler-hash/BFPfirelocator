<?php

namespace App\Rules;

use App\Models\Booking;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class StatusIsCurrent implements ValidationRule
{

    public function __construct(private $id)
    {
        $this->id = $id;
    }
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $currentBooking = Booking::find($this->id);
        if($currentBooking->status !== $value) {
            $fail('Status is outdated');
        }
    }
}
