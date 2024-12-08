<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreWidgetRequest extends FormRequest
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
            'keyword' => 'required|unique:widgets',
            'short_code' => 'unique:widgets',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => __('toast.name_widget'),
            'keyword.required' => __('toast.no_widget'),
            'keyword.unique' => __('toast.widget_use'),
            'short_code.unique' => __('toast.short_widget_use'),
        ];
    }
}
