<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class StoreCarRequest extends FormRequest
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
            'model' => 'required|string|max:255',
            'manufacturer' => 'required|string|max:255',
            'year' => 'required|integer|digits:4|between:1900,' . date('Y'),
            'fuelType' => 'required|in:gasoline,ethanol,diesel,electric,hybrid',
            'userId' => 'required|exists:clients,id',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ];
    }

    public function messages()
    {
        return [
            'model.required' => 'O modelo do carro é obrigatório.',
            'manufacturer.required' => 'O fabricante do carro é obrigatório.',
            'year.required' => 'O ano do carro é obrigatório.',
            'year.digits' => 'O ano deve ter 4 dígitos.',
            'userId.exists' => 'O dono informado não existe.',
            'userId.required' => 'O id dono do carro é obrigatório.',
            'fuelType.in' => 'O tipo de combustível informado é inválido.',
            'latitude.required' => 'The latitude is required.',
            'latitude.numeric' => 'The latitude must be a number.',
            'longitude.required' => 'The longitude is required.',
            'longitude.numeric' => 'The longitude must be a number.',
        ];
    }
}
