<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if (! $this->filled('slug')) {
            $this->merge([
                'slug' => $this->route('project')?->slug ?: Str::slug((string) $this->input('title')),
            ]);
        }
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $projectId = $this->route('project')?->id;

        return [
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'title' => ['required', 'string', 'max:160'],
            'slug' => ['required', 'string', 'max:180', Rule::unique('projects', 'slug')->ignore($projectId)],
            'description' => ['required', 'string', 'max:5000'],
            'stack' => ['nullable', 'string', 'max:255'],
            'thumbnail' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,avif', 'max:4096'],
            'project_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,avif', 'max:4096'],
            'project_url' => ['nullable', 'url', 'max:255'],
            'year' => ['required', 'integer', 'digits:4', 'min:1990', 'max:'.(now()->year + 2)],
            'featured' => ['sometimes', 'boolean'],
            'status' => ['required', Rule::in(['draft', 'published'])],
        ];
    }
}
