<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductReceiptRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        // Dùng hàm convert_price để chuyển đổi giá trị của price trước khi validate
        $prices = array_map(function ($price) {
            return convert_price($price); // Gọi hàm convert_price để xử lý giá
        }, $this->price ?? []);

        // Cập nhật lại giá trị price sau khi đã chuyển đổi
        $this->merge([
            'price' => $prices,
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */

    public function rules(): array
    {
        return [

            'quantityReceipt.*' => 'required|gt:0',
            'price.*' => 'required|gt:0'
        ];
    }

    public function messages(): array
    {
        return [
            'quantityReceipt.*.required' => "Bạn chưa nhập số lượng nhập.",
            'quantityReceipt.*.gt' => "Số lượng nhập sản phẩm phải lớn hơn 0.",
            'price.*.required' => "Bạn chưa nhập giá nhập.",
            'price.*.gt' => "Giá sản phẩm phải lớn hơn 0.",
        ];
    }
}

if (!function_exists('convert_price')) {
    function convert_price(string $price = '')
    {
        return str_replace('.', '', $price); // Ví dụ: loại bỏ dấu chấm trong giá
    }
}
