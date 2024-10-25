<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCartRequest extends FormRequest
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
            'fullname' => 'required',
            'phone' => 'required',
            'email' => 'required|email',
        ];
    }

    public function messages(): array
    {
        return [
            'fullname.required' => __('checkout.request.fullname_required'),
            'phone.required' => __('checkout.request.phone_required'),
            'email.required' => __('checkout.request.email_required'),
            'email.email' => __('checkout.request.email_email'),
        ];
    }
}
