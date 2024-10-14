<?php

namespace App\Services;

use App\Repositories\ProductCatalogueRepository;
use App\Repositories\PromotionRepository;
use App\Repositories\WidgetRepository;
use App\Services\Interfaces\WidgetServiceInterface;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Class WidgetService
 * @package App\Services
 */
class WidgetService extends BaseService implements WidgetServiceInterface
{
    protected $widgetRepository;
    protected $promotionRepository;
    protected $productService;
    protected $productVariantService;
    protected $productCatalogueRepository;

    public function __construct(WidgetRepository $widgetRepository, PromotionRepository $promotionRepository, ProductService $productService, ProductCatalogueRepository $productCatalogueRepository, ProductVariantService $productVariantService)
    {
        $this->widgetRepository = $widgetRepository;
        $this->promotionRepository = $promotionRepository;
        $this->productService = $productService;
        $this->productVariantService = $productVariantService;
        $this->productCatalogueRepository = $productCatalogueRepository;
    }

    public function paginate($request)
    {
        $condition['keyword'] = addslashes($request->input('keyword'));
        $condition['publish'] = $request->input('publish') != null ? $request->integer('publish') : -1;
        $perPage = $request->input('perpage') != null ? $request->integer('perpage') : 20;
        return $this->widgetRepository->pagination($this->paginateSelect(), $condition, [], $perPage, ['path' => 'widget/index']);
    }

    public function getWidgets($params = [], $language = 1)
    {
        $whereIn = [];
        $whereInField = 'keyword';
        if (count($params)) {
            foreach ($params as $key => $val) {
                $whereIn[] = $val['keyword'];
            }
        }
        $widgets = $this->widgetRepository->getWidgetByWhereIn($whereIn, $whereInField);
        if (!is_null($widgets)) {
            $temp = [];
            foreach ($widgets as $keyWidget => $valWidget) {
                /**
                 * @var Widget $valWidget
                 */
                $class = loadClass($valWidget->model);
                // Lấy các đối tượng trong widget
                $object = $class->findByCondition(...$this->widgetArgument($valWidget, $language, $params[$keyWidget]));
                $model = lcfirst(str_replace('Catalogue', '', $valWidget->model));
                $replace = $model . 's';
                $service = $model . 'Service';
                $serviceVariant = $model . 'VariantService';
                if (count($object) && strpos($valWidget->model, 'Catalogue')) {
                    foreach ($object as $keyObject => $valObject) {
                        // lấy các đối tượng con của của danh mục cha
                        if (isset($params[$keyWidget]['children']) && $params[$keyWidget]['children'] == true) {
                            $valObject->children = $class->findByCondition(...$this->childrenArgument([$valObject->id], $language));
                        }
                        /* LẤY ALL SẢN PHẨM TỪ CÁC DANH MỤC CHA + CON */
                        if (strpos($valWidget->model, 'Catalogue')) {
                            // gộp các phần tử của mảng thành 1 chuỗi với ký tự phân cách là ','
                            // $parameters = implode(',', $objectIds);
                            $childrenId = $class->recursiveCategory([$valObject->id], $model); // lấy các id catalogue con
                            $ids = [];
                            foreach ($childrenId as $childId) {
                                $ids[] = $childId->id;
                            }
                            $classRepo = loadClass(ucfirst($model));
                            if ($valObject->rgt - $valObject->lft > 1) {
                                $valObject->{$replace} = $classRepo->findObjectByCategoryIds($ids, $model, $language);
                            }
                        }
                        // lấy khuyến mãi cho sản phẩm
                        if ($model === 'product' && isset($params[$keyWidget]['promotion']) && $params[$keyWidget]['promotion'] == true) {
                            // lấy một tập hợp các giá trị của một cột duy nhất từ cơ sở dữ liệu hoặc từ một collection
                            $productIds = $valObject->{$replace}->pluck('id')->toArray();
                            $valObject->{$replace} = $this->{$service}->combineProductAndPromotion($productIds, $valObject->{$replace});
                            foreach ($valObject->{$replace} as $keyProduct => $valProduct) {
                                $productVariantUuids = $valProduct->product_variants->pluck('uuid')->toArray();
                                $valProduct->product_variants = $this->{$serviceVariant}->combineProductVariantAndPromotion($productVariantUuids,  $valProduct->product_variants);
                            }
                        }
                        $widgets[$keyWidget]->object = $object;
                    }
                } else {
                    if ($model === 'product' && isset($params[$keyWidget]['promotion']) && $params[$keyWidget]['promotion'] == true) {
                        $productIds = $object->pluck('id')->toArray();
                        $object = $this->{$service}->combineProductAndPromotion($productIds, $object);
                        foreach ($object as $keyProduct => $valProduct) {
                            $productVariantUuids = $valProduct->product_variants->pluck('uuid')->toArray();
                            $valProduct->product_variants = $this->{$serviceVariant}->combineProductVariantAndPromotion($productVariantUuids,  $valProduct->product_variants);
                        }
                    }
                    $widgets[$keyWidget]->object = $object;
                }
                $temp[$valWidget->keyword] = $widgets[$keyWidget];
            }
        }
        return $temp;
    }

