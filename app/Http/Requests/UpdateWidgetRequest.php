<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateWidgetRequest extends FormRequest
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
            'keyword' => 'required|unique:widgets,keyword, ' . $this->id . '',
            'short_code' => 'unique:widgets,short_code, ' . $this->id . '',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => "Bạn chưa nhập tên widget.",
            'keyword.required' => "Bạn chưa nhập từ khóa widget.",
            'keyword.unique' => "Từ khóa widget đã tồn tại.",
            'short_code.unique' => "Short code widget đã tồn tại.",
        ];
    }
}
