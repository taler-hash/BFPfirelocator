<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Models\Booking;

class CanAbleToDeleteUsingStatus implements ValidationRule
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
        dd('test');
        $currentBooking = Booking::find($this->id);
        $role = request()->user()->roles;
        
    }
}
