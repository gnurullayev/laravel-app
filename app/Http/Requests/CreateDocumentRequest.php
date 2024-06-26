<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateDocumentRequest extends FormRequest
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
            'document_name' => "string|required|max:255",
            'form_values.*.field_seq' => 'integer|nullable',
            'form_values.*.is_mandatory' => 'integer|nullable',
            'form_values.*.field_type' => 'integer|nullable',
            'form_values.*.field_name' => 'string|nullable|max:255',
            'form_values.*.select_values' => 'nullable|array',
        ];
    }
}
