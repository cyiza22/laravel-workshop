<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTodoRequest extends FormRequest
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
            'title' => 'required|unique:todos,title|max:25',
            'description' => 'nullable|string|regex:/[a-zA-Z]/',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'The title field is required.',
            'title.unique' => 'Iri zina barifashe shaka irindi.',
            'title.max' => 'The title may not be greater than 25 characters.'
        ];
    }
}
