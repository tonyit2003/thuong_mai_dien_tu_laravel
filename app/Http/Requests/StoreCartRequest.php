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
            'province_id' => 'gt:0',
            'district_id' => 'gt:0',
            'ward_id' => 'gt:0',
        ];
    }

    public function messages(): array
    {
        return [
            'fullname.required' => __('checkout.request.fullname_required'),
            'phone.required' => __('checkout.request.phone_required'),
            'email.required' => __('checkout.request.email_required'),
            'email.email' => __('checkout.request.email_email'),
            'province_id.gt' => __('checkout.request.province_gt'),
            'district_id.gt' => __('checkout.request.district_gt'),
            'ward_id.gt' => __('checkout.request.ward_gt'),
        ];
    }
}
