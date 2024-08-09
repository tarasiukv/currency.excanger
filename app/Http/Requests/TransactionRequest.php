<?php

namespace App\Http\Requests;

use App\Rules\ResultAmountRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class TransactionRequest extends FormRequest
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
            'user_id' => 'required|exists:users,id',
            'from_currency_id' => 'nullable|exists:currencies,id',
            'to_currency_id' => 'nullable|exists:currencies,id',
            'amount' => 'required|numeric|min:0',
            'rate' => 'required|numeric|min:0',
            'result_amount' => ['required', 'numeric', 'min:0', new ResultAmountRule($this->amount, $this->rate)],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'user_id.required' => 'The user ID is required.',
            'user_id.exists' => 'The selected user does not exist.',
            'from_currency_id.exists' => 'The selected from currency does not exist.',
            'to_currency_id.exists' => 'The selected to currency does not exist.',
            'to_currency_id.different' => 'The to currency must be different from the from currency.',
            'amount.required' => 'The amount is required.',
            'amount.numeric' => 'The amount must be a valid number.',
            'amount.min' => 'The amount must be at least 0.',
            'rate.required' => 'The exchange rate is required.',
            'rate.numeric' => 'The exchange rate must be a valid number.',
            'rate.min' => 'The exchange rate must be at least 0.',
            'result_amount.required' => 'The result amount is required.',
            'result_amount.numeric' => 'The result amount must be a valid number.',
            'result_amount.min' => 'The result amount must be at least 0.',
            'result_amount.match_result_amount' => 'The result amount does not match the expected value.',
        ];
    }

    /**
     * @param Validator $validator
     * @return mixed
     */
    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Validation error',
            'data' => $validator->errors(),
        ]));
    }
}
