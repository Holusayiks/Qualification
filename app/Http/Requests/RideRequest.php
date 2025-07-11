<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RideRequest extends FormRequest
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
            'driver_id' => 'required',
            'points' => 'required',
            'distance' => 'required',
            'vutratu' => 'required',
            'weigth' => 'required',
            'station_start' => 'required',
            'station_end' => 'required',
        ];
    }
}
