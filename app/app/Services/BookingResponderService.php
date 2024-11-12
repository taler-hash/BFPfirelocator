<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\BookingResponder;
use App\Traits\Integrity;

class BookingResponderService {
    use Integrity;

    public function getBookingResponders($request) {
        return BookingResponder::with('user')
        ->where('booking_id', $request->bookingId)->get();
    }

    public function storeBookingResponders($request) {
        $this->checkIntegrity(Booking::class,$request ,function () use ($request) {
            $booking = Booking::find($request->id);
            $booking->responders()
            ->createMany($this->idAsUserId($request->responders));
            $this->makeUserNotAvailable($booking);
        }, ['exist' => true, 'integrity' => true]);
        
    }

    public function bulkEditBookingResponders($request) {
        $this->checkIntegrity(Booking::class,$request ,function () use ($request) {
            BookingResponder::where('booking_id', $request->id)->each(function ($bookingResponder) {
                $bookingResponder->delete();
            });

            $this->storeBookingResponders($request);
        }, ['exist' => true, 'integrity' => true]);
    }

    public function getOnGoingBooking($request) {
        return BookingResponder::with('booking.station')
        ->where('user_id', $request->id)
        ->whereHas('booking', function($q) {
            $q->where('status', 'ongoing');
        })->first();
    }

    private function idAsUserId(array $collection) {
        return collect($collection)->map(function ($item) {
            return [
                'user_id' => $item['id'],
                'name' => $item['name']
            ];
        })->toArray();
    }

    private function makeUserNotAvailable($bookingModel) {
        $bookingModel->responders()->each(function ($bookingResponder) {
            
            $bookingResponder->user()->update(['status' => 'not_available']);
        });
    }
}