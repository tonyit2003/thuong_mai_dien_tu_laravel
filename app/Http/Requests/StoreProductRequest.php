<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
            'attribute' => 'required',
            'canonical' => 'required|unique:routers',
            'product_catalogue_id' => 'gt:0',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => __('toast.no_name'),
            'canonical.required' =>  __('toast.no_canonical'),
            'canonical.unique' => __('toast.canonical_use'),
            'product_catalogue_id.gt' => __('toast.no_root'),
            'attribute.required' => __('toast.not_enter_variant'),
        ];
    }
}
