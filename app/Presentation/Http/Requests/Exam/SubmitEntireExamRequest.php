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
            'exam_training_id' => 'required|integer|exists:exams_trainings,id',
        ];
    }
}
