<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSupplierRequest extends FormRequest
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
            'email' => 'required|string|email|unique:suppliers,email, ' . $this->id . '|max:255',
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
            'email.required' => __('toast.no_email'),
            'email.email' => __('toast.email_err'),
            'email.unique' =>  __('toast.email_use'),
            'email.max' => __('toast.email_max'),
            'name.required' => __('toast.no_name'),
            'name.max' => __('toast.name_max'),
            'phone.required' => __('toast.no_phone'),
            'phone.string' => __('toast.phone_str'),
            'phone.max' => __('toast.phone_max'),
            'catalogue.required' => __('toast.choose_pro_cata'),
            'catalogue.array' => __('toast.no_pro_cata'),
            'catalogue.*.string' => __('toast.pro_cata_str')
        ];
    }
}
