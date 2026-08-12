<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTestimonialRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'client_name'      => ['required', 'string', 'max:255'],
            'client_position'  => ['nullable', 'string', 'max:255'],
            'company'          => ['nullable', 'string', 'max:255'],
            'content'          => ['required', 'string'],
            'rating'           => ['nullable', 'integer', 'min:1', 'max:5'],
            'sort_order'       => ['nullable', 'integer'],
            'status'           => ['nullable', 'boolean'],
            'photo'            => ['nullable', 'image', 'mimes:jpeg,png,webp', 'max:2048'],
        ];
    }
}
