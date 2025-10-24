<?php

namespace App\Presentation\Http\Requests\AvatarCategory;

use Illuminate\Foundation\Http\FormRequest;

class CreateAvatarCategoryRequest extends FormRequest
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
            'name_en' => 'required|string|max:255|unique:avatar_categories,name_en',
            'name_ar' => 'required|string|max:255',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name_en.required' => __('validation.required', ['attribute' => __('attributes.name_en')]),
            'name_en.unique' => __('validation.unique', ['attribute' => __('attributes.name_en')]),
            'name_ar.required' => __('validation.required', ['attribute' => __('attributes.name_ar')]),
        ];
    }
}
