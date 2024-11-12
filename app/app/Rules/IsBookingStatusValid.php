<?php

namespace App\Rules;

use App\Models\Booking;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class IsBookingStatusValid implements ValidationRule
{

    public function __construct(public $id)
    {
    }
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $booking = Booking::find($this->id);
        $role = auth()->user()->roles->toArray()[0]['name'];

        if(!(($booking->status === 'pending' && $role === 'admin_staff') || ($booking->status === 'accepted' && $role === 'brgy_staff'))) {
            $fail('status is not valid');
        }
    }
}
