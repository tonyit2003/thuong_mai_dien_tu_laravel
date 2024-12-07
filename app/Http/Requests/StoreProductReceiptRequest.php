<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductReceiptRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        $prices = array_map(function ($price) {
            if ($price !== null) {
                return convert_price($price);
            }
            return $price;
        }, $this->price ?? []);

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
            'product_id' => 'required',
            'quantityReceipt.*' => 'required|gt:0',
            'price.*' => 'required|gt:0'
        ];
    }

    public function messages(): array
    {
        return [
            'product_id.required' => __('toast.choose_product'),
            'quantityReceipt.*.required' => __('toast.choose_num_receipt'),
            'quantityReceipt.*.gt' => __('toast.num_receipt_min'),
            'price.*.required' => __('toast.no_price'),
            'price.*.gt' => __('toast.price_min')
        ];
    }
}

if (!function_exists('convert_price')) {
    function convert_price(string $price = '')
    {
        return str_replace('.', '', $price); // Ví dụ: loại bỏ dấu chấm trong giá
    }
}
