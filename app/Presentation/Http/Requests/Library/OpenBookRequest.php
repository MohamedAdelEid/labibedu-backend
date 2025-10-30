<?php

namespace App\Presentation\Http\Requests\Library;

use Illuminate\Foundation\Http\FormRequest;

class OpenBookRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'page_id' => 'nullable|integer|exists:pages,id',
        ];
    }
}

