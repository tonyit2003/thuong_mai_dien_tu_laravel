<?php

namespace App\Services;

use App\Classes\Nestedsetbie;
use App\Repositories\MenuRepository;
use App\Services\Interfaces\MenuServiceInterface;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Class MenuService
 * @package App\Services
 */
class MenuService extends BaseService implements MenuServiceInterface
{
    protected $menuRepository;
    protected $nestedset;

    public function __construct(MenuRepository $menuRepository)
    {
        $this->menuRepository = $menuRepository;
    }

    public function paginate($request, $languageId)
    {
        return [];
    }

    public function create($request, $languageId)
    {
        DB::beginTransaction();
        try {
            $payload = $request->only('menu', 'menu_catalogue_id', 'type');
            if (count($payload['menu']['name'])) {
                foreach ($payload['menu']['name'] as $key => $val) {
                    $menuArray = [
                        'menu_catalogue_id' => $payload['menu_catalogue_id'],
                        'type' => $payload['type'],
                        'order' => $payload['menu']['order'][$key],
                        'user_id' => Auth::id(),
                    ];
                    $menu = $this->menuRepository->create($menuArray);
                    if ($menu->id > 0) {
                        $menu->languages()->detach($languageId);
                        $payloadLanguage = [
                            'language_id' => $languageId,
                            'name' => $val,
                            'canonical' => $payload['menu']['canonical'][$key]
                        ];
                        $this->menuRepository->createPivot($menu, $payloadLanguage, 'languages');
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

    public function update($id, $request, $languageId)
    {
        DB::beginTransaction();
        try {
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            return false;
        }
    }

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

    public function delete($id, $languageId)
    {
        DB::beginTransaction();
        try {
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    public function getAndConvertMenu($menu = null, $languageId = 1)
    {
        $condition = [
            ['parent_id', '=', $menu->id]
        ];
        $relation = ['languages' => function ($query) use ($languageId) {
            $query->where('language_id', $languageId);
        }];
        $menuList = $this->menuRepository->findByCondition($condition, true, $relation);
        return $this->convertMenu($menuList);
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

    private function paginateSelect()
    {
        return [
            'menus.id',
            'menus.publish',
            'menus.image',
            'menus.level',
            'menus.order',
            'menu_language.name',
            'menu_language.canonical'
        ];
    }

    private function payload()
    {
        return ['parent_id', 'follow', 'publish', 'image', 'album'];
    }

    private function payloadLanguage()
    {
        return ['name', 'description', 'content', 'meta_title', 'meta_keyword', 'meta_description', 'canonical'];
    }
}
