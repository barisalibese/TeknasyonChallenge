<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DevicePurchaseRequest extends FormRequest
{
    use BaseApiRequest;
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'receipt' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'receipt.required' => 'A receipt is required',
        ];
    }
}
