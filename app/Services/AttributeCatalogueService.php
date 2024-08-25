<?php

namespace App\Services;

use App\Classes\Nestedsetbie;
use App\Models\AttributeCatalogue;
use App\Repositories\AttributeCatalogueRepository;
use App\Repositories\RouterRepository;
use App\Services\Interfaces\AttributeCatalogueServiceInterface;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Class AttributeCatalogueService
 * @package App\Services
 */
class AttributeCatalogueService extends BaseService implements AttributeCatalogueServiceInterface
{
    protected $attributeCatalogueRepository;
    protected $nestedset;
    protected $controllerName = 'AttributeCatalogueController';

    public function __construct(AttributeCatalogueRepository $attributeCatalogueRepository, RouterRepository $routerRepository)
    {
        $this->attributeCatalogueRepository = $attributeCatalogueRepository;
        parent::__construct($routerRepository);
    }

    public function paginate($request, $languageId)
    {
        $perPage = $request->input('perpage') != null ? $request->integer('perpage') : 20;
        $condition = [
            'keyword' => addslashes($request->input('keyword')),
            'publish' => $request->input('publish') != null ? $request->integer('publish') : -1,
            'where' => [
                ['attribute_catalogue_language.language_id', '=', $languageId]
            ],
        ];
        $join = [
            ['attribute_catalogue_language', 'attribute_catalogue_language.attribute_catalogue_id', '=', 'attribute_catalogues.id']
        ];
        $orderBy = [
            'attribute_catalogues.lft', 'ASC'
        ];
        $extend = ['path' => 'attribute/catalogue/index'];
        return $this->attributeCatalogueRepository->pagination($this->paginateSelect(), $condition, $join, $perPage, $extend, [], $orderBy);
    }

    public function create($request, $languageId)
    {
        DB::beginTransaction();
        try {
            $attributeCatalogue = $this->createAttributeCatalogue($request);
            if ($attributeCatalogue->id > 0) {
                $this->updateLanguageForAttributeCatalogue($attributeCatalogue, $request, $languageId);
                $this->createRouter($attributeCatalogue, $request, $this->controllerName, $languageId);
            }

            $this->nestedset = new Nestedsetbie([
                'table' => 'attribute_catalogues',
                'foreignkey' => 'attribute_catalogue_id',
                'language_id' =>  $languageId,
            ]);
            $this->nestedset($this->nestedset);

            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    public function update($id, $request, $languageId)
    {
        DB::beginTransaction();
        try {
            $attributeCatalogue = $this->attributeCatalogueRepository->findById($id);
            if ($this->updateAttributeCatalogue($attributeCatalogue, $request)) {
                $this->updateLanguageForAttributeCatalogue($attributeCatalogue, $request, $languageId);
                $this->updateRouter($attributeCatalogue, $request, $this->controllerName, $languageId);
            }

            $this->nestedset = new Nestedsetbie([
                'table' => 'attribute_catalogues',
                'foreignkey' => 'attribute_catalogue_id',
                'language_id' =>  $languageId,
            ]);
            $this->nestedset($this->nestedset);

            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    public function delete($id, $languageId)
    {
        DB::beginTransaction();
        try {
            $this->attributeCatalogueRepository->delete($id);
            $this->routerRepository->forceDeleteByCondition([
                ['module_id', '=', $id],
                ['controllers', '=', 'App\Http\Controllers\Frontend\\' . $this->controllerName . '']
            ]);

            $this->nestedset = new Nestedsetbie([
                'table' => 'attribute_catalogues',
                'foreignkey' => 'attribute_catalogue_id',
                'language_id' =>  $languageId,
            ]);
            $this->nestedset($this->nestedset);

            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    private function createAttributeCatalogue($request)
    {
        $payload = $request->only($this->payload());
        $payload['user_id'] = Auth::id();
        $payload['album'] = $this->formatAlbum($payload['album'] ?? null);
        return $this->attributeCatalogueRepository->create($payload);
    }

    private function updateAttributeCatalogue($attributeCatalogue, $request)
    {
        $payload = $request->only($this->payload());
        $payload['album'] = $this->formatAlbum($payload['album'] ?? null);
        return $this->attributeCatalogueRepository->update($attributeCatalogue->id, $payload);
    }

    private function updateLanguageForAttributeCatalogue($attributeCatalogue, $request, $languageId)
    {
        $payload = $request->only($this->payloadLanguage());
        $payload = $this->formatLanguagePayload($payload, $attributeCatalogue, $languageId);
        $attributeCatalogue->languages()->detach($payload['language_id']);
        return $this->attributeCatalogueRepository->createPivot($attributeCatalogue, $payload, 'languages');
    }

    private function formatLanguagePayload($payload, $attributeCatalogue, $languageId)
    {
        $payload['canonical'] = Str::slug($payload['canonical']);
        $payload['language_id'] = $languageId;
        $payload['attribute_catalogue_id'] = $attributeCatalogue->id;
        return $payload;
    }

    private function paginateSelect()
    {
        return [
            'attribute_catalogues.id',
            'attribute_catalogues.publish',
            'attribute_catalogues.image',
            'attribute_catalogues.level',
            'attribute_catalogues.order',
            'attribute_catalogue_language.name',
            'attribute_catalogue_language.canonical'
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
