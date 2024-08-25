<?php

namespace App\Services;

use App\Repositories\MenuCatalogueRepository;
use App\Repositories\MenuRepository;
use App\Services\Interfaces\MenuCatalogueServiceInterface;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Class MenuService
 * @package App\Services
 */
class MenuCatalogueService extends BaseService implements MenuCatalogueServiceInterface
{
    protected $menuCatalogueRepository;

    public function __construct(MenuCatalogueRepository $menuCatalogueRepository)
    {
        $this->menuCatalogueRepository = $menuCatalogueRepository;
    }

    public function paginate($request)
    {
        $condition['keyword'] = addslashes($request->input('keyword'));
        $perPage = $request->input('perpage') != null ? $request->integer('perpage') : 20;
        return $this->menuCatalogueRepository->pagination($this->paginateSelect(), $condition, [], $perPage, ['path' => 'menu/index'], []);
    }

    public function create($request)
    {
        DB::beginTransaction();
        try {
            $payload = $request->only('name', 'keyword');
            $payload['keyword'] = Str::slug($payload['keyword']);
            $menuCatalogue = $this->menuCatalogueRepository->create($payload);
            DB::commit();
            return [
                'name' => $menuCatalogue->name,
                'id' => $menuCatalogue->id
            ];
        } catch (Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    public function update($id, $request)
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

    public function delete($id)
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

    private function paginateSelect()
    {
        return [
            'id',
            'name',
            'keyword',
            'publish',
        ];
    }
}
