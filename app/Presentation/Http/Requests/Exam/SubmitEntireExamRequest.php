<?php

namespace App\Presentation\Http\Requests\Exam;

use Illuminate\Foundation\Http\FormRequest;

class SubmitEntireExamRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'source' => 'nullable|string|in:journey,assignment',
            'sourceId' => 'nullable|integer',
        ];
    }
}

