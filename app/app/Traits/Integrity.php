<?php

namespace App\Traits;

trait Integrity
{
    private function checkIntegrity($model, $request, $callback, $options) {
        $booking = $model::find($request->id);
        $matchedBooking = true;
        $integrity = $options['integrity'] ?? false;
        $exist = $options['exist'] ?? false;
        $toExcludeInputs = $options['toExcludeInputs'] ?? false;
        $client = $options['client'] ?? false;

        if($booking && $integrity) {
            foreach($booking->getAttributes() as $key => $value) {
                $excludedInputs = ['created_at', 'updated_at'];

                if($toExcludeInputs && is_array($toExcludeInputs)) {
                    $excludedInputs = array_merge($excludedInputs, $toExcludeInputs);
                }
    
                if(!in_array($key, $excludedInputs) && $request->input($key) != $value) {
                    $matchedBooking = false;
                    break;
                } 
            }
        }
        if(($exist || $integrity) && ($booking && $matchedBooking)) {
            return $callback();
        } else {
            if($client === 'axios') {
                return response()->json(['message' => 'current booking is outdated'], 422);
            }
            return redirect()->back()->withErrors(['message' => 'current booking is outdated'])->withInput();
        }
    }
}
