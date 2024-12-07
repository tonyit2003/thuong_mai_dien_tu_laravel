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
            'email.required' => __('toast.no_email'),
            'email.email' => __('toast.email_err'),
            'email.unique' => __('toast.email_use'),
            'email.max' => __('toast.email_max'),
            'name.required' => __('toast.no_name'),
            'user_catalogue_id.gt' => __('toast.choose_user_cata'),
            'password.required' => __('toast.no_pass'),
            'password.string' => __('toast.pass_no_str'),
            'password.min' => __('toast.pass_min'),
            're_password.required' => __('toast.no_repass'),
            're_password.string' =>  __('toast.repass_no_str'),
            're_password.same' => __('toast.pass_not_repass')
        ];
    }
}
