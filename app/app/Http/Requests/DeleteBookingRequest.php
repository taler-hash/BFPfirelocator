<?php

namespace App\Http\Requests;

use App\Rules\StatusIsCurrent;
use App\Rules\CanAbleToDeleteUsingStatus;
use Illuminate\Foundation\Http\FormRequest;

class DeleteBookingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    //tiwasa ni mag himu kag sideeffect sa booking, ug booking responder sa event php artisan make:event modelname

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'id' => 'exists:bookings,id',
        ];
    }
}
