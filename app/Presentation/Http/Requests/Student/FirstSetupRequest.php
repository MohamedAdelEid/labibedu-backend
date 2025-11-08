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
            'name' => ['required', 'string', 'max:255'],
            'age_group_id' => ['required', 'integer', 'exists:age_groups,id'],
            'gender' => ['required', 'in:male,female'],
        ];
    }
}

