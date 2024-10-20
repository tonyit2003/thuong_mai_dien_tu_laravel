<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\FrontendController;
use App\Repositories\ProductCatalogueRepository;
use App\Repositories\ProductRepository;
use App\Repositories\ProductVariantRepository;
use App\Services\ProductService;
use App\Services\ProductVariantService;

class ProductController extends FrontendController
{
    protected $productCatalogueRepository;
    protected $productRepository;
    protected $productService;
    protected $productVariantService;
    protected $productVariantRepository;

    public function __construct(ProductCatalogueRepository $productCatalogueRepository, ProductVariantService $productVariantService, ProductRepository $productRepository, ProductVariantRepository $productVariantRepository, ProductService $productService)
    {
        // gọi hàm khởi tạo (constructor) của lớp cha
        parent::__construct();
        $this->productCatalogueRepository = $productCatalogueRepository;
        $this->productVariantService = $productVariantService;
        $this->productRepository = $productRepository;
        $this->productVariantRepository = $productVariantRepository;
        $this->productService = $productService;
    }

    public function index($id, $variantUuid, $request)
    {
        $language = $this->language;
        $product = $this->productRepository->getProductById($id, $language);
        $product = $this->productService->getAttribute($product, $language);
        $category = recursive($this->productCatalogueRepository->all([
            'languages' => function ($query) use ($language) {
                $query->where('language_id', $language);
            }
        ]));
        $productVariant = $this->productVariantRepository->findByCondition([
            ['uuid', '=', $variantUuid],
        ], false, [
            'languages' => function ($query) use ($language) {
                $query->where('language_id', $language);
            }
        ]);
        $productVariant = $this->productVariantService->combineProductVariantAndPromotion([$variantUuid], $productVariant, true);
        $productCatalogue = $this->productCatalogueRepository->getProductCatalogueById($product->product_catalogue_id, $language);
        $breadcrumb = $this->productCatalogueRepository->breadcrumb($productCatalogue, $language);
        $config = $this->config();
        $system = $this->system;
        $seo = seo($product);
        return view('frontend.product.product.index', compact('config', 'language', 'seo', 'system', 'product', 'productVariant', 'productCatalogue', 'breadcrumb', 'category', 'language'));
    }

    private function config()
    {
        return [
            'js' => [
                'frontend/core/library/cart.js',
                'frontend/core/library/product.js',
            ]
        ];
    }
}
