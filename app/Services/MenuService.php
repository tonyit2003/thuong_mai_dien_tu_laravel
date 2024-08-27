<?php

namespace App\Services;

use App\Classes\Nestedsetbie;
use App\Repositories\MenuCatalogueRepository;
use App\Repositories\MenuRepository;
use App\Repositories\RouterRepository;
use App\Services\Interfaces\MenuServiceInterface;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Class MenuService
 * @package App\Services
 */
class MenuService extends BaseService implements MenuServiceInterface
{
    protected $menuRepository;
    protected $menuCatalogueRepository;
    protected $routerRepository;
    protected $nestedset;

    public function __construct(MenuRepository $menuRepository, MenuCatalogueRepository $menuCatalogueRepository, RouterRepository $routerRepository)
    {
        $this->menuRepository = $menuRepository;
        $this->menuCatalogueRepository = $menuCatalogueRepository;
        $this->routerRepository = $routerRepository;
    }

    public function getAndConvertMenu($menu = null, $languageId = 1)
    {
        $condition = [
            ['parent_id', '=', $menu->id]
        ];
        $relation = ['languages' => function ($query) use ($languageId) {
            $query->where('language_id', $languageId);
        }];
        $orderBy = ['order', 'DESC'];
        $menuList = $this->menuRepository->findByCondition($condition, true, $relation, $orderBy);
        return $this->convertMenu($menuList);
    }

    // Thêm 2 trường translate_name và translate_canonical vào từng phần tử trong collection menus từ các bảng trung gian của model và language
    public function findMenuItemTranslate($menus, $currentLanguage, $languageId)
    {
        $output = [];
        if (count($menus)) {
            foreach ($menus as $menu) {
                $canonical = $menu->languages->first()->pivot->canonical;
                $detailMenu = $this->menuRepository->findById($menu->id, ['*'], ['languages' => function ($query) use ($languageId) {
                    $query->where('language_id', $languageId);
                }]);
                if ($detailMenu) {
                    if ($detailMenu->languages->isNotEmpty()) {
                        $menu->translate_name = $detailMenu->languages->first()->pivot->name;
                        $menu->translate_canonical = $detailMenu->languages->first()->pivot->canonical;
                    } else {
                        $router = $this->routerRepository->findByCondition([['canonical', '=', $canonical]]);
                        if ($router) {
                            $controller = explode('\\', $router->controllers);
                            $model = str_replace('Controller', '', end($controller));
                            $repositoryNamespace = '\App\Repositories\\' . ucfirst($model) . 'Repository';
                            if (class_exists($repositoryNamespace)) {
                                $repositoryInstance = app($repositoryNamespace);
                            }
                            $alias  = Str::snake($model) . '_language';
                            $object = $repositoryInstance->findByWhereHas([
                                'canonical' => $canonical,
                                'language_id' => $currentLanguage
                            ], 'languages', $alias);
                            if ($object) {
                                $translateObject = $object->languages()->where('language_id', $languageId)->first([$alias . '.name', $alias . '.canonical']);
                                if (!is_null($translateObject)) {
                                    $menu->translate_name = $translateObject->name;
                                    $menu->translate_canonical = $translateObject->canonical;
                                }
                            }
                        }
                    }
                }
                $output[] = $menu;
            }
        }
        return $output;
    }

    // Thêm mới menu trong trường hợp input[name=id] = 0 or cập nhật menu trong trường hợp input[name=id] != 0
    public function save($request, $languageId)
    {
        DB::beginTransaction();
        try {
            $payload = $request->only('menu', 'menu_catalogue_id');
            if (count($payload['menu']['name'])) {
                foreach ($payload['menu']['name'] as $key => $val) {
                    $menuId = $payload['menu']['id'][$key];
                    $menuArray = [
                        'menu_catalogue_id' => $payload['menu_catalogue_id'],
                        'order' => $payload['menu']['order'][$key],
                        'user_id' => Auth::id(),
                    ];
                    if ($menuId == 0) {
                        $menuSave = $this->menuRepository->create($menuArray);
                    } else {
                        $menuSave = $this->menuRepository->update($menuId, $menuArray);
                        if ($menuSave->rgt - $menuSave->lft > 1) {
                            $conditionUpdate = [
                                ['lft', '>', $menuSave->lft],
                                ['rgt', '<', $menuSave->rgt],
                            ];
                            $payloadUpdate = ['menu_catalogue_id' => $payload['menu_catalogue_id']];
                            $this->menuRepository->updateByWhere($conditionUpdate, $payloadUpdate);
                        }
                    }
                    if ($menuSave->id > 0) {
                        $menuSave->languages()->detach($languageId);
                        $payloadLanguage = [
                            'language_id' => $languageId,
                            'name' => $val,
                            'canonical' => $payload['menu']['canonical'][$key]
                        ];
                        $this->menuRepository->createPivot($menuSave, $payloadLanguage, 'languages');
                    }
                }
                $this->initialize($languageId);
                $this->nestedset($this->nestedset);
            }
            DB::commit();
            return true;
        } catch (Exception $e) {
            dd($e->getMessage());
            DB::rollBack();
            return false;
        }
    }