    public function create($request, $languageId)
    {
        DB::beginTransaction();
        try {
            $payload = $request->only('name', 'keyword', 'short_code', 'album', 'model', 'description');
            $payload['model_id'] = $request->input('modelItem.id');
            $payload['description'] = [
                $languageId => $payload['description']
            ];
            $payload['user_id'] = Auth::id();
            $this->widgetRepository->create($payload);
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
            $payload = $request->only('name', 'keyword', 'short_code', 'album', 'model', 'description');
            $payload['model_id'] = $request->input('modelItem.id');
            $description = [
                $languageId => $payload['description']
            ];
            $widget = $this->widgetRepository->findById($id);
            $widgetDescription = $widget->description;
            unset($widgetDescription[$languageId]);
            $payload['description'] = $widgetDescription + $description;
            $this->widgetRepository->update($id, $payload);
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    public function saveTranslate($request)
    {
        DB::beginTransaction();
        try {
            $widgetId = $request->input('widgetId');
            $translateId = $request->input('translateId');
            $description = [
                $translateId => $request->input('translate_description')
            ];
            $widget = $this->widgetRepository->findById($widgetId);
            $widgetDescription = $widget->description;
            unset($widgetDescription[$translateId]);
            $payload['description'] = $widgetDescription + $description;
            $this->widgetRepository->update($widgetId, $payload);
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
            $this->widgetRepository->delete($id);
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    private function paginateSelect()
    {
        return ['id', 'name', 'keyword', 'short_code', 'publish', 'description'];
    }

    private function widgetArgument($widget, $language, $param)
    {
        $relation = [
            'languages' => function ($query) use ($language) {
                $query->where('language_id', $language);
            }
        ];
        $withCount = [];
        if (strpos($widget->model, 'Catalogue')) {
            $model = lcfirst(str_replace('Catalogue', '', $widget->model));
            if (isset($param['promotion']) && $param['promotion'] == true) {
                $relation[$model . 's'] = function ($query) use ($param, $language, $model) {
                    $query->with([
                        'languages' =>
                        function ($query) use ($language) {
                            $query->where('language_id', $language);
                        },
                    ]);
                    $query->with([
                        $model . '_catalogues' =>
                        function ($query) use ($language) {
                            $query->with(['languages' => function ($query) use ($language) {
                                $query->where('language_id', $language);
                            }]);
                        }
                    ]);
                    if ($model === 'product') {
                        $query->with(['product_variants' => function ($query) use ($language) {
                            $query->with(['languages' => function ($query) use ($language) {
                                $query->where('language_id', $language);
                            }]);
                        }]);
                    }
                    // $query->take($param['limit'] ?? 8);
                    $query->where('publish', 1);
                    $query->orderBy('order', 'DESC');
                };
            }
            if (isset($param['countObject'])) {
                $withCount[] = $model . 's';
            }
        } else {
            $model = lcfirst($widget->model) . '_catalogues';
            $relation[$model] = function ($query) use ($language) {
                $query->with('languages', function ($query) use ($language) {
                    $query->where('language_id', $language);
                });
            };
            if ($widget->model === 'Product') {
                $relation['product_variants'] = function ($query) use ($language) {
                    $query->with(['languages' => function ($query) use ($language) {
                        $query->where('language_id', $language);
                    }]);
                };
            }
        }
        return [
            'condition' => [
                config('apps.general.publish')
            ],
            'flag' => true,
            'relation' => $relation,
            'orderBy' => ['id', 'DESC'],
            'param' => [
                'whereIn' => $widget->model_id,
                'whereInField' => 'id'
            ],
            'withCount' => $withCount,
        ];
    }

    private function childrenArgument($objectIds, $language)
    {
        return [
            'condition' => [
                config('apps.general.publish'),

            ],
            'flag' => true,
            'relation' => [
                'languages' => function ($query) use ($language) {
                    $query->where('language_id', $language);
                },
            ],
            'orderBy' => ['id', 'DESC'],
            'param' => [
                'whereIn' => $objectIds,
                'whereInField' => 'parent_id'
            ]
        ];
    }
}
