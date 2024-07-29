<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreGenerateRequest extends FormRequest
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
            'name' => 'required|unique:generates',
            'schema' => 'required',
            // 'moduleType' => 'gt:0'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => "Bạn chưa nhập tên module",
            'name.unique' => "Tên module đã tồn tại",
            'schema.required' => "Bạn chưa nhập schema của module.",
            // 'moduleType.gt' => "Bạn chưa chọn loại module.",
        ];
    }
}
