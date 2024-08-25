<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMenuCatalogueRequest;
use App\Models\Language;
use App\Repositories\MenuCatalogueRepository;
use App\Services\MenuCatalogueService;
use App\Services\MenuService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class MenuController extends Controller
{
    protected $menuCatalogueRepository;
    protected $menuCatalogueService;
    protected $menuService;

    public function __construct(MenuCatalogueRepository $menuCatalogueRepository, MenuCatalogueService $menuCatalogueService, MenuService $menuService)
    {
        $this->middleware(function ($request, $next) {
            $locale = App::getLocale();
            $language = Language::where('canonical', $locale)->first();
            $this->language = $language->id;
            return $next($request);
        });
        $this->menuCatalogueRepository = $menuCatalogueRepository;
        $this->menuCatalogueService = $menuCatalogueService;
        $this->menuService = $menuService;
    }

    public function createCatalogue(StoreMenuCatalogueRequest $storeMenuCatalogueRequest)
    {
        $menuCatalogue = $this->menuCatalogueService->create($storeMenuCatalogueRequest);
        if ($menuCatalogue != false) {
            return response()->json([
                'code' => 0,
                'message' => __('toast.create_menu_catalogue_success'),
                'data' => $menuCatalogue
            ]);
        }
        return response()->json([
            'code' => 1,
            'message' => __('toast.create_menu_catalogue_failed')
        ]);
    }

    public function drag(Request $request)
    {
        $json = json_decode($request->string('json'), true);
        $menuCatalogueId = $request->integer('menu_catalogue_id');
        $flag = $this->menuService->dragUpdate($json, $menuCatalogueId, $this->language, 0);
    }
}
