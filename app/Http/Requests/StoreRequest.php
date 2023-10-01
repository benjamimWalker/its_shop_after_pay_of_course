<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required_if:method,POST', 'string', 'max:255'],
            'url' => ['required_if:method,POST', 'url', 'max:255'],
            'logo' => ['required_if:method,POST', 'url', 'max:255'],
            'address' => ['required_if:method,POST', 'string', 'max:255'],
            'owner_id' => ['required_if:method,POST', 'integer', 'exists:users,id']
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
