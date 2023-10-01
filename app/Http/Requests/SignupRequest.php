<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SignupRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:5']
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
