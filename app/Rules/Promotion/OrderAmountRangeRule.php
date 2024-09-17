<?php

namespace App\Rules\Promotion;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class OrderAmountRangeRule implements ValidationRule
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
        if (!isset($this->data['amountFrom']) || !isset($this->data['amountTo']) || !isset($this->data['amountValue']) || count($this->data['amountFrom']) == 0 || $this->data['amountFrom'][0] == '') {
            $fail(__('promotion.request.amount_fail'));
        }
        if (in_array(0, $this->data['amountValue']) || in_array('', $this->data['amountValue'])) {
            $fail(__('promotion.request.amountValue_fail'));
        }
        $conflict = false;
        for ($i = 0; $i < count($this->data['amountFrom']); $i++) {
            $amountFrom_1 = floatval(convert_price($this->data['amountFrom'][$i]));
            $amountTo_1 = floatval(convert_price($this->data['amountTo'][$i]));
            if ($amountFrom_1 >= $amountTo_1) {
                $conflict = true;
                break;
            }
            for ($j = 0; $j < count($this->data['amountFrom']); $j++) {
                if ($i != $j) {
                    $amountFrom_2 = floatval(convert_price($this->data['amountFrom'][$j]));
                    $amountTo_2 = floatval(convert_price($this->data['amountTo'][$j]));
                    if ($amountFrom_1 <= $amountTo_2 && $amountTo_1 >= $amountFrom_2) {
                        $conflict = true;
                        break;
                    }
                }
            }
        }
        if ($conflict) {
            $fail(__('promotion.request.conflict_fail'));
        }
    }
}
