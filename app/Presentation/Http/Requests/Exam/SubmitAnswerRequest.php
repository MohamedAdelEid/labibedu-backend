<?php

namespace App\Presentation\Http\Requests\Exam;

use Illuminate\Foundation\Http\FormRequest;

class SubmitAnswerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'question_id' => ['required', 'integer', 'exists:questions,id'],
            'user_answer' => ['nullable', 'string'],
            'selections' => ['required', 'array', 'min:1'],
            'selections.*.option_id' => ['required_without:selections.*.left_option_id', 'integer', 'exists:question_options,id'],
            'selections.*.left_option_id' => ['required_with:selections.*.right_option_id', 'integer', 'exists:question_options,id'],
            'selections.*.right_option_id' => ['required_with:selections.*.left_option_id', 'integer', 'exists:question_options,id'],
            'selections.*.order' => ['nullable', 'integer'],
        ];
    }
}