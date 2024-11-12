<?php

namespace App\Services;

use App\Events\BookingMapUpdatedEvent;
use App\Models\Booking;
use App\Models\BookingResponder;
use Illuminate\Validation\ValidationException;
use App\Traits\Integrity;
use Illuminate\Support\Facades\Cache;
use Spatie\Activitylog\Models\Activity;

class BookingService {
    use Integrity;

    public function getBookings($request) {
        $model = new Booking();

        $bookings = Booking::with(['station', 'responders'])
        ->when($request?->ownerId, function($q) use ($request) {
            $q->owned($request->ownerId);
        })
        ->wherehas('station', function ($q) use ($request) {
            $q->when($request->stationId, function ($q2) use ($request) {
                $q2->where('id', $request->stationId);
            });
        })
        ->when($request?->status, function($q) use ($request) {
            $q->whereIn('status', $request->status);
        })
        ->when($request->role === 'responder', function ($q) use ($request) {
            $q->whereHas('responders', function($q2) use ($request) {
                $q2->where('user_id', $request->user()->id);
            });
        })
        ->whereAny($model->getFillable(), 'LIKE', "%{$request->searchString}%")
        ->orderBy($request->sortBy, $request->sortType)
        ->paginate($request->rows);

        return $bookings;
    }

    public function getBooking($request) {
        return $this->checkIntegrity(Booking::class, $request, function () use ($request) {
            $booking = Booking::with(['responders.user.station', 'responders.user.roles', 'station'])->find($request->id);

            return response()->json($booking);
        }, 
        ['integrity' => true, 'exist' => true, 'client' => 'axios' ]);
    }

    public function getBookingLogs($request) {
        return response()->json(Activity::where('subject_type', Booking::class)
        ->where('subject_id', $request->id)
        ->with(['causer.station', 'subject'])
        ->latest()
        ->get());
    }

    public function storeBooking($request) {
        $request->user()->bookings()->create($request->all());
    }

    public function editBooking($request) {
        $this->checkIntegrity(Booking::class, $request, function () use ($request) {
            $this->statusCallbacks($request);
            $this->hasEditBooking($request);
            $hasChangedStation = $this->changedStation($request);
            // dd($request->except([$hasChangedStation ? 'status' : '']), $hasChangedStation);
            Booking::find($request->id)->update($request->except([$hasChangedStation ? 'status' : '']));
        }, ['exist' => true, 'integrity' => true, 'toExcludeInputs' => ['status', 'station_id'] ]);
    }

    public function deleteBooking($request) {
        $this->checkIntegrity(Booking::class, $request, function () use ($request) {
            Booking::find($request->id)->delete();
        }, 
        ['integrity' => true, 'exist' => true ]);
    }

    public function sendCoords($request) {

        $key = 'booking.'.$request->booking['id'];
        $bookingData = Cache::get($key);

        if(!$bookingData) {
            Cache::remember($key, 10, function () use ($request) {
                return json_encode($request->user()->toArray());
            });
        } else {
            $assignedResponder = json_decode(Cache::get($key));

            if($assignedResponder->id === $request->user()->id) {
                event(new BookingMapUpdatedEvent($request->booking, (object)$request->coords));
            }
        }
    }

    public function unsetResponderCoords($request) {
        $key = 'booking.'.$request->booking['id'];

        Cache::forget($key);
    }

    public function getBookingCounts($request) {
        $user = auth()->user();

        return Booking::selectRaw('status, COUNT(*) as count')
        ->when($user->role === 'admin_staff', function($q) use ($user) {
            $q->where('station_id', $user->station_id);
        })
        ->when($user->role === 'brgy_staff', function($q) use ($user) {
            $q->where('user_id', $user->id);
        })
        ->groupBy('status')
        ->get();
    }


    private function statusCallbacks($request) {
        $statusCallback = [
            'pending' => function () use ($request) {
                Booking::find($request->id)->responders()->delete();
            },
            'completed' => function () use ($request) {
                Cache::pull('booking.'.$request->id);
                BookingResponder::where('booking_id', $request->id)->each(function ($bookingResponder) {
                    $bookingResponder->user()->update(['status' => null]);
                });
            }
        ];

        if(isset($statusCallback[$request->status])) {
            $statusCallback[$request->status]();
        }
    }

    private function hasEditBooking($request) {
        if($request?->event) {
            Cache::pull('booking.'.$request->id);
            event(new BookingMapUpdatedEvent($request->all(), null));
        }
    }

    private function changedStation($request) {
        $booking = Booking::find($request->id);

        $booking->station_id = $request->station_id;

        if($booking->isDirty('station_id')) {
            $booking->status = 'pending';
            $booking->save();

            BookingResponder::where('booking_id', $request->id)->each(function ($bookingResponder) {
                $bookingResponder->delete();
            });

            return true;
        }

        return false;
    }
}