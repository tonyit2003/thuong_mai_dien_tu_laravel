<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCustomerRequest extends FormRequest
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
            'email' => 'required|string|email|unique:customers,email, ' . $this->id . '|max:255',
            'name' => 'required|string',
            'customer_catalogue_id' => 'required|integer|gt:0',
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
            'customer_catalogue_id.gt' =>  __('toast.choose_cus_cata')
        ];
    }
}
