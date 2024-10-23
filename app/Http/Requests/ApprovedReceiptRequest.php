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
            'actualQuantity.*' => [
                'required',
                'gt:0',
                function ($attribute, $value, $fail) {
                    // Lấy số lượng đã định từ dữ liệu được gửi
                    $index = (int) filter_var($attribute, FILTER_SANITIZE_NUMBER_INT); // Lấy chỉ số của mảng
                    $quantity = $this->input('quantity.' . $index); // Truy cập số lượng theo chỉ số

                    // Kiểm tra nếu số lượng thực nhập lớn hơn số lượng đã định
                    if ($value > $quantity) {
                        $fail('Số lượng thực nhập không được lớn hơn số lượng.');
                    }
                },
            ],
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
