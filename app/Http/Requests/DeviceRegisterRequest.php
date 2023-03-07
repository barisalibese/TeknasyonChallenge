<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeviceRegisterRequest extends FormRequest
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
            'uId' => 'required',
            'appId' => 'required',
            'language' => 'required',
            'operating-system' => 'required'
        ];
    }

    public function messages(): array
    {
        return [
            'uId.required' => 'A uId is required',
            'appId.required' => 'A appId is required',
            'language.required' => 'A language is required',
            'operating-system.required' => 'A operating-system is required',
        ];
    }
}
