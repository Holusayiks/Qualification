<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AutoRequest extends FormRequest
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
            'nomer' => 'required|string',
            'type' => 'required|string',
            'lifting_weight' => 'required|numeric|min:600|max:20000',
        ];
    }
}
