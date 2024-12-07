<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMenuCatalogueRequest extends FormRequest
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
            'keyword' => 'required|unique:menu_catalogues'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => __('toast.name_location'),
            'keyword.required' => __('toast.no_key'),
            'keyword.unique' => __('toast.key_use'),
        ];
    }
}
