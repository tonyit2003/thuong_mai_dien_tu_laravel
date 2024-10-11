<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\FrontendController;
use App\Models\ProductVariant;
use App\Repositories\ProductCatalogueRepository;
use App\Repositories\ProductRepository;
use App\Repositories\ProductVariantRepository;
use App\Services\ProductVariantService;
use Illuminate\Http\Request;

class ProductController extends FrontendController
{
    protected $productCatalogueRepository;
    protected $productRepository;
    protected $productVariantService;
    protected $productVariantRepository;

    public function __construct(ProductCatalogueRepository $productCatalogueRepository, ProductVariantService $productVariantService, ProductRepository $productRepository, ProductVariantRepository $productVariantRepository)
    {
        // gọi hàm khởi tạo (constructor) của lớp cha
        parent::__construct();
        $this->productCatalogueRepository = $productCatalogueRepository;
        $this->productVariantService = $productVariantService;
        $this->productRepository = $productRepository;
        $this->productVariantRepository = $productVariantRepository;
    }

    public function index($id, $productVariantId, $request)
    {
        $language = $this->language;
        $product = $this->productRepository->getProductById($id, $language);
        $productVariant = $this->productVariantRepository->findById($productVariantId, ['*'], [
            'languages' => function ($query) use ($language) {
                $query->where('language_id', $language);
            }
        ]);
        // dd($product->product_variants);
        $productVariant = $this->productVariantService->combineProductVariantAndPromotion([$productVariantId], $productVariant, true);
        $productCatalogue = $this->productCatalogueRepository->getProductCatalogueById($product->product_catalogue_id, $language);
        $breadcrumb = $this->productCatalogueRepository->breadcrumb($productCatalogue, $language);
        $config = $this->config();
        $system = $this->system;
        $seo = seo($product);
        return view('frontend.product.product.index', compact('config', 'language', 'seo', 'system', 'product', 'productVariant', 'productCatalogue', 'breadcrumb'));
    }

    private function config()
    {
        return [];
    }
}
