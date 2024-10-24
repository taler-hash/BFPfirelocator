<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditStationRequest extends FormRequest
{
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
            'name' => ['required', 'unique:stations,name, '. $this->route('id')],
            'location' => ['required', 'unique:stations,location, '. $this->route('id')],
            'longitude' => ['required', 'unique:stations,longitude, '. $this->route('id')],
            'latitude' => ['required', 'unique:stations,latitude, '. $this->route('id')]
        ];
    }
}
