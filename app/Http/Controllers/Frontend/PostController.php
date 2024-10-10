<?php

namespace App\Http\Controllers\Frontend;

use App\Enums\SlideEnum;
use App\Enums\WidgetEnum;
use App\Http\Controllers\FrontendController;
use App\Repositories\SlideRepository;
use App\Repositories\SystemRepository;
use App\Services\SlideService;
use App\Services\WidgetService;

class PostController extends FrontendController
{
    protected $slideRepository;
    protected $widgetService;
    protected $slideService;

    public function __construct(SlideRepository $slideRepository, WidgetService $widgetService, SlideService $slideService)
    {
        // gọi hàm khởi tạo (constructor) của lớp cha
        parent::__construct();
        $this->slideRepository = $slideRepository;
        $this->widgetService = $widgetService;
        $this->slideService = $slideService;
    }

    public function index()
    {
        $slides = $this->slideService->getSlides([SlideEnum::MAIN_SLIDE, SlideEnum::BANNER, SlideEnum::BANNER_FOOTER], $this->language);
        // children => lấy các danh mục con của các danh mục trong widget
        // promotion => lấy ra các sản phẩm + khuyến mãi của danh mục product catalogue
        // countObject => đếm các sản phẩm của danh mục
        $widgets = $this->widgetService->getWidgets([
            ['keyword' => WidgetEnum::CATEGORY, 'children' => true, 'promotion' => true, 'countObject' => true],
            ['keyword' => WidgetEnum::CATEGORY_MENU],
            ['keyword' => WidgetEnum::CATEGORY_HOME, 'children' => true, 'promotion' => true, 'countObject' => true],
            ['keyword' => WidgetEnum::BESTSELLER, 'promotion' => true],
            ['keyword' => WidgetEnum::FEATURED_NEWS],
        ], $this->language);
        $config = $this->config();
        $language = $this->language;
        $system = $this->system;
        $seo = [
            'meta_title' => $system['seo_meta_title'],
            'meta_keyword' => $system['seo_meta_keyword'],
            'meta_description' => $system['seo_meta_description'],
            'meta_image' => $system['seo_meta_image'],
            'canonical' => config('app.url')
        ];
        return view('frontend.homepage.home.index', compact('config', 'slides', 'widgets', 'language', 'seo', 'system'));
    }

    private function config()
    {
        return [];
    }
}
