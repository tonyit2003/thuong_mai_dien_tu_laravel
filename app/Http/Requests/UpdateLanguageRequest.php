<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLanguageRequest extends FormRequest
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
            'name' => 'required',
            'canonical' => 'required|unique:languages,canonical,' . $this->id . ''
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => __('toast.name_lang'),
            'canonical.required' => __('toast.lang_name'),
            'canonical.unique' => __('toast.keyword_exit'),
        ];
    }
}
