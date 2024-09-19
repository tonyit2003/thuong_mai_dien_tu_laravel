<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSupplierRequest extends FormRequest
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
            'email' => 'required|string|email|unique:suppliers|max:255',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'catalogue' => 'required|array',
            'catalogue.*' => 'string'
        ];
    }

    /**
     * Get custom error messages for validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'email.required' => "Bạn chưa nhập địa chỉ email.",
            'email.email' => "Địa chỉ email không đúng định dạng.",
            'email.unique' => "Địa chỉ email đã được sử dụng.",
            'email.max' => "Địa chỉ email không được vượt quá :max ký tự.",
            'name.required' => "Bạn chưa nhập tên.",
            'name.max' => "Tên không được vượt quá :max ký tự.",
            'phone.required' => "Bạn chưa nhập số điện thoại.",
            'phone.string' => "Số điện thoại phải là chuỗi ký tự.",
            'phone.max' => "Số điện thoại không được vượt quá :max ký tự.",
            'catalogue.required' => "Bạn chưa chọn nhóm danh mục.",
            'catalogue.array' => "Danh mục không hợp lệ.",
            'catalogue.*.string' => "Mỗi danh mục phải là chuỗi ký tự hợp lệ."
        ];
    }
}
