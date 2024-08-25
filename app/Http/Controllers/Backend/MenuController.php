<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMenuChildrenRequest;
use App\Http\Requests\StoreMenuRequest;
use App\Http\Requests\UpdateMenuRequest;
use App\Models\Language;
use App\Repositories\MenuCatalogueRepository;
use App\Repositories\MenuRepository;
use App\Services\MenuCatalogueService;
use App\Services\MenuService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Gate;

class MenuController extends Controller
{
    protected $menuCatalogueService;
    protected $menuCatalogueRepository;
    protected $menuRepository;
    protected $menuService;

    public function __construct(MenuCatalogueService $menuCatalogueService, MenuRepository $menuRepository, MenuCatalogueRepository $menuCatalogueRepository, MenuService $menuService)
    {
        $this->menuCatalogueService = $menuCatalogueService;
        $this->menuCatalogueRepository = $menuCatalogueRepository;
        $this->menuRepository = $menuRepository;
        $this->menuService = $menuService;
        $this->middleware(function ($request, $next) {
            $locale = App::getLocale();
            $language = Language::where('canonical', $locale)->first();
            $this->language = $language->id;
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        Gate::authorize('modules', 'menu.index');
        $menuCatalogues = $this->menuCatalogueService->paginate($request);

        $config = [
            'js' => [
                'backend/js/plugins/switchery/switchery.js',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js'
            ],
            'css' => [
                'backend/css/plugins/switchery/switchery.css',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css'
            ],
            'model' => 'MenuCatalogue'
        ];
        $config['seo'] = __('menu');

        $template = 'backend.menu.menu.index';
        return view('backend.dashboard.layout', compact('template', 'config', 'menuCatalogues'));
    }

    public function create()
    {
        Gate::authorize('modules', 'menu.create');
        $menuCatalogues = $this->menuCatalogueRepository->all();
        $config = $this->configData();
        $config['method'] = 'create';
        $config['seo'] = __('menu');
        $template = 'backend.menu.menu.store';
        return view('backend.dashboard.layout', compact('template', 'config', 'menuCatalogues'));
    }

    public function store(StoreMenuRequest $storeMenuRequest)
    {
        if ($this->menuService->create($storeMenuRequest, $this->language)) {
            flash()->success(__('toast.store_success'));
            return redirect()->route('menu.index');
        }
        flash()->error(__('toast.store_failed'));
        return redirect()->route('menu.index');
    }

    public function edit($id)
    {
        Gate::authorize('modules', 'menu.update');
        $condition = [
            ['menu_catalogue_id', '=', $id]
        ];
        $language = $this->language;
        $relation = ['languages' => function ($query) use ($language) {
            $query->where('language_id', $language);
        }];
        $orderBy = ['order', 'DESC'];
        $menus = $this->menuRepository->findByCondition($condition, true, $relation, $orderBy);
        $config = $this->configData();
        $config['seo'] = __('menu');
        $template = 'backend.menu.menu.show';
        return view('backend.dashboard.layout', compact('template', 'config', 'menus', 'id'));
    }

    public function editMenu($id)
    {
        Gate::authorize('modules', 'menu.update');
        $condition = [
            ['menu_catalogue_id', '=', $id],
            ['parent_id', '=', 0],
        ];
        $language = $this->language;
        $relation = ['languages' => function ($query) use ($language) {
            $query->where('language_id', $language);
        }];
        $orderBy = ['order', 'DESC'];
        $menus = $this->menuRepository->findByCondition($condition, true, $relation, $orderBy);
        $menuList = $this->menuService->convertMenu($menus);
        $menuCatalogues = $this->menuCatalogueRepository->all();
        $menuCatalogue = $this->menuCatalogueRepository->findById($id, ['*']);
        $config = $this->configData();
        $config['method'] = 'update';
        $config['seo'] = __('menu');
        $template = 'backend.menu.menu.store';
        return view('backend.dashboard.layout', compact('template', 'config', 'menuList', 'menuCatalogues', 'menuCatalogue'));
    }

    public function update($id, UpdateMenuRequest $updateMenuRequest)
    {
        if ($this->menuService->update($id, $updateMenuRequest, $this->language)) {
            flash()->success(__('toast.update_success'));
            return redirect()->route('menu.index');
        }
        flash()->error(__('toast.update_failed'));
        return redirect()->route('menu.index');
    }

    public function delete($id)
    {
        Gate::authorize('modules', 'menu.destroy');
        $menu = $this->menuRepository->findById($id);
        $config['seo'] = __('menu');
        $template = 'backend.menu.menu.delete';
        return view('backend.dashboard.layout', compact('template', 'menu', 'config'));
    }

    public function destroy($id)
    {
        if ($this->menuService->delete($id, $this->language)) {
            flash()->success(__('toast.destroy_success'));
            return redirect()->route('menu.index');
        }
        flash()->error(__('toast.destroy_failed'));
        return redirect()->route('menu.index');
    }

    public function children($id)
    {
        Gate::authorize('modules', 'menu.create');
        $language = $this->language;
        $relation = ['languages' => function ($query) use ($language) {
            $query->where('language_id', $language);
        }];
        $menu = $this->menuRepository->findById($id, ['*'], $relation);
        $menuList = $this->menuService->getAndConvertMenu($menu, $language);
        $config = $this->configData();
        $config['seo'] = __('menu', ['menu' => lcfirst($menu->languages->first()->pivot->name)]);
        $config['method'] = 'create';
        $template = 'backend.menu.menu.children';
        return view('backend.dashboard.layout', compact('template', 'config', 'menu', 'menuList'));
    }

    public function saveChildren(StoreMenuChildrenRequest $storeMenuChildrenRequest, $id)
    {
        $menu = $this->menuRepository->findById($id);
        if ($this->menuService->saveChildren($storeMenuChildrenRequest, $this->language, $menu)) {
            flash()->success(__('toast.store_success'));
            return redirect()->route('menu.edit', $menu->menu_catalogue_id);
        }
        flash()->error(__('toast.store_failed'));
        return redirect()->route('menu.edit', $menu->menu_catalogue_id);
    }

    private function configData()
    {
        return [
            'css' => [
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css'
            ],
            'js' => [
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js',
                'backend/library/menu.js',
                'backend/js/plugins/nestable/jquery.nestable.js'
            ]
        ];
    }
}
