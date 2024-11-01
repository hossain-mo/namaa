<?php

namespace App\Http\Requests;

use App\DataProviderMappers\DataProviderFactory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserTransactionRequest extends FormRequest
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
        $providers = DataProviderFactory::allProviders();
        return [
            'provider' => ['string', Rule::in($providers)],
            'statusCode' => 'string|in:authorised,decline,refunded',
            'balanceMin' => 'numeric|min:0',
            'balanceMax' => 'numeric|min:0|gte:balanceMin',
            'currency' => 'string|size:3',
        ];
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        throw new \Illuminate\Validation\ValidationException($validator, response()->json([
            'errors' => $validator->errors(),
            'message' => 'Validation failed'
        ], 422));
    }
}
