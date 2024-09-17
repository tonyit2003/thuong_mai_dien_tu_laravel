<?php

namespace App\Rules\Promotion;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ProductAndQuantityRule implements ValidationRule
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($this->data['product_and_quantity']['quantity'] == 0) {
            $fail(__('promotion.request.quantity_fail'));
        }

        if ($this->data['product_and_quantity']['discountValue'] == 0) {
            $fail(__('promotion.request.discountValue_fail'));
        }

        if (!isset($this->data['object'])) {
            $fail(__('promotion.request.object_fail'));
        }
    }
}
