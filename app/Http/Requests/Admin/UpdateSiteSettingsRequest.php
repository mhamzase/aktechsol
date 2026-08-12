<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSiteSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'site_name'       => ['required', 'string', 'max:255'],
            'site_email'      => ['required', 'email', 'max:255'],
            'site_phone'      => ['nullable', 'string', 'max:50'],
            'site_address'    => ['nullable', 'string', 'max:500'],
            'footer_text'     => ['nullable', 'string', 'max:500'],
            'copyright_text'  => ['nullable', 'string', 'max:255'],
            'facebook_url'    => ['nullable', 'url', 'max:255'],
            'twitter_url'     => ['nullable', 'url', 'max:255'],
            'linkedin_url'    => ['nullable', 'url', 'max:255'],
            'instagram_url'   => ['nullable', 'url', 'max:255'],
            'logo'            => ['nullable', 'image', 'mimes:jpeg,png,webp,svg', 'max:2048'],
            'favicon'         => ['nullable', 'image', 'mimes:ico,png,svg', 'max:1024'],
        ];
    }
}
