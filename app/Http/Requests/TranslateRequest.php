<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TranslateRequest extends FormRequest
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
            'translate_name' => 'required',
            'translate_canonical' => 'required|unique:routers,canonical'
        ];
    }

    public function messages(): array
    {
        return [
            'translate_name.required' => "Bạn chưa nhập tên tiêu đề.",
            'translate_canonical.required' => "Bạn chưa nhập đường dẫn.",
            'translate_canonical.unique' => "Đường dẫn đã tồn tại.",
        ];
    }
}
