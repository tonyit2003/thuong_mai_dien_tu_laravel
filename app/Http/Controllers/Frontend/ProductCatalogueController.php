<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\FrontendController;
use App\Models\System;
use App\Repositories\ProductCatalogueRepository;

class ProductCatalogueController extends FrontendController
{
    protected $productCatalogueRepository;

    public function __construct(ProductCatalogueRepository $productCatalogueRepository)
    {
        // gọi hàm khởi tạo (constructor) của lớp cha
        parent::__construct();
        $this->productCatalogueRepository = $productCatalogueRepository;
    }

    public function index($id, $language = 1)
    {
        $config = $this->config();
        $productCatalogue = $this->productCatalogueRepository->getProductCatalogueById($id, $language);
        $system = convert_array(System::where('language_id', $language)->get(), 'keyword', 'content');
        $seo = [
            'meta_title' => isset($productCatalogue->meta_title) ? $productCatalogue->meta_title : $productCatalogue->name,
            'meta_keyword' => isset($productCatalogue->meta_keyword) ? $productCatalogue->meta_keyword : '',
            'meta_description' => isset($productCatalogue->meta_description) ? $productCatalogue->meta_description : cut_string_and_decode($productCatalogue->description, 168),
            'meta_image' => $productCatalogue->image,
            'canonical' => write_url($productCatalogue->canonical, true, true),
        ];
        return view('frontend.product.catalogue.index', compact('config', 'language', 'seo', 'system', 'productCatalogue'));
    }

    private function config()
    {
        return [];
    }
}
