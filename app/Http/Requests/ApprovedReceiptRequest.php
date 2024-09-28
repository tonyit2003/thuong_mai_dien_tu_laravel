<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApprovedReceiptRequest extends FormRequest
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
            'date_approved' => 'required',
            'actualQuantity.*' => 'required|gt:0'
        ];
    }

    public function messages(): array
    {
        return [
            'date_approved.required' => "Bạn không được để trống ngày giao.",
            'actualQuantity.*.required' => "Bạn không được để trống số lượng thực nhập.",
            'actualQuantity.*.gt' => "Số lượng thực nhập phải lớn hơn 0.",
        ];
    }
}
