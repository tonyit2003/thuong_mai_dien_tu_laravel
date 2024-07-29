<?php

namespace App\Services;

use App\Repositories\ProductRepository;
use App\Repositories\RouterRepository;
use App\Services\Interfaces\ProductServiceInterface;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Class ProductCatalogueService
 * @package App\Services
 */
class ProductService extends BaseService implements ProductServiceInterface
{
    protected $productRepository;
    protected $controllerName = 'ProductController';

    public function __construct(ProductRepository $productRepository, RouterRepository $routerRepository)
    {
        $this->productRepository = $productRepository;
        parent::__construct($routerRepository);
    }

    public function paginate($request, $languageId)
    {
        $perPage = $request->input('perpage') != null ? $request->integer('perpage') : 20;
        $condition = [
            'keyword' => addslashes($request->input('keyword')),
            'publish' => $request->input('publish') != null ? $request->integer('publish') : -1,
            'product_catalogue_id' => $request->input('product_catalogue_id') != null ? $request->integer('product_catalogue_id') : 0,
            'where' => [
                ['product_language.language_id', '=', $languageId]
            ]
        ];
        $join = [
            ['product_language', 'product_language.product_id', '=', 'products.id'],
            ['product_catalogue_product', 'product_catalogue_product.product_id', '=', 'products.id']
        ];
        $orderBy = [
            'products.id', 'DESC'
        ];
        $extend = [
            'path' => 'product/index',
            'groupBy' => $this->paginateSelect()
        ];
        $relations = ['product_catalogues'];
        return $this->productRepository->pagination($this->paginateSelect(), $condition, $join, $perPage, $extend, $relations, $orderBy, $this->whereRaw($request, $languageId));
    }

    public function create($request, $languageId)
    {
        DB::beginTransaction();
        try {
            $product = $this->createProduct($request);
            if ($product->id > 0) {
                $this->updateLanguageForProduct($product, $request, $languageId);
                $this->updateCatalogueForProduct($product, $request);
                $this->createRouter($product, $request, $this->controllerName);
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
            $product = $this->productRepository->findById($id);
            if ($this->updateProduct($product, $request)) {
                $this->updateLanguageForProduct($product, $request, $languageId);
                $this->updateCatalogueForProduct($product, $request);
                $this->updateRouter($product, $request, $this->controllerName);
            }
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    public function updateStatus($post = [])
    {
        DB::beginTransaction();
        try {
            $payload[$post['field']] = (($post['value'] == 1) ? 0 : 1);
            $this->productRepository->update($post['modelId'], $payload);
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    public function updateStatusAll($post = [])
    {
        DB::beginTransaction();
        try {
            $payload[$post['field']] = $post['value'];
            $this->productRepository->updateByWhereIn('id', $post['id'], $payload);
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
            $this->productRepository->delete($id);

            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    private function createProduct($request)
    {
        $payload = $request->only($this->payload());
        $payload['user_id'] = Auth::id();
        $payload['album'] = $this->formatAlbum($payload['album'] ?? null);
        return $this->productRepository->create($payload);
    }

    private function updateProduct($product, $request)
    {
        $payload = $request->only($this->payload());
        $payload['album'] = $this->formatAlbum($payload['album'] ?? null);
        return $this->productRepository->update($product->id, $payload);
    }

    private function updateLanguageForProduct($product, $request, $languageId)
    {
        $payload = $request->only($this->payloadLanguage());
        $payload = $this->formatLanguagePayload($payload, $product->id, $languageId);
        $product->languages()->detach($payload['language_id']);
        return $this->productRepository->createPivot($product, $payload, 'languages');
    }

    private function formatLanguagePayload($payload, $productId, $languageId)
    {
        $payload['canonical'] = Str::slug($payload['canonical']);
        $payload['language_id'] = $languageId;
        $payload['product_id'] = $productId;
        return $payload;
    }

    private function updateCatalogueForProduct($product, $request)
    {
        $catalogue = $this->catalogue($request);
        $product->product_catalogues()->sync($catalogue);
    }

    private function catalogue($request)
    {
        return array_unique(array_merge(($request->input('catalogue') != null && is_array($request->input('catalogue'))) ? $request->input('catalogue') : [], [$request->product_catalogue_id]));
    }

    private function whereRaw($request, $languageId)
    {
        $rawCondition = [];
        $productCatalogueId = $request->input('product_catalogue_id') != null ? $request->integer('product_catalogue_id') : 0;
        if ($productCatalogueId > 0) {
            $rawCondition['whereRaw'] = [
                [
                    'product_catalogue_product.product_catalogue_id IN (
                        SELECT id
                        FROM product_catalogues
                        JOIN product_catalogue_language ON product_catalogues.id = product_catalogue_language.product_catalogue_id
                        WHERE lft >= (SELECT lft FROM product_catalogues WHERE product_catalogues.id = ?)
                        AND rgt <= (SELECT rgt FROM product_catalogues WHERE product_catalogues.id = ?)
                        AND product_catalogue_language.language_id = ?
                    )',
                    [$productCatalogueId, $productCatalogueId, $languageId]
                ]
            ];
        }
        return $rawCondition;
    }

    private function paginateSelect()
    {
        return [
            'products.id',
            'products.publish',
            'products.image',
            'products.order',
            'product_language.name',
            'product_language.canonical'
        ];
    }

    private function payload()
    {
        return ['follow', 'publish', 'image', 'album', 'product_catalogue_id', 'catalogue'];
    }

    private function payloadLanguage()
    {
        return ['name', 'description', 'content', 'meta_title', 'meta_keyword', 'meta_description', 'canonical'];
    }
}
