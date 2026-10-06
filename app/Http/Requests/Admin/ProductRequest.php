<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:160'],
            'description' => ['required', 'string'],
            'price' => ['required', 'decimal:0,2', 'between:0,99999999.99'],
            'stock' => ['required', 'integer', 'between:0,2147483647'],
            'image' => ['nullable', File::image()->types(['jpeg', 'png', 'webp'])->max('2mb')],
            'is_active' => ['nullable', 'boolean'],
            'is_featured' => ['nullable', 'boolean'],
            'slug' => ['prohibited'],
            'image_path' => ['prohibited'],
        ];
    }
}
