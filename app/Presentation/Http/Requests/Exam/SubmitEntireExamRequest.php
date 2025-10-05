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
            'exam_training_id' => ['required', 'integer', 'exists:exams_trainings,id'],
            'answers' => ['required', 'array', 'min:1'],
            'answers.*.question_id' => ['required', 'integer', 'exists:questions,id'],
            'answers.*.user_answer' => ['nullable', 'string'],
            'answers.*.selections' => ['nullable', 'array'],
            'answers.*.selections.*.option_id' => ['required_without:answers.*.selections.*.left_option_id', 'integer', 'exists:question_options,id'],
            'answers.*.selections.*.left_option_id' => ['required_with:answers.*.selections.*.right_option_id', 'integer', 'exists:question_options,id'],
            'answers.*.selections.*.right_option_id' => ['required_with:answers.*.selections.*.left_option_id', 'integer', 'exists:question_options,id'],
            'answers.*.selections.*.order' => ['nullable', 'integer'],
        ];
    }
}