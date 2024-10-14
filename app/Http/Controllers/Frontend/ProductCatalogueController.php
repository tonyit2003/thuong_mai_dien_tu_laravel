<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\FrontendController;
use App\Repositories\ProductCatalogueRepository;
use App\Services\ProductVariantService;
use Illuminate\Http\Request;

class ProductCatalogueController extends FrontendController
{
    protected $productCatalogueRepository;
    protected $productVariantService;

    public function __construct(ProductCatalogueRepository $productCatalogueRepository, ProductVariantService $productVariantService)
    {
        // gọi hàm khởi tạo (constructor) của lớp cha
        parent::__construct();
        $this->productCatalogueRepository = $productCatalogueRepository;
        $this->productVariantService = $productVariantService;
    }

    public function index($id, $request, $page = 1)
    {
        $config = $this->config();
        $language = $this->language;
        $system = $this->system;
        $productCatalogue = $this->productCatalogueRepository->getProductCatalogueById($id, $language);
        $breadcrumb = $this->productCatalogueRepository->breadcrumb($productCatalogue, $language);
        $productVariants = $this->productVariantService->paginate($request, $language, $productCatalogue, ['path' => $productCatalogue->canonical], $page);
        $productVariantUuids = $productVariants->pluck('uuid')->toArray();
        if (count($productVariantUuids) && isset($productVariantUuids)) {
            $productVariants = $this->productVariantService->combineProductVariantAndPromotion($productVariantUuids, $productVariants);
        }
        $this->productVariantService->getCatalogueName($productVariants, $this->language);
        $seo = seo($productCatalogue, $page);
        return view('frontend.product.catalogue.index', compact('config', 'language', 'seo', 'system', 'productCatalogue', 'breadcrumb', 'productVariants'));
    }

    private function config()
    {
        return [];
    }
}
