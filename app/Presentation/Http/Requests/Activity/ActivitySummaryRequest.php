<?php

namespace App\Presentation\Http\Requests\Activity;

use Illuminate\Foundation\Http\FormRequest;

class ActivitySummaryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {   
        return [
            'start_date' => 'required|date|before_or_equal:end_date',
            'end_date' => 'required|date|after_or_equal:start_date|before_or_equal:today',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'start_date.required' => __('validation.required', ['attribute' => 'start date']),
            'start_date.date' => __('validation.date', ['attribute' => 'start date']),
            'start_date.before_or_equal' => __('validation.before_or_equal', ['attribute' => 'start date', 'date' => 'end date']),
            'end_date.required' => __('validation.required', ['attribute' => 'end date']),
            'end_date.date' => __('validation.date', ['attribute' => 'end date']),
            'end_date.after_or_equal' => __('validation.after_or_equal', ['attribute' => 'end date', 'date' => 'start date']),
            'end_date.before_or_equal' => __('validation.before_or_equal', ['attribute' => 'end date', 'date' => 'today']),
        ];
    }
}
