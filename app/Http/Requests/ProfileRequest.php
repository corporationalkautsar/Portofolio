<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:120'],
            'profession' => ['required', 'string', 'max:160'],
            'biography' => ['nullable', 'string', 'max:6000'],
            'biography_id' => ['nullable', 'string', 'max:6000'],
            'biography_en' => ['nullable', 'string', 'max:6000'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,avif', 'max:4096'],
            'email' => ['required', 'email', 'max:150'],
            'whatsapp' => ['nullable', 'string', 'max:60'],
            'location' => ['nullable', 'string', 'max:120'],
        ];
    }
}
