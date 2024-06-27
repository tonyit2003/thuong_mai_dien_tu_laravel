<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
            'email' => 'required|string|email|unique:users|max:255', // unique:users: duy nhất trong bảng users
            'name' => 'required|string',
            'user_catalogue_id' => 'required|integer|gt:0', // gt:0 => value > 0
            'password' => 'required|string|min:6',
            're_password' => 'required|string|same:password',
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
            'user_catalogue_id.gt' => "Bạn chưa chọn nhóm thành viên.",
            'password.required' => "Bạn chưa nhập mật khẩu.",
            'password.string' => "Mật khẩu phải là một chuỗi ký tự.",
            'password.min' => "Mật khẩu phải có ít nhất :min ký tự.",
            're_password.required' => "Bạn chưa nhập lại mật khẩu.",
            're_password.string' => "Mật khẩu nhập lại phải là một chuỗi ký tự.",
            're_password.same' => "Mật khẩu nhập lại không khớp với mật khẩu đã nhập."
        ];
    }
}
