<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMenuChildrenRequest;
use App\Http\Requests\StoreMenuRequest;
use App\Http\Requests\UpdateMenuRequest;
use App\Models\Language;
use App\Repositories\LanguageRepository;
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
    protected $languageRepository;

    public function __construct(MenuCatalogueService $menuCatalogueService, MenuRepository $menuRepository, MenuCatalogueRepository $menuCatalogueRepository, MenuService $menuService, LanguageRepository $languageRepository)
    {
        $this->menuCatalogueService = $menuCatalogueService;
        $this->menuCatalogueRepository = $menuCatalogueRepository;
        $this->menuRepository = $menuRepository;
        $this->menuService = $menuService;
        $this->languageRepository = $languageRepository;
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

    // Tạo mới các menu cấp 1
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

    // Sửa vị trí các menu cấp 1
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
        $menuCatalogue = $this->menuCatalogueRepository->findById($id, ['*']);
        $languageCurrent = $this->language;
        $config = $this->configData();
        $config['seo'] = __('menu');
        $template = 'backend.menu.menu.show';
        return view('backend.dashboard.layout', compact('template', 'config', 'menus', 'id', 'menuCatalogue', 'languageCurrent'));
    }

    // Sửa or tạo mới menu cấp 1
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
        return view('backend.dashboard.layout', compact('template', 'config', 'menuList', 'menuCatalogues', 'menuCatalogue', 'id'));
    }

    public function store(StoreMenuRequest $storeMenuRequest)
    {
        if ($this->menuService->save($storeMenuRequest, $this->language)) {
            $menuCatalogueId = $storeMenuRequest->input('menu_catalogue_id');
            flash()->success(__('toast.update_success'));
            return redirect()->route('menu.edit', ['id' => $menuCatalogueId]);
        }
        flash()->error(__('toast.update_failed'));
        return redirect()->route('menu.index');
    }

    // Thêm mới or sửa menu con của menu bất kỳ
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

    public function delete($id)
    {
        Gate::authorize('modules', 'menu.destroy');
        $menuCatalogue = $this->menuCatalogueRepository->findById($id);
        $config['seo'] = __('menu');
        $template = 'backend.menu.menu.delete';
        return view('backend.dashboard.layout', compact('template', 'menuCatalogue', 'config'));
    }

    public function destroy($id)
    {
        if ($this->menuService->delete($id)) {
            flash()->success(__('toast.destroy_success'));
            return redirect()->route('menu.index');
        }
        flash()->error(__('toast.destroy_failed'));
        return redirect()->route('menu.index');
    }

    public function translate($languageId, $id)
    {
        $language = $this->languageRepository->findById($languageId);
        $menuCatalogue = $this->menuCatalogueRepository->findById($id);
        $condition = [
            ['menu_catalogue_id', '=', $id]
        ];
        $currentLanguage = $this->language;
        $relation = ['languages' => function ($query) use ($currentLanguage) {
            $query->where('language_id', $currentLanguage);
        }];
        $orderBy = ['lft', 'ASC'];
        $menus = buildMenu($this->menuService->findMenuItemTranslate($this->menuRepository->findByCondition($condition, true, $relation, $orderBy), $currentLanguage, $languageId));
        $config = $this->configData();
        $config['seo'] = __('menu', ['language' => lcfirst($language->name), 'menu' => lcfirst($menuCatalogue->name)]);
        $config['method'] = 'translate';
        $template = 'backend.menu.menu.translate';
        return view('backend.dashboard.layout', compact('template', 'config', 'menuCatalogue', 'menus', 'languageId'));
    }

    public function saveTranslate(Request $request, $languageId)
    {
        if ($this->menuService->saveTranslateMenu($request, $languageId)) {
            flash()->success(__('toast.update_success'));
            return redirect()->route('menu.index');
        }
        flash()->error(__('toast.update_failed'));
        return redirect()->route('menu.index');
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
