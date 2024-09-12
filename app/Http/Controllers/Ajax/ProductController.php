<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Repositories\ProductReceiptRepository;
use App\Repositories\ProductRepository;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class ProductController extends Controller
{
    protected $productRepository;
    protected $productService;
    protected $productReceiptRepository;

    public function __construct(ProductRepository $productRepository, ProductService $productService, ProductReceiptRepository $productReceiptRepository)
    {
        $this->productRepository = $productRepository;
        $this->productService = $productService;
        $this->productReceiptRepository = $productReceiptRepository;
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
}
