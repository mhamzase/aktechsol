<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBlogCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'       => ['required', 'string', 'max:255'],
            'slug'       => ['nullable', 'string', 'max:255', Rule::unique('blog_categories')->ignore($this->route('blog_category')?->id)],
            'sort_order' => ['nullable', 'integer'],
            'status'     => ['nullable', 'boolean'],
        ];
    }
}
