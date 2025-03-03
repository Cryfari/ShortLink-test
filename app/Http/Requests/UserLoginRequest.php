<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Client\HttpClientException;

class UserLoginRequest extends FormRequest
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
            'username'=>'required|string|min:5|max:50',
            'password'=> 'required|string|min:8'
        ];
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        throw new HttpClientException(response([
            "errors" => $validator->errors()
        ], 400));
    }

    public function messages(){
        return [
            'username.required'=>'Username required',
            'username.min'=>'Username at least 5 characters',
            'username.max' => 'Username at most 50 characters',
            'password.required'=>'Password required',
            'password.min'=>'Password at least 8 characters'
        ];
    }
}
