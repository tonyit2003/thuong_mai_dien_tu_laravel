<?php

namespace App\Http\Requests\Promotion;

use App\Enums\PromotionEnum;
use App\Rules\Promotion\OrderAmountRangeRule;
use App\Rules\Promotion\ProductAndQuantityRule;
use Illuminate\Foundation\Http\FormRequest;

class StorePromotionRequest extends FormRequest
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
        $rules = [
            'name' => 'required',
            'code' => 'required|unique:promotions',
            'startDate' => 'required|custom_date_format',
        ];
        if (!$this->input('neverEndDate')) {
            $rules['endDate'] = 'required|custom_date_format|custom_after:startDate';
        }
        $method = $this->input('method');
        switch ($method) {
            case PromotionEnum::ORDER_AMOUNT_RANGE:
                $rules['method'] = [new OrderAmountRangeRule($this->input('promotion_order_amount_range'))];
                break;

            case PromotionEnum::PRODUCT_AND_QUANTITY:
                $rules['method'] = [new ProductAndQuantityRule($this->only('product_and_quantity', 'object'))];
                break;
            default:
                // Trường method không được có giá trị là 'none'
                $rules['method'] = 'required|not_in:none';
                break;
        }
        return $rules;
    }

    public function messages(): array
    {
        $messages = [
            'name.required' => __('promotion.request.name_required'),
            'code.required' => __('promotion.request.code_required'),
            'code.unique' => __('promotion.request.code_unique'),
            'startDate.required' => __('promotion.request.startDate_required'),
            'startDate.custom_date_format' => __('promotion.request.startDate_custom_date_format'),
        ];

        $method = $this->input('method');
        if ($method === 'none') {
            $messages['method.not_in'] = __('promotion.request.method_not_in');
        }

        if (!$this->input('neverEndDate')) {
            $messages['endDate.required'] = __('promotion.request.endDate_required');
            $messages['endDate.custom_after'] = __('promotion.request.endDate_custom_after');
            $messages['endDate.custom_date_format'] = __('promotion.request.endDate_custom_date_format');
        }
        return $messages;
    }
}
