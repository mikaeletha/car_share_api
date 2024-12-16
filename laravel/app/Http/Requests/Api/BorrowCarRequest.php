<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Laravel\Sanctum\HasApiTokens;

class BorrowCarRequest extends FormRequest
{
    use HasApiTokens;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status' => false,
            'erros' => $validator->errors(),
        ], 422));
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
            'client_id' => 'required|exists:clients,id',
            'borrowed_at' => 'nullable|date|after_or_equal:today',
            'returned_at' => 'nullable|date|after:borrowed_at',
            'borrowed_latitude' => 'required|numeric',
            'borrowed_longitude' => 'required|numeric',
            'returned_latitude' => 'nullable|numeric',
            'returned_longitude' => 'nullable|numeric',
        ];
    }

    public function messages()
    {
        return [
            'car_id.required' => 'The car field is required.',
            'car_id.exists' => 'The specified car does not exist.',
            'client_id.required' => 'The client field is required.',
            'client_id.exists' => 'The specified client does not exist.',
            // 'borrowed_at.required' => 'The borrow date is required.',
            'borrowed_at.after_or_equal' => 'The borrow date must be today or a future date.',
            'returned_at.after' => 'The return date must be later than the borrow date.',
            'borrowed_latitude.required' => 'The borrowed latitude is required.',
            'borrowed_longitude.required' => 'The borrowed longitude is required.',
            'returned_latitude.numeric' => 'The returned latitude must be a number.',
            'returned_longitude.numeric' => 'The returned longitude must be a number.',
        ];
    }
}
