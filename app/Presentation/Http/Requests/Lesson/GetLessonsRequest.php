<?php

namespace App\Presentation\Http\Requests\Lesson;

use Illuminate\Foundation\Http\FormRequest;

class GetLessonsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'subject_id' => 'required|integer|exists:subjects,id',
            'search' => 'nullable|string|max:255',
            'page' => 'nullable|integer|min:1',
            'per_page' => 'nullable|integer|min:1|max:100',
        ];
    }
}

