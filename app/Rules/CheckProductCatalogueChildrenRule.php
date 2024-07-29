<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Models\ProductCatalogue;

class CheckProductCatalogueChildrenRule implements ValidationRule
{

    protected $id;

    public function __construct($id){
        $this->id = $id;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $flag = ProductCatalogue::isNodeCheck($this->id);
        if ($flag == false) {
            $fail('Không thể xóa do vẫn còn danh mục con');
        }
    }
}
