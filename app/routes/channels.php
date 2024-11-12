<?php
use App\Models\Booking;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('booking.{bookingId}', function ($user, $bookingId) {
    return $user;
});