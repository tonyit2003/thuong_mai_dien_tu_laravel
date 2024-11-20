<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Repositories\AttributeRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class AttributeController extends Controller
{
    protected $attributeRepository;

    public function __construct(AttributeRepository $attributeRepository)
    {
        $this->attributeRepository = $attributeRepository;
        $this->middleware(function ($request, $next) {
            $locale = App::getLocale();
            $language = Language::where('canonical', $locale)->first();
            $this->language = $language->id;
            return $next($request);
        });
    }

    public function getAttribute(Request $request)
    {
        $payload = $request->input();
        $attributes = $this->attributeRepository->searchAttributes($payload['search'], $payload['option'], $this->language);
        // map(): laravel => lặp qua mỗi mục trong tập hợp $attributes và áp dụng hàm callback (function($attribute)) để biến đổi mỗi mục
        // map(): nhận vào 1 Collection ban đầu và trả về 1 Collection mới chứa các mục đã được biến đổi
        // $attribute => đại diện cho từng mục trong tập hợp.
        $attributeMapped = $attributes->map(function ($attribute) {
            return [
                'id' => $attribute->id,
                'text' => $attribute->attribute_language->first()->name,
            ];
        })->all(); // all() => chuyển đổi một collection thành một mảng
        return response()->json(array('items' => $attributeMapped));
    }

    public function loadAttribute(Request $request)
    {
        // true: kết quả phải được trả về dưới dạng một mảng kết hợp (associative array) thay vì một đối tượng.
        $payload['attribute'] = json_decode(base64_decode($request->input('attribute')), true);
        $payload['attribute'] = array_map(function ($val) {
            if (!is_array($val)) {
                return [$val];
            }
            return $val;
        }, $payload['attribute']);
        $payload['attributeCatalogueId'] = $request->input('attributeCatalogueId');
        $attributeArray = $payload['attribute'][$payload['attributeCatalogueId']];
        $attributes = [];
        if (count($attributeArray)) {
            $attributes = $this->attributeRepository->findAttributeByIdArray($attributeArray, $this->language);
        }
        $temp = [];
        if (count($attributes)) {
            foreach ($attributes as $key => $val) {
                $temp[] = [
                    'id' => $val->id,
                    'text' => $val->name
                ];
            }
        }
        return response()->json(array('items' => $temp));
    }
}
