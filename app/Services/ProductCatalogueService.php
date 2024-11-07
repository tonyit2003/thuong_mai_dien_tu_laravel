<?php

namespace App\Services;

use App\Classes\Nestedsetbie;
use App\Models\ProductCatalogue;
use App\Models\ProductCatalogueLanguage;
use App\Repositories\AttributeCatalogueRepository;
use App\Repositories\AttributeRepository;
use App\Repositories\ProductCatalogueRepository;
use App\Repositories\RouterRepository;
use App\Services\Interfaces\ProductCatalogueServiceInterface;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Class ProductCatalogueService
 * @package App\Services
 */
class ProductCatalogueService extends BaseService implements ProductCatalogueServiceInterface
{
    protected $productCatalogueRepository;
    protected $attributeCatalogueRepository;
    protected $attributeRepository;
    protected $nestedset;
    protected $controllerName = 'ProductCatalogueController';

    public function __construct(ProductCatalogueRepository $productCatalogueRepository, RouterRepository $routerRepository, AttributeCatalogueRepository $attributeCatalogueRepository, AttributeRepository $attributeRepository)
    {
        $this->productCatalogueRepository = $productCatalogueRepository;
        $this->attributeCatalogueRepository = $attributeCatalogueRepository;
        $this->attributeRepository = $attributeRepository;
        parent::__construct($routerRepository);
    }

    public function paginate($request, $languageId)
    {
        $perPage = $request->input('perpage') != null ? $request->integer('perpage') : 20;
        $condition = [
            'keyword' => addslashes($request->input('keyword')),
            'publish' => $request->input('publish') != null ? $request->integer('publish') : -1,
            'where' => [
                ['product_catalogue_language.language_id', '=', $languageId]
            ],
        ];
        $join = [
            ['product_catalogue_language', 'product_catalogue_language.product_catalogue_id', '=', 'product_catalogues.id']
        ];
        $orderBy = [
            'product_catalogues.lft',
            'ASC'
        ];
        $extend = ['path' => 'product/catalogue/index'];
        return $this->productCatalogueRepository->pagination($this->paginateSelect(), $condition, $join, $perPage, $extend, [], $orderBy);
    }

    public function create($request, $languageId)
    {
        DB::beginTransaction();
        try {
            $productCatalogue = $this->createProductCatalogue($request);
            if ($productCatalogue->id > 0) {
                $this->updateLanguageForProductCatalogue($productCatalogue, $request, $languageId);
                $this->createRouter($productCatalogue, $request, $this->controllerName, $languageId);
            }

            $this->nestedset = new Nestedsetbie([
                'table' => 'product_catalogues',
                'foreignkey' => 'product_catalogue_id',
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
            $productCatalogue = $this->productCatalogueRepository->findById($id);
            if ($this->updateProductCatalogue($productCatalogue, $request)) {
                $this->updateLanguageForProductCatalogue($productCatalogue, $request, $languageId);
                $this->updateRouter($productCatalogue, $request, $this->controllerName, $languageId);
            }

            $this->nestedset = new Nestedsetbie([
                'table' => 'product_catalogues',
                'foreignkey' => 'product_catalogue_id',
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
            $this->productCatalogueRepository->delete($id);
            $this->routerRepository->forceDeleteByCondition([
                ['module_id', '=', $id],
                ['controllers', '=', 'App\Http\Controllers\Frontend\\' . $this->controllerName . '']
            ]);

            $this->nestedset = new Nestedsetbie([
                'table' => 'product_catalogues',
                'foreignkey' => 'product_catalogue_id',
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

    public function setAttribute($product)
    {
        $attribute = $product->attribute;
        if (!is_array($attribute)) {
            $attribute = json_decode($attribute, true);
        }
        $productCatalogueId = (int)$product->product_catalogue_id;
        $productCatalogue = $this->productCatalogueRepository->findById($productCatalogueId);
        if (!is_array($productCatalogue->attribute)) {
            $payload['attribute'] = $attribute;
        } else {
            $mergeArray = $productCatalogue->attribute;
            foreach ($attribute as $key => $val) {
                if (!isset($mergeArray[$key])) {
                    $mergeArray[$key] = $val;
                } else {
                    $mergeArray[$key] = array_values(array_unique(array_merge($mergeArray[$key], $val)));
                }
            }
            $flatAttributeArray = array_merge(...$mergeArray);
            $attributeList = $this->attributeRepository->findAttributeProductVariant($flatAttributeArray, $productCatalogue->id);
            $payload['attribute'] = array_map(function ($newArray) use ($attributeList) {
                // lấy phần giao của $newArray (đại diện cho từng phần tử trong mảng $mergeArray) và $attributeList->all()
                return array_intersect($newArray, $attributeList->all());
            }, $mergeArray);
        }
        $result = $this->productCatalogueRepository->update($productCatalogue->id, $payload);
        return $result;
    }

    public function getFilterList($attribute = [], $language)
    {
        $attributeCatalogueIds = array_keys($attribute);
        $attributeIds = array_unique(array_merge(...$attribute));
        $attributeCatalogues = $this->attributeCatalogueRepository->findByCondition(
            [config('apps.general.publish')],
            true,
            ['languages' => function ($query) use ($language) {
                $query->where('language_id', $language);
            }],
            ['id', 'ASC'],
            [
                'whereIn' => $attributeCatalogueIds,
                'whereInField' => 'id'
            ]
        );
        $attributes = $this->attributeRepository->findByCondition(
            [config('apps.general.publish')],
            true,
            ['languages' => function ($query) use ($language) {
                $query->where('language_id', $language);
            }],
            ['id', 'ASC'],
            [
                'whereIn' => $attributeIds,
                'whereInField' => 'id'
            ]
        );
        foreach ($attributeCatalogues as $key => $val) {
            $attributeItem = [];
            foreach ($attributes as $index => $item) {
                if ($item->attribute_catalogue_id === $val->id) {
                    $attributeItem[] = $item;
                }
            }
            $val->attributes = $attributeItem;
        }
        return $attributeCatalogues;
    }

    private function createProductCatalogue($request)
    {
        $payload = $request->only($this->payload());
        $payload['user_id'] = Auth::id();
        $payload['album'] = $this->formatAlbum($payload['album'] ?? null);
        return $this->productCatalogueRepository->create($payload);
    }

    private function updateProductCatalogue($productCatalogue, $request)
    {
        $payload = $request->only($this->payload());
        $payload['album'] = $this->formatAlbum($payload['album'] ?? null);
        return $this->productCatalogueRepository->update($productCatalogue->id, $payload);
    }

    private function updateLanguageForProductCatalogue($productCatalogue, $request, $languageId)
    {
        $payload = $request->only($this->payloadLanguage());
        $payload = $this->formatLanguagePayload($payload, $productCatalogue, $languageId);
        $productCatalogue->languages()->detach($payload['language_id']);
        return $this->productCatalogueRepository->createPivot($productCatalogue, $payload, 'languages');
    }

    private function formatLanguagePayload($payload, $productCatalogue, $languageId)
    {
        $payload['canonical'] = Str::slug($payload['canonical']);
        $payload['language_id'] = $languageId;
        $payload['product_catalogue_id'] = $productCatalogue->id;
        return $payload;
    }

    private function paginateSelect()
    {
        return [
            'product_catalogues.id',
            'product_catalogues.publish',
            'product_catalogues.image',
            'product_catalogues.level',
            'product_catalogues.order',
            'product_catalogue_language.name',
            'product_catalogue_language.canonical'
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
