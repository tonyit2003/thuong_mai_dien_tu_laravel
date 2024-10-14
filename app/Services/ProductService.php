<?php

namespace App\Services;

use App\Repositories\AttributeCatalogueRepository;
use App\Repositories\AttributeRepository;
use App\Repositories\ProductRepository;
use App\Repositories\ProductVariantAttributeRepository;
use App\Repositories\ProductVariantLanguageRepository;
use App\Repositories\PromotionRepository;
use App\Repositories\RouterRepository;
use App\Services\Interfaces\ProductServiceInterface;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;

/**
 * Class ProductCatalogueService
 * @package App\Services
 */
class ProductService extends BaseService implements ProductServiceInterface
{
    protected $productRepository;
    protected $productVariantLanguageRepository;
    protected $productVariantAttributeRepository;
    protected $promotionRepository;
    protected $attributeCatalogueRepository;
    protected $attributeRepository;
    protected $controllerName = 'ProductController';

    public function __construct(ProductRepository $productRepository, RouterRepository $routerRepository, ProductVariantLanguageRepository $productVariantLanguageRepository, ProductVariantAttributeRepository $productVariantAttributeRepository, PromotionRepository $promotionRepository, AttributeCatalogueRepository $attributeCatalogueRepository, AttributeRepository $attributeRepository)
    {
        $this->productRepository = $productRepository;
        $this->productVariantLanguageRepository = $productVariantLanguageRepository;
        $this->productVariantAttributeRepository = $productVariantAttributeRepository;
        $this->promotionRepository = $promotionRepository;
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
            'products.id',
            'DESC'
        ];
        $extend = [
            'path' => 'product/index',
            'groupBy' => $this->paginateSelect()
        ];
        $relations = ['product_catalogues'];
        return $this->productRepository->pagination($this->paginateSelect(), $condition, $join, $perPage, $extend, $relations, $orderBy, $this->whereRaw($request, $languageId));
    }

    public function paginateProduct($request, $languageId)
    {
        $id = $request->input('product_id') != null ? $request->integer('product_id') : 0;
        $quantity = $request->input('quantity') != null ? $request->integer('quantity') : -1;
        $keyword = addslashes($request->input('keyword')) != null ? addslashes($request->input('keyword')) : NULL;
        $condition = [
            'where' => [
                ['product_language.language_id', '=', $languageId],
                ['products.id', '=', $id]
            ]
        ];

        if ($keyword != null) {
            $condition = [
                'where' => [
                    ['product_language.language_id', '=', $languageId],
                    ['products.id', '=', $id],
                    ['product_variants.quantity', '<=', $quantity],
                    ['product_language.name', 'LIKE',  "%{$keyword}%"],
                ]
            ];
        }

        $join = [
            ['product_language', 'product_language.product_id', '=', 'products.id'],
            ['product_catalogue_product', 'product_catalogue_product.product_id', '=', 'products.id'],
            ['product_variants', 'product_variants.product_id', '=', 'products.id'],
            ['product_variant_language', 'product_variant_language.product_variant_id', '=', 'product_variants.id'],
        ];

        $extend = [
            'path' => 'ajax/product/getProduct',
            'groupBy' => $this->paginateSelectReceipt()
        ];

        $relations = [
            'product_catalogues',
            'product_variant_language',
            [
                'product_variants' => function ($query) use ($quantity) {
                    if ($quantity != -1) {
                        $query->where('product_variants.quantity', '<=', $quantity);
                    }
                }
            ]
        ];

        return $this->productRepository->getProductForReceipt($this->paginateSelectReceipt(), $condition, $join, $extend, $relations);
    }

    public function getAttribute($product, $language)
    {
        $attributeArray = json_decode($product->attribute, true);
        // Lấy danh sách attribute catalogue
        $attributeCatalogueIds = array_keys($attributeArray);
        $attributeCatalogues = $this->attributeCatalogueRepository->getAttributeCatalogueWhereIn($attributeCatalogueIds, 'attribute_catalogues.id', $language);
        // Lấy danh sách attribute
        $attributeIds = array_merge(...$attributeArray);
        $attributes = $this->attributeRepository->findAttributeByIdArray($attributeIds, $language, true);
        // Gán attribute vào attribute catalogue
        if (isset($attributeCatalogues)) {
            foreach ($attributeCatalogues as $attributeCatalogue) {
                $tempAttributes = [];
                foreach ($attributes as $attribute) {
                    if ($attributeCatalogue->id == $attribute->attribute_catalogue_id) {
                        $tempAttributes[] = $attribute;
                    }
                }
                $attributeCatalogue->attribute = $tempAttributes;
            }
        }
        $product->attributeCatalogue = $attributeCatalogues;
        return $product;
    }

    public function create($request, $languageId)
    {
        DB::beginTransaction();
        try {
            $product = $this->createProduct($request);
            if ($product->id > 0) {
                $this->updateLanguageForProduct($product, $request, $languageId);
                $this->updateCatalogueForProduct($product, $request);
                $this->createRouter($product, $request, $this->controllerName, $languageId);
                if ($request->input('attribute')) {
                    $this->createVariant($product, $request, $languageId);
                }
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
                $this->updateRouter($product, $request, $this->controllerName, $languageId);
                // lặp qua từng ProductVariant liên quan đến $product. (1 Product có nhiều ProductVariant)
                $product->product_variants()->each(function ($query) {
                    // detach() => xóa các bản ghi trong bảng pivot liên quan đến $product
                    $query->languages()->detach();
                    $query->attributes()->detach();
                    // delete() => xóa các bản ghi trên bảng đang chỉ định liên quan đến $product
                    $query->delete();
                });
                if ($request->input('attribute')) {
                    $this->createVariant($product, $request, $languageId);
                }
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
            $this->productRepository->delete($id);
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

    public function combineProductAndPromotion($productIds, $products)
    {
        $promotions = $this->promotionRepository->findByProduct($productIds);
        if ($promotions) {
            foreach ($products as $keyProduct => $valProduct) {
                foreach ($promotions as $keyPromotion => $valPromotion) {
                    if ($valPromotion->product_id === $valProduct->id) {
                        $products[$keyProduct]->promotions = $valPromotion;
                    }
                }
            }
        }
        return $products;
    }

    private function createProduct($request)
    {
        $payload = $request->only($this->payload());
        $payload['user_id'] = Auth::id();
        $payload['album'] = $this->formatAlbum($payload['album'] ?? null);
        // convert_price() từ MyHelper.php
        $payload['price'] = convert_price($payload['price']) ?? 0;
        $payload['attributeCatalogue'] = $this->formatJson($request, 'attributeCatalogue');
        $payload['attribute'] = $this->formatJson($request, 'attribute');
        $payload['variant'] = $this->formatJson($request, 'variant');
        return $this->productRepository->create($payload);
    }

    private function updateProduct($product, $request)
    {
        $payload = $request->only($this->payload());
        $payload['album'] = $this->formatAlbum($payload['album'] ?? null);
        $payload['price'] = convert_price($payload['price']);
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

    private function createVariant($product, $request, $languageId)
    {
        $payload = $request->only(['variant', 'productVariant', 'attribute']);
        $variant = $this->createVariantArray($payload, $product);
        // tạo nhiều bản ghi mới trong bảng product_variants với dữ liệu từ mảng $variant và trả về collection các đối tượng mới được tạo ra
        // (create($payload) => chỉ tạo ra 1 bản ghi (1 hàng trong csdl))
        $variants = $product->product_variants()->createMany($variant);
        // trích xuất giá trị của trường id từ mỗi đối tượng trong $variants và trả về một Collection chỉ chứa các giá trị id.
        $variantIds = $variants->pluck('id');
        $productVariantLanguage = [];
        $productVariantAttribute = [];
        // array_values => lấy tất cả các giá trị của một mảng, và kết quả trả về là một mảng mới mà trong đó các chỉ mục (keys) được đánh số liên tục bắt đầu từ 0, 1, 2,...
        $attributeCombines = $this->combineAttribute(array_values($payload['attribute']));
        if (count($variantIds)) {
            foreach ($variantIds as $key => $val) { // $key: 0, 1, 2,...
                $productVariantLanguage[] = [
                    'product_variant_id' => $val,
                    'language_id' => $languageId,
                    'name' => $payload['productVariant']['name'][$key]
                ];
                if (count($attributeCombines)) {
                    foreach ($attributeCombines[$key] as $attributeId) {
                        $productVariantAttribute[] = [
                            'product_variant_id' => $val,
                            'attribute_id' => $attributeId
                        ];
                    }
                }
            }
        }
        $this->productVariantLanguageRepository->createBatch($productVariantLanguage);
        $this->productVariantAttributeRepository->createBatch($productVariantAttribute);
    }

    private function createVariantArray($payload = [], $product): array
    {
        $variant = [];
        if (isset($payload['variant']['sku']) && count($payload['variant']['sku'])) {
            foreach ($payload['variant']['sku'] as $key => $val) {
                $vId = $payload['productVariant']['id'][$key] ?? '';
                $productVariantId = sortString($vId);
                // tạo một UUID duy nhất, sử dụng hàm băm SHA-1 và dựa trên không gian tên cùng với một chuỗi đầu vào duy nhất.
                $uuid = Uuid::uuid5(Uuid::NAMESPACE_DNS, $product->id . ', ' . $payload['productVariant']['id'][$key]);
                $variant[] = [
                    'uuid' => $uuid,
                    'code' => $productVariantId,
                    'quantity' => $payload['variant']['quantity'][$key] ?? 0,
                    'sku' => $val,
                    'price' => $payload['variant']['price'][$key] ? convert_price($payload['variant']['price'][$key]) : '',
                    'barcode' => $payload['variant']['barcode'][$key] ?? '',
                    'file_name' => $payload['variant']['file_name'][$key] ?? '',
                    'file_url' => $payload['variant']['file_url'][$key] ?? '',
                    'album' => $payload['variant']['album'][$key] ?? '',
                    'user_id' => Auth::id(),
                ];
            }
        }
        return $variant;
    }

    // tạo ra các phiên bản từ các thuộc tính
    /*
        $attributes = [
            [ "2", "4" ],
            [ "8", "9" ],
            [ "12" ]
        ];

        index = 2
            subCombines = [[]]
            combine = [[12]]
        index = 1
            subCombines = [[12]]
            combine = [[8, 12],[9, 12]]
        index = 0
            subCombines = [[8, 12],[9, 12]]
            combine = [[2, 8, 12][2, 9, 12][4, 8, 12][4, 9, 12]]
    */
    private function combineAttribute($attributes = [], $index = 0)
    {
        if ($index == count($attributes)) {
            return [[]];
        }
        $subCombines = $this->combineAttribute($attributes, $index + 1);
        $combine = [];
        foreach ($attributes[$index] as $key => $val) {
            foreach ($subCombines as $keySub => $valSub) {
                $combine[] = array_merge([$val], $valSub);
            }
        }
        return $combine;
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
            'products.price',
            'product_language.name',
            'product_language.canonical'
        ];
    }

    private function paginateSelectReceipt()
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
        return [
            'follow',
            'publish',
            'image',
            'album',
            'price',
            'made_in',
            'code',
            'product_catalogue_id',
            'attributeCatalogue',
            'attribute',
            'variant',
        ];
    }

    private function payloadLanguage()
    {
        return ['name', 'description', 'content', 'meta_title', 'meta_keyword', 'meta_description', 'canonical'];
    }
}
