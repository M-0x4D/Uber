<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required',
            'phone' => 'required|unique:clients|digits:10',
        ];
    }

    public function messages()
{
    return [
        'name.required' => 'A name is required',
        'phone.required' => 'A phone is required',
        'phone.unique' => 'A phone number should be unique',
        'phone.digits' => 'A phone number should be 10 digits',
    ];
}


}
