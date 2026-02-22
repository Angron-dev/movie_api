<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ListRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'per_page' => 'nullable|integer|min:5|max:50'
        ];
    }

    public function getPerPage(): int
    {
        return $this->input('per_page', 20);
    }
}
