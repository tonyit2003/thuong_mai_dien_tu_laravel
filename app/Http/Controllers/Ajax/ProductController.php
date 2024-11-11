<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Models\Supplier;
use App\Repositories\ProductReceiptRepository;
use App\Repositories\ProductRepository;
use App\Repositories\ProductVariantRepository;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class ProductController extends Controller
{
    protected $productRepository;
    protected $productService;
    protected $productReceiptRepository;
    protected $productVariantRepository;

    public function __construct(ProductRepository $productRepository, ProductService $productService, ProductReceiptRepository $productReceiptRepository, ProductVariantRepository $productVariantRepository)
    {
        $this->productRepository = $productRepository;
        $this->productService = $productService;
        $this->productReceiptRepository = $productReceiptRepository;
        $this->productVariantRepository = $productVariantRepository;
        $this->middleware(function ($request, $next) {
            $locale = App::getLocale();
            $language = Language::where('canonical', $locale)->first();
            $this->language = $language->id;
            return $next($request);
        });
    }

    public function getProduct(Request $request)
    {
        $products = $this->productService->paginateProduct($request, $this->language);
        return response()->json($products);
    }

    public function getReceiptById($id)
    {
        $receipt = $this->productReceiptRepository->getProductReceiptById($id);
        $formattedDetails = $receipt->details->map(function ($detail) {
            return [
                'product_id' => (int)$detail->product_id,
                'variant_id' => (int)$detail->product_variant_id,
                'product_name' => $detail->product->product_name ?? 'N/A',
                'variant_name' => $detail->productVariant->variant_name ?? 'N/A',
                'quantity' => $detail->quantity,
                'price' => (float)$detail->price
            ];
        });

        return response()->json(['data' => $formattedDetails]);
    }

    public function getProductCatalogueBySupplierId($id)
    {
        $languageId = $this->language;
        // Fetch supplier with product catalogues and related products including language filtering
        $supplier = Supplier::with(['product_catalogues.products' => function ($query) use ($languageId) {
            // Eager load the language that matches the specified language_id
            $query->with(['languages' => function ($q) use ($languageId) {
                $q->where('language_id', $languageId);
            }]);
        }])->findOrFail($id);

        // Format product data based on supplier's product catalogues
        $products = $supplier->product_catalogues->flatMap(function ($catalogue) {
            return $catalogue->products->map(function ($product) {
                $productLanguage = $product->languages->first(); // Get the first matching language
                return [
                    'product_id' => $product->id,
                    'product_name' => $productLanguage ? $productLanguage->pivot->name : 'N/A',
                ];
            });
        });
        // Return formatted product data as JSON
        return response()->json(['data' => $products]);
    }


    public function loadProductPromotion(Request $request)
    {
        $get = $request->input();
        $loadClass = loadClass($get['model']);
        if ($get['model'] == 'Product') {
            $condition = [
                ['product_language.language_id', '=', $this->language]
            ];
            if (isset($get['keyword']) && $get['keyword'] != '') {
                $keywordCondition = ['product_language.name', 'LIKE', '%' . $get['keyword'] . '%'];
                array_push($condition, $keywordCondition);
            }
            $objects = $loadClass->findProductForPromotion($condition);
        } else if ($get['model'] == 'ProductCatalogue') {
            if (isset($get['keyword']) && $get['keyword'] != '') {
                $condition['keyword'] = $get['keyword'];
            }
            $condition['where'] = [
                ['product_catalogue_language.language_id', '=', $this->language]
            ];
            $join = [
                ['product_catalogue_language', 'product_catalogue_language.product_catalogue_id', '=', 'product_catalogues.id']
            ];
            $objects = $loadClass->pagination(['product_catalogues.id', 'product_catalogue_language.name'], $condition, $join, 10, ['path' => 'product.catalogue.index'], ['languages']);
        }
        return response()->json([
            'model' => $get['model'] ?? 'Product',
            'objects' => $objects
        ]);
    }

    public function loadVariant(Request $request)
    {
        $get = $request->input();
        $attributeId = $get['attribute_id'];
        $attributeId = sortAttributeId($attributeId);
        $variant = $this->productVariantRepository->findVariant($attributeId, $get['product_id'], $get['language_id']);
        return response()->json([
            'variant' => $variant
        ]);
    }
}
