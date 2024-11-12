<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShowBookingRequest extends FormRequest
{
    protected function prepareForValidation()
    {
        // Assuming you want to set 'reason' based on some condition
        $this->merge([
            'id' => request()->route('id'), // You can replace this with your logic
        ]);
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'id' => 'exists:bookings,id'
        ];
    }
}
