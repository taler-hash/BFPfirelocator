<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\BookingResponderService;
use App\Services\BookingService;

class BookingResponderController extends Controller
{

    //tiwasa ni mag himu kag logic para ang status ma inprogress human mag assign ug responders
    public $bookingResponderService;
    public $bookingService;

    public function __construct()
    {
        $this->bookingResponderService = new BookingResponderService();
        $this->bookingService = new BookingService();

    }

    public function index(Request $request) {
        return response()->json($this->bookingResponderService->getBookingResponders($request));
    }

    public function store(Request $request) {
        $this->bookingResponderService->storeBookingResponders($request);
    }

    public function edit(Request $request) {
        
    }

    public function bulkEdit(Request $request) {
        $this->bookingResponderService->bulkEditBookingResponders($request);
    }

    public function getOnGoingBooking(Request $request) {
        return response()->json($this->bookingResponderService->getOnGoingBooking($request));
    }
}
