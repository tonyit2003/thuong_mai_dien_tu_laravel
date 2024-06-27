<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
            'email' => 'required|string|email|unique:users,email, ' . $this->id . '|max:255', // unique:users,email, ' . $this->id . ': duy nhất trong bảng users nhưng không kiểm tra tính duy nhất so với chính nó.
            'name' => 'required|string',
            'user_catalogue_id' => 'required|integer|gt:0', // gt:0 => value > 0
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => "Bạn chưa nhập địa chỉ email.",
            'email.email' => "Địa chỉ email không đúng định dạng.",
            'email.unique' => "Địa chỉ email đã được sử dụng.",
            'email.max' => "Địa chỉ email không được vượt quá :max ký tự.",
            'name.required' => "Bạn chưa nhập tên.",
            'user_catalogue_id.gt' => "Bạn chưa chọn nhóm thành viên."
        ];
    }
}
