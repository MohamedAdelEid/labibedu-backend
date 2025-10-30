<?php

namespace App\Presentation\Http\Requests\Library;

use Illuminate\Foundation\Http\FormRequest;

class GetLibraryBooksRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'scope' => 'nullable|in:all,mine,favorite',
            'level_id' => 'nullable|integer|exists:levels,id',
            'search' => 'nullable|string|max:255',
            'page' => 'nullable|integer|min:1',
            'per_page' => 'nullable|integer|min:1|max:100',
        ];
    }
}

