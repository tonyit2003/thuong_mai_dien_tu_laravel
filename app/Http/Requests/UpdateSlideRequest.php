<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSlideRequest extends FormRequest
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
            'keyword' => 'required|unique:slides,keyword, ' . $this->id . '',
            'short_code' => 'unique:slides,short_code, ' . $this->id . '',
            'slide.image' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => __('toast.no_name_slide'),
            'keyword.required' => __('toast.no_key_slide'),
            'keyword.unique' => __('toast.key_slide_use'),
            'short_code.unique' => __('toast.short_code_use'),
            'slide.image.required' => __('toast.choose_slide'),
        ];
    }
}
