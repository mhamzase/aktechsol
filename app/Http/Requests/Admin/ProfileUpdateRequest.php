<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ProfileUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'  => ['required', 'string', 'min:3', 'max:255'],
            'email' => ['required', 'string', 'email:rfc', 'max:255', 'unique:users,email,' . auth()->id()],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'  => 'Please enter your name.',
            'name.min'       => 'Name must be at least 3 characters.',
            'email.required' => 'Please enter your email address.',
            'email.email'    => 'Please enter a valid email address.',
            'email.unique'   => 'This email is already taken by another account.',
        ];
    }
}
