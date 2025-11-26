<?php

namespace App\Presentation\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;

class FirstSetupRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['nullable', 'string', 'max:255'],
            'age_group_id' => ['nullable', 'integer', 'exists:age_groups,id'],
            'gender' => ['nullable', 'in:male,female'],
        ];
    }
}

