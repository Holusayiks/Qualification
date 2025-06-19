<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderBySupplierRequest extends FormRequest
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
            'product' => 'required',
            'weigth' => 'required',
            'car_type' => 'required',
            'point_A' => 'required',
            'point_B' => 'required',
            'date_of_start' => 'required',
        ];
    }
}
