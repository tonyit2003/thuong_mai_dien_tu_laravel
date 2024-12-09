<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\FrontendController;
use App\Http\Requests\SearchProductRequest;
use App\Repositories\ProductCatalogueRepository;
use App\Repositories\ProductRepository;
use App\Repositories\ProductVariantRepository;
use App\Repositories\ReviewRepository;
use App\Services\ProductService;
use App\Services\ProductVariantService;
use App\Services\ReviewService;
use Illuminate\Http\Request;

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
        $this->setLanguage();
        $language = $this->language;
        $product = $this->productRepository->getProductById($id, $language);
        $product = $this->productService->getAttribute($product, $language);
        $product = $this->productService->setGeneralAttribute($product, $language);
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
        $similarProducts = $this->getSimilarProducts($product->product_catalogue_id, $variantUuid, $language);
        $config = $this->config();
        $system = $this->system;
        $seo = seo($product);
        return view('frontend.product.product.index', compact('config', 'language', 'seo', 'system', 'product', 'productVariant', 'productCatalogue', 'breadcrumb', 'category', 'language', 'reviews', 'similarProducts'));
    }

    public function search(SearchProductRequest $request)
    {
        $this->setLanguage();
        $keyword = $request->input('keyword');
        $language = $this->language;
        $config = $this->config();
        $system = $this->system;
        $productVariants = $this->productVariantService->searchProduct($request, $language);
        if (isset($keyword) && isset($productVariants) && count($productVariants)) {
            $productVariantUuids = $productVariants->pluck('uuid')->toArray();
            if (count($productVariantUuids) && isset($productVariantUuids)) {
                $productVariants = $this->productVariantService->combineProductVariantAndPromotion($productVariantUuids, $productVariants);
            }
            $this->productVariantService->getCatalogueName($productVariants, $this->language);
            $productVariants = $this->productVariantService->getReview($productVariants);
        }
        $seo = [
            'meta_title' => $keyword ?? '',
            'meta_keyword' =>  $keyword ?? '',
            'meta_description' =>  $keyword ?? '',
            'meta_image' => asset('userfiles/image/logo/logo.png'),
            'canonical' =>  $keyword ?? '',
        ];
        return view('frontend.product.product.search', compact('config', 'language', 'seo', 'system', 'keyword', 'productVariants'));
    }

    private function getSimilarProducts($catalogueId, $variantUuid, $language)
    {
        $similarProducts = $this->productService->getSimilarProducts($catalogueId ?? null, $variantUuid, $language, 2, 2);
        if (isset($similarProducts) && count($similarProducts)) {
            foreach ($similarProducts as $similarProduct) {
                $productVariantUuids = $similarProduct->variants->pluck('uuid')->toArray();
                if (count($productVariantUuids) && isset($productVariantUuids)) {
                    $similarProduct->variants = $this->productVariantService->combineProductVariantAndPromotion($productVariantUuids, $similarProduct->variants);
                }
            }
        }
        return $similarProducts;
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
