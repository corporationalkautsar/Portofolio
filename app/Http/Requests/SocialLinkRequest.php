<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SocialLinkRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'platform' => ['required', 'string', 'max:60', Rule::unique('social_links', 'platform')->ignore($this->route('social_link')?->id)],
            'url' => ['required', 'url', 'max:255'],
        ];
    }
}
