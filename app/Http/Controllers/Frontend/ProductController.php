<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\FrontendController;
use App\Repositories\ProductCatalogueRepository;
use App\Repositories\ProductRepository;
use App\Repositories\ProductVariantRepository;
use App\Repositories\ReviewRepository;
use App\Services\ProductService;
use App\Services\ProductVariantService;
use App\Services\ReviewService;

class ProductController extends FrontendController
{
    protected $productCatalogueRepository;
    protected $productRepository;
    protected $productService;
    protected $productVariantService;
    protected $productVariantRepository;
    protected $reviewRepository;
    protected $reviewService;

    public function __construct(ProductCatalogueRepository $productCatalogueRepository, ProductVariantService $productVariantService, ProductRepository $productRepository, ProductVariantRepository $productVariantRepository, ProductService $productService, ReviewRepository $reviewRepository, ReviewService $reviewService)
    {
        // gọi hàm khởi tạo (constructor) của lớp cha
        parent::__construct();
        $this->productCatalogueRepository = $productCatalogueRepository;
        $this->productVariantService = $productVariantService;
        $this->productRepository = $productRepository;
        $this->productVariantRepository = $productVariantRepository;
        $this->productService = $productService;
        $this->reviewRepository = $reviewRepository;
        $this->reviewService = $reviewService;
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
        $reviews = $this->reviewRepository->findByCondition([
            ['variant_uuid', '=', $variantUuid],
            config('apps.general.publish')
        ], true);
        $reviews = $this->reviewService->setCustomerInformation($reviews);
        $config = $this->config();
        $system = $this->system;
        $seo = seo($product);
        return view('frontend.product.product.index', compact('config', 'language', 'seo', 'system', 'product', 'productVariant', 'productCatalogue', 'breadcrumb', 'category', 'language', 'reviews'));
    }

    private function config()
    {
        return [
            'js' => [
                'frontend/core/library/cart.js',
                'frontend/core/library/product.js',
                'frontend/core/library/review.js',
            ],
            'css' => [
                'frontend/core/css/product.css'
            ]
        ];
    }
}
