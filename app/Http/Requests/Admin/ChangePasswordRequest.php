<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;

class ChangePasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'current_password' => ['required', 'string', function ($attribute, $value, $fail) {
                if (!Hash::check($value, auth()->user()->password)) {
                    $fail('The current password is incorrect.');
                }
            }],
            'new_password' => ['required', 'string', 'min:8', 'confirmed', 'different:current_password'],
        ];
    }

    public function messages(): array
    {
        return [
            'current_password.required' => 'Please enter your current password.',
            'new_password.required'     => 'Please enter a new password.',
            'new_password.min'          => 'New password must be at least 8 characters.',
            'new_password.confirmed'    => 'Passwords do not match.',
            'new_password.different'    => 'New password must be different from the current one.',
        ];
    }
}
