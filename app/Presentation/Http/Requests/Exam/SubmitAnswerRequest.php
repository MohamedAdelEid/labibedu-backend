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
            'question_id' => 'required|integer|exists:questions,id',
            'selected_option_ids' => 'nullable|array',
            'selected_option_ids.*' => 'integer|exists:question_options,id',
            'true_false_answer' => 'nullable|boolean',
            'connect_pairs' => 'nullable|array',
            'connect_pairs.*.left_option_id' => 'required_with:connect_pairs|integer|exists:question_options,id',
            'connect_pairs.*.right_option_id' => 'required_with:connect_pairs|integer|exists:question_options,id',
            'arrange_option_ids' => 'nullable|array',
            'arrange_option_ids.*' => 'integer|exists:question_options,id',
            'written_answer' => 'nullable|string',
            'time_spent' => 'nullable|integer|min:0',
        ];
    }
}
