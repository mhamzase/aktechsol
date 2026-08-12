<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'             => ['required', 'string', 'max:255'],
            'slug'              => ['nullable', 'string', 'max:255', Rule::unique('projects')->ignore($this->route('project')?->id)],
            'short_description' => ['nullable', 'string', 'max:500'],
            'full_description'  => ['nullable', 'string'],
            'client_name'       => ['nullable', 'string', 'max:255'],
            'project_url'       => ['nullable', 'url', 'max:255'],
            'completion_date'   => ['nullable', 'date'],
            'sort_order'        => ['nullable', 'integer'],
            'status'            => ['nullable', 'boolean'],
            'seo_title'         => ['nullable', 'string', 'max:255'],
            'meta_description'  => ['nullable', 'string', 'max:255'],
            'canonical_url'     => ['nullable', 'url', 'max:255'],
            'featured_image'    => ['nullable', 'image', 'mimes:jpeg,png,webp', 'max:2048'],
            'gallery_images'    => ['nullable', 'array'],
            'gallery_images.*'  => ['image', 'mimes:jpeg,png,webp', 'max:2048'],
        ];
    }
}
