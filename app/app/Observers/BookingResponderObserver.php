<?php

namespace App\Observers;

use App\Models\BookingResponder;

class BookingResponderObserver
{
    /**
     * Handle the BookingResponder "created" event.
     */
    public function created(BookingResponder $bookingResponder): void
    {
        // $bookingResponder->user()->update(['status' => 'not_available']);
    }

    /**
     * Handle the BookingResponder "updated" event.
     */
    public function updated(BookingResponder $bookingResponder): void
    {
        //
    }

    /**
     * Handle the BookingResponder "deleted" event.
     */
    public function deleted(BookingResponder $bookingResponder): void
    {
        $bookingResponder->user()->update(['status' => null]);
    }

    /**
     * Handle the BookingResponder "restored" event.
     */
    public function restored(BookingResponder $bookingResponder): void
    {
        //
    }

    /**
     * Handle the BookingResponder "force deleted" event.
     */
    public function forceDeleted(BookingResponder $bookingResponder): void
    {
        $bookingResponder->user()->update(['status' => null]);
    }
}
