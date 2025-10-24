<?php

namespace App\Presentation\Http\Requests\Avater;

use Illuminate\Foundation\Http\FormRequest;

class CreateAvatarRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'category_id' => 'required|integer|exists:avatar_categories,id',
            'avatar' => 'required|image|mimes:jpeg,png,gif,webp,svg|max:5120', // 5MB max
            'coins' => 'integer|min:0',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'avatar.required' => 'An avatar image is required.',
            'avatar.image' => 'The uploaded file must be an image.',
            'avatar.mimes' => 'The avatar must be a file of type: jpeg, png, gif, webp, svg.',
            'avatar.max' => 'The avatar may not be greater than 5MB.',
            'coins.integer' => 'The coins field must be an integer.',
            'coins.min' => 'The coins field must be at least 0.',
        ];
    }
}
