<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreWarrantyRequest extends FormRequest
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
            'product_id' => 'required|array',
            'variant_uuid' => 'required|array',
            'notes' => 'array',
        ];
    }

    public function messages(): array
    {
        return [
            'product_id.required' => 'Vui lòng chọn ít nhất một sản phẩm.',
        ];
    }

    protected function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $productIds = $this->input('product_id', []);
            $notes = $this->input('notes', []);

            foreach ($productIds as $index => $productId) {
                // Kiểm tra nếu product_id được chọn mà notes trống
                if (isset($productId) && empty($notes[$index])) {
                    $validator->errors()->add("notes.$index", 'Ghi chú không được bỏ trống khi sản phẩm được chọn.');
                }
            }
        });
    }
}