    // Thêm mới menu con trong trường hợp input[name=id] = 0 or cập nhật menu con trong trường hợp input[name=id] != 0
    public function saveChildren($request, $languageId, $menu)
    {
        DB::beginTransaction();
        try {
            $payload = $request->only('menu');
            if (count($payload['menu']['name'])) {
                foreach ($payload['menu']['name'] as $key => $val) {
                    $menuId = $payload['menu']['id'][$key];
                    $menuArray = [
                        'menu_catalogue_id' => $menu->menu_catalogue_id,
                        'parent_id' => $menu->id,
                        'order' => $payload['menu']['order'][$key],
                        'user_id' => Auth::id(),
                    ];

                    $menuSave = $menuId == 0 ? $this->menuRepository->create($menuArray) : $this->menuRepository->update($menuId, $menuArray);

                    if ($menuSave->id > 0) {
                        $menuSave->languages()->detach($languageId);
                        $payloadLanguage = [
                            'language_id' => $languageId,
                            'name' => $val,
                            'canonical' => $payload['menu']['canonical'][$key]
                        ];
                        $this->menuRepository->createPivot($menuSave, $payloadLanguage, 'languages');
                    }
                }
                $this->initialize($languageId);
                $this->nestedset($this->nestedset);
            }
            DB::commit();
            return true;
        } catch (Exception $e) {
            dd($e->getMessage());
            DB::rollBack();
            return false;
        }
    }

    public function saveTranslateMenu($request, $languageId)
    {
        DB::beginTransaction();
        try {
            $payload = $request->only('translate');
            if (count($payload['translate']['name'])) {
                foreach ($payload['translate']['name'] as $key => $val) {
                    if ($val == null) {
                        continue;
                    }
                    $temp = [
                        'language_id' => $languageId,
                        'name' => $val,
                        'canonical' => $payload['translate']['canonical'][$key]
                    ];
                    $menu = $this->menuRepository->findById($payload['translate']['id'][$key]);
                    $menu->languages()->detach($languageId);
                    $this->menuRepository->createPivot($menu, $temp, 'languages');
                }
            }
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    // cập nhật parent_id khi kéo các menu
    public function dragUpdate($json = [], $menuCatalogueId = 0, $languageId = 1, $parentId = 0)
    {
        if (count($json)) {
            foreach ($json as $key => $val) {
                $update = [
                    'order' => count($json) - $key,
                    'parent_id' => $parentId
                ];
                $menu = $this->menuRepository->update($val['id'], $update);
                if (isset($val['children']) && count($val['children'])) {
                    $this->dragUpdate($val['children'], $menuCatalogueId, $languageId, $val['id']);
                }
            }
        }
        $this->initialize($languageId);
        $this->nestedset($this->nestedset);
    }

    public function delete($id)
    {
        DB::beginTransaction();
        try {
            $condition = [
                ['menu_catalogue_id', '=', $id]
            ];
            $this->menuRepository->forceDeleteByCondition($condition);
            $this->menuCatalogueRepository->forceDelete($id);
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    public function convertMenu($menuList = [])
    {
        $temp = [];
        $fields = ['name', 'canonical', 'order', 'id'];
        if (count($menuList)) {
            foreach ($menuList as $key => $val) {
                foreach ($fields as $field) {
                    if ($field == 'name' || $field == 'canonical') {
                        $temp[$field][] = $val->languages->first()->pivot->{$field};
                    } else {
                        $temp[$field][] = $val->{$field};
                    }
                }
            }
        }
        return $temp;
    }

    private function initialize($languageId)
    {
        $this->nestedset = new Nestedsetbie([
            'table' => 'menus',
            'foreignkey' => 'menu_id',
            'isMenu' => true,
            'language_id' =>  $languageId,
        ]);
    }
}
