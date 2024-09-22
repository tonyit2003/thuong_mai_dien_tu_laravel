<?php

namespace App\Http\Controllers\Frontend;

use App\Enums\SlideEnum;
use App\Enums\WidgetEnum;
use App\Http\Controllers\FrontendController;
use App\Repositories\SlideRepository;
use App\Services\SlideService;
use App\Services\WidgetService;

class HomeController extends FrontendController
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
        $slides = $this->slideService->getSlides([SlideEnum::MAIN_SLIDE, SlideEnum::BANNER], $this->language);
        $widgets = [
            WidgetEnum::CATEGORY => $this->widgetService->findWidgetByKeyword(WidgetEnum::CATEGORY, $this->language, ['children' => true, 'object' => true, 'countObject' => true]),
            WidgetEnum::CATEGORY_MENU => $this->widgetService->findWidgetByKeyword(WidgetEnum::CATEGORY, $this->language),
        ];
        $config = $this->config();
        return view('frontend.homepage.home.index', compact('config', 'slides', 'widgets'));
    }

    private function config()
    {
        return [];
    }
}
