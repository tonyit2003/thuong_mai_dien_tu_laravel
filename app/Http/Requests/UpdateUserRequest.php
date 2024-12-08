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
            'email' => 'required|string|email|unique:users,email, ' . $this->id . '|max:255', // unique:users,email, ' . $this->id . ': duy nhất trong bảng users và bỏ qua kiểm tra duy nhất cho bản ghi hiện tại được xác định bởi $this->id
            'name' => 'required|string',
            'user_catalogue_id' => 'required|integer|gt:0', // gt:0 => value > 0
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' =>  __('toast.no_email'),
            'email.email' => __('toast.email_err'),
            'email.unique' => __('toast.email_use'),
            'email.max' => __('toast.email_max'),
            'name.required' => __('toast.no_name'),
            'user_catalogue_id.gt' => __('toast.choose_user_cata'),
        ];
    }
}
