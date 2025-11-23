<?php

namespace App\Presentation\Http\Requests\Subject;

use Illuminate\Foundation\Http\FormRequest;

class GetSubjectsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'grade_id' => 'sometimes|required_without:user_id|integer|exists:grades,id',
            'user_id' => 'sometimes|required_without:grade_id|integer|exists:users,id',
        ];
    }
}

