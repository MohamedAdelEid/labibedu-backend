<?php

namespace App\Presentation\Http\Requests\AvatarCategory;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAvatarCategoryRequest extends FormRequest
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
        $categoryId = $this->route('id');

        return [
            'name_en' => [
                'sometimes',
                'string',
                'max:255',
                Rule::unique('avatar_categories', 'name_en')->ignore($categoryId)
            ],
            'name_ar' => 'sometimes|string|max:255',
            'is_active' => 'sometimes|boolean',
            'sort_order' => 'sometimes|integer|min:0',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name_en.unique' => __('validation.unique', ['attribute' => __('attributes.name_en')]),
        ];
    }
}
