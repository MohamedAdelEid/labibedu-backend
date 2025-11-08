<?php

namespace App\Presentation\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'language' => ['nullable', 'in:ar,en'],
            'theme' => ['nullable', 'in:minimal,blue,pink'],
            'notifications_enabled' => ['nullable', 'boolean'],
            'haptic_feedback_enabled' => ['nullable', 'boolean'],
        ];
    }
}

