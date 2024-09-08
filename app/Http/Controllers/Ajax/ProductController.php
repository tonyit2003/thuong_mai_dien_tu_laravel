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
}
