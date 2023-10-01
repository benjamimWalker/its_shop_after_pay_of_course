<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
            'name' => ['required_if:method,POST', 'string', 'max:255'],
            'email' => ['required_if:method,POST', 'email', 'string', 'max:255'],
            'password' => ['required_if:method,POST', 'string', 'max:255']
        ];
    }
}
