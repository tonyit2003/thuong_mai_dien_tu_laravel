<?php

namespace App\Services;

use App\Repositories\AttributeRepository;
use App\Repositories\RouterRepository;
use App\Services\Interfaces\AttributeServiceInterface;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Class AttributeCatalogueService
 * @package App\Services
 */
class AttributeService extends BaseService implements AttributeServiceInterface
{
    protected $attributeRepository;
    protected $controllerName = 'AttributeController';

    public function __construct(AttributeRepository $attributeRepository, RouterRepository $routerRepository)
    {
        $this->attributeRepository = $attributeRepository;
        parent::__construct($routerRepository);
    }

    public function paginate($request, $languageId)
    {
        $perPage = $request->input('perpage') != null ? $request->integer('perpage') : 20;
        $condition = [
            'keyword' => addslashes($request->input('keyword')),
            'publish' => $request->input('publish') != null ? $request->integer('publish') : -1,
            'attribute_catalogue_id' => $request->input('attribute_catalogue_id') != null ? $request->integer('attribute_catalogue_id') : 0,
            'where' => [
                ['attribute_language.language_id', '=', $languageId]
            ]
        ];
        $join = [
            ['attribute_language', 'attribute_language.attribute_id', '=', 'attributes.id'],
            ['attribute_catalogue_attribute', 'attribute_catalogue_attribute.attribute_id', '=', 'attributes.id']
        ];
        $orderBy = [
            'attributes.id',
            'DESC'
        ];
        $extend = [
            'path' => 'attribute/index',
            'groupBy' => $this->paginateSelect()
        ];
        $relations = ['attribute_catalogues'];
        return $this->attributeRepository->pagination($this->paginateSelect(), $condition, $join, $perPage, $extend, $relations, $orderBy, $this->whereRaw($request, $languageId));
    }

    public function create($request, $languageId)
    {
        DB::beginTransaction();
        try {
            $attribute = $this->createAttribute($request);
            if ($attribute->id > 0) {
                $this->updateLanguageForAttribute($attribute, $request, $languageId);
                $this->updateCatalogueForAttribute($attribute, $request);
            }

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
            $attribute = $this->attributeRepository->findById($id);
            if ($this->updateAttribute($attribute, $request)) {
                $this->updateLanguageForAttribute($attribute, $request, $languageId);
                $this->updateCatalogueForAttribute($attribute, $request);
            }
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
            $this->attributeRepository->delete($id);
            $this->routerRepository->forceDeleteByCondition([
                ['module_id', '=', $id],
                ['controllers', '=', 'App\Http\Controllers\Frontend\\' . $this->controllerName . '']
            ]);

            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    private function createAttribute($request)
    {
        $payload = $request->only($this->payload());
        $payload['user_id'] = Auth::id();
        $payload['album'] = $this->formatAlbum($payload['album'] ?? null);
        return $this->attributeRepository->create($payload);
    }

    private function updateAttribute($attribute, $request)
    {
        $payload = $request->only($this->payload());
        $payload['album'] = $this->formatAlbum($payload['album'] ?? null);
        return $this->attributeRepository->update($attribute->id, $payload);
    }

    private function updateLanguageForAttribute($attribute, $request, $languageId)
    {
        $payload = $request->only($this->payloadLanguage());
        $payload = $this->formatLanguagePayload($payload, $attribute->id, $languageId);
        $attribute->languages()->detach($payload['language_id']);
        return $this->attributeRepository->createPivot($attribute, $payload, 'languages');
    }

    private function formatLanguagePayload($payload, $attributeId, $languageId)
    {
        $payload['language_id'] = $languageId;
        $payload['attribute_id'] = $attributeId;
        return $payload;
    }

    private function updateCatalogueForAttribute($attribute, $request)
    {
        $catalogue = $this->catalogue($request);
        $attribute->attribute_catalogues()->sync($catalogue);
    }

    private function catalogue($request)
    {
        return array_unique(array_merge(($request->input('catalogue') != null && is_array($request->input('catalogue'))) ? $request->input('catalogue') : [], [$request->attribute_catalogue_id]));
    }

    private function whereRaw($request, $languageId)
    {
        $rawCondition = [];
        $attributeCatalogueId = $request->input('attribute_catalogue_id') != null ? $request->integer('attribute_catalogue_id') : 0;
        if ($attributeCatalogueId > 0) {
            $rawCondition['whereRaw'] = [
                [
                    'attribute_catalogue_attribute.attribute_catalogue_id IN (
                        SELECT id
                        FROM attribute_catalogues
                        JOIN attribute_catalogue_language ON attribute_catalogues.id = attribute_catalogue_language.attribute_catalogue_id
                        WHERE lft >= (SELECT lft FROM attribute_catalogues WHERE attribute_catalogues.id = ?)
                        AND rgt <= (SELECT rgt FROM attribute_catalogues WHERE attribute_catalogues.id = ?)
                        AND attribute_catalogue_language.language_id = ?
                    )',
                    [$attributeCatalogueId, $attributeCatalogueId, $languageId]
                ]
            ];
        }
        return $rawCondition;
    }

    private function paginateSelect()
    {
        return [
            'attributes.id',
            'attributes.publish',
            'attributes.image',
            'attributes.order',
            'attribute_language.name',
            'attribute_language.canonical'
        ];
    }

    private function payload()
    {
        return ['follow', 'publish', 'image', 'album', 'attribute_catalogue_id', 'catalogue'];
    }

    private function payloadLanguage()
    {
        return ['name', 'description', 'content', 'meta_title', 'meta_keyword', 'meta_description', 'canonical'];
    }
}
