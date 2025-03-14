<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePermissionRequest extends FormRequest
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
            'canonical' => 'required|unique:permissions,canonical,' . $this->id . ''
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => __('toast.no_per'),
            'canonical.required' => __('toast.no_canonical'),
            'canonical.unique' => __('toast.canonical_use'),
        ];
    }
}
