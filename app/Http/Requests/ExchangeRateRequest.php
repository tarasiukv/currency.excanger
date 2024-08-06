<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExchangeRateRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
//            'date' => 'required|date_format:Y-m-d H:i:s',
            'rate' => 'required|numeric',
            'from_currency_id' => 'required|string|max:3',
            'to_currency_id' => 'required|string|max:3',
        ];
    }

    public function messages()
    {
        return [

        ];
    }
}
