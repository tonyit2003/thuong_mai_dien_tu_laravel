<?php

namespace App\Http\Controllers\Frontend;

use App\Enums\WidgetEnum;
use App\Http\Controllers\FrontendController;
use App\Models\Post;
use App\Repositories\PostCatalogueRepository;
use App\Repositories\PostRepository;
use App\Services\PostService;
use App\Services\WidgetService;

class PostCatalogueController extends FrontendController
{
    protected $postService;
    protected $postRepository;
    protected $routerRepository;
    protected $postCatalogueRepository;
    protected $widgetService;

    public function __construct(PostRepository $postRepository, PostService $postService, PostCatalogueRepository $postCatalogueRepository, WidgetService $widgetService)
    {
        // gọi hàm khởi tạo (constructor) của lớp cha
        parent::__construct();
        $this->postRepository = $postRepository;
        $this->postCatalogueRepository = $postCatalogueRepository;
        $this->widgetService = $widgetService;
        $this->postService = $postService;
    }

    public function index($id)
    {
        $language = $this->language;
        $posts = $this->postRepository->getPostByCatalogueId($id, $language);
        $system = $this->system;
        $seo = [
            'meta_title' => $system['seo_meta_title'],
            'meta_keyword' => $system['seo_meta_keyword'],
            'meta_description' => $system['seo_meta_description'],
            'meta_image' => $system['seo_meta_image'],
            'canonical' => config('app.url')
        ];
        $postCatalogue = $this->postCatalogueRepository->getPostCatalogueById($id, $language);
        $breadcrumb = $this->postCatalogueRepository->breadcrumb($postCatalogue, $language);
        return view('frontend.post.show', compact('posts', 'language', 'system', 'seo', 'breadcrumb', 'postCatalogue'));
    }

    private function config()
    {
        return [];
    }
}
