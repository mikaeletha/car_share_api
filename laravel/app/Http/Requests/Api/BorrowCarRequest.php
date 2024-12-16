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
            'borrowed_latitude' => 'required|numeric',
            'borrowed_longitude' => 'required|numeric',
        ];
    }

    public function messages()
    {
        return [
            'car_id.required' => 'The car field is required.',
            'car_id.exists' => 'The specified car does not exist.',
            'client_id.required' => 'The client field is required.',
            'client_id.exists' => 'The specified client does not exist.',
            'borrowed_latitude.required' => 'The borrowed latitude is required.',
            'borrowed_longitude.required' => 'The borrowed longitude is required.',
        ];
    }
}
