<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ShowUserRequest extends FormRequest
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
            'sort_by' =>  [
                'nullable',
                'string',
                'max:50',
                Rule::in(['id', 'name', 'email', 'role']),
            ],
            'sort_order' => [
                'nullable',
                'string',
                'max:5',
                Rule::in(['asc', 'desc']),
            ],
        ];
    }
}
