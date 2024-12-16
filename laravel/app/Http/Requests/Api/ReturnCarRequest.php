<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class ReturnCarRequest extends FormRequest
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
            'car_id' => 'required|exists:cars,id',
            'returned_latitude' => 'required|numeric',
            'returned_longitude' => 'required|numeric'
        ];
    }

    public function messages()
    {
        return [
            'car_id.required' => 'The car_id field is required.',
            'car_id.exists' => 'The specified car does not exist.',
            'returned_latitude.required' => 'The returned latitude is required.',
            'returned_latitude.numeric' => 'The returned latitude must be a number.',
            'returned_longitude.required' => 'The returned longitude is required.',
            'returned_longitude.numeric' => 'The returned longitude must be a number.',
        ];
    }
}
