<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMenuChildrenRequest extends FormRequest
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
            // kiểm tra thuộc tính name của mảng menu trong dữ liệu đầu vào là bắt buộc (required).
            'menu.name' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'menu.name.required' => "Bạn phải tạo ít nhất một menu.",
        ];
    }
}
