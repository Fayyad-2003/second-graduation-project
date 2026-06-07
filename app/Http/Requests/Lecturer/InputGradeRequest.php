<?php

namespace App\Http\Requests\Lecturer;

use Illuminate\Foundation\Http\FormRequest;

class InputGradeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->hasRole('lecturer');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'grades' => 'required|array',
            'grades.*' => 'nullable|numeric|min:0|max:100',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages()
    {
        return [
            'grades.required' => __('Grades data is required'),
            'grades.array' => __('Grades must be an array'),
            'grades.*.numeric' => __('Grade must be a number'),
            'grades.*.min' => __('Grade cannot be less than :min', ['min' => 0]),
            'grades.*.max' => __('Grade cannot be more than :max', ['max' => 100]),
        ];
    }
}
