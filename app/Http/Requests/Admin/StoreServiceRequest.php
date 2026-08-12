<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreServiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_id'      => ['required', 'exists:service_categories,id'],
            'title'            => ['required', 'string', 'max:255'],
            'slug'             => ['required', 'string', 'max:255', 'unique:services,slug'],
            'short_description'=> ['nullable', 'string', 'max:500'],
            'full_description' => ['nullable', 'string'],
            'icon'             => ['nullable', 'string', 'max:100'],
            'sort_order'       => ['nullable', 'integer'],
            'status'           => ['nullable', 'boolean'],
            'seo_title'        => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:255'],
            'canonical_url'    => ['nullable', 'url', 'max:255'],
            'image'            => ['nullable', 'image', 'mimes:jpeg,png,webp', 'max:2048'],
        ];
    }
}
