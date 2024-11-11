<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\FrontendController;
use App\Repositories\ProductCatalogueRepository;
use App\Services\ProductCatalogueService;
use App\Services\ProductVariantService;
use Illuminate\Http\Request;

class ProductCatalogueController extends FrontendController
{
    protected $productCatalogueRepository;
    protected $productVariantService;
    protected $productCatalogueService;

    public function __construct(ProductCatalogueRepository $productCatalogueRepository, ProductVariantService $productVariantService, ProductCatalogueService $productCatalogueService)
    {
        // gọi hàm khởi tạo (constructor) của lớp cha
        parent::__construct();
        $this->productCatalogueRepository = $productCatalogueRepository;
        $this->productVariantService = $productVariantService;
        $this->productCatalogueService = $productCatalogueService;
    }

    public function index($id, $request, $page = 1)
    {
        $config = $this->config();
        $language = $this->language;
        $system = $this->system;
        $productCatalogue = $this->productCatalogueRepository->getProductCatalogueById($id, $language);
        $filters = $this->getFilters($productCatalogue, $language);
        $breadcrumb = $this->productCatalogueRepository->breadcrumb($productCatalogue, $language);
        $productVariants = $this->productVariantService->paginate($request, $language, $productCatalogue, ['path' => $productCatalogue->canonical], $page);
        $productVariantUuids = $productVariants->pluck('uuid')->toArray();
        if (count($productVariantUuids) && isset($productVariantUuids)) {
            $productVariants = $this->productVariantService->combineProductVariantAndPromotion($productVariantUuids, $productVariants);
        }
        $this->productVariantService->getCatalogueName($productVariants, $this->language);
        $productVariants = $this->productVariantService->getReview($productVariants);
        $seo = seo($productCatalogue, $page);
        return view('frontend.product.catalogue.index', compact('config', 'language', 'seo', 'system', 'productCatalogue', 'breadcrumb', 'productVariants', 'filters'));
    }

    private function getFilters($productCatalogue, $language)
    {
        $filters = null;
        if (isset($productCatalogue->attribute) && count($productCatalogue->attribute)) {
            $filters = $this->productCatalogueService->getFilterList($productCatalogue->attribute, $language);
        }
        return $filters;
    }

    private function config()
    {
        return [
            'js' => [
                'frontend/core/library/filter.js',
            ]
        ];
    }
}
