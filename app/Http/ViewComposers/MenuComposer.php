<?php

namespace App\Http\ViewComposers;

use App\Enums\MenuEnum;
use App\Models\Language;
use App\Repositories\MenuCatalogueRepository;
use Illuminate\Support\Facades\App;
use Illuminate\View\View;

class MenuComposer
{
    protected $menuCatalogueRepository;
    protected $language;

    public function __construct(MenuCatalogueRepository $menuCatalogueRepository)
    {
        $locale = App::getLocale();
        $language = Language::where('canonical', $locale)->first();
        $this->language = $language->id;
        $this->menuCatalogueRepository = $menuCatalogueRepository;
    }

    public function compose(View $view)
    {
        $language = $this->language;
        $condition = [
            config('apps.general.publish')
        ];
        // khi lấy dữ liệu của menus, nó sẽ đồng thời lấy dữ liệu liên quan từ bảng languages.
        $relation = [
            'menus' => function ($query) use ($language) {
                $query->orderBy('order', 'DESC');
                $query->with([
                    'languages' => function ($query) use ($language) {
                        $query->where('language_id', $language);
                    }
                ]);
            }
        ];
        $menuCatalogues = $this->menuCatalogueRepository->findByCondition($condition, true, $relation);
        $menus = [];
        $htmlType = [MenuEnum::MAIN_MENU];
        if (count($menuCatalogues)) {
            foreach ($menuCatalogues as $key => $val) {
                $type = in_array($val->keyword, $htmlType) ? 'html' : 'array';
                $menus[$val->keyword] = frontend_recursive_menu(recursive($val->menus), 0, 1, $type);
            }
        }
        $view->with('menus', $menus);
    }
}
