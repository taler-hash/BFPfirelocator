<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteBookingRequest;
use App\Http\Requests\EditBookingRequest;
use App\Http\Requests\ShowBookingRequest;
use App\Http\Requests\StoreBookingRequest;
use Illuminate\Http\Request;
use App\Services\BookingService;
use Inertia\Inertia;
use App\Events\BookingMapUpdatedEvent;

class BookingController extends Controller
{

    public $bookingService;

    public function __construct()
    {
        $this->bookingService = new BookingService();
    }
    
    public function display(Request $request) {
        return Inertia::render('Booking/Booking');
    }

    public function index(Request $request) {
        return response()->json($this->bookingService->getBookings($request));
    }

    public function show(Request $request) {
        return $this->bookingService->getBooking($request);
    }

    public function logs(Request $request) {
        return $this->bookingService->getBookingLogs($request);
    }

    public function store(StoreBookingRequest $request) {
        $this->bookingService->storeBooking($request);
    }

    public function edit(Request $request) {
        $this->bookingService->editBooking($request);
    }

    public function delete(DeleteBookingRequest $request) {
        return $this->bookingService->deleteBooking($request);
    }

    public function sendCoords(Request $request) {
        $this->bookingService->sendCoords($request);
    }

    public function unsetResponderCoords(Request $request) {
        $this->bookingService->unsetResponderCoords($request);
    }
}
