<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePostCatalogueRequest extends FormRequest
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
            'canonical' => 'required|unique:routers,canonical,' . $this->id . ',module_id'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => __('toast.no_post'),
            'canonical.required' => __('toast.no_url'),
            'canonical.unique' => __('toast.url_use')
        ];
    }
}
