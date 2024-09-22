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
    protected $productCatalogueRepository;

    public function __construct(WidgetRepository $widgetRepository, PromotionRepository $promotionRepository, ProductService $productService, ProductCatalogueRepository $productCatalogueRepository)
    {
        $this->widgetRepository = $widgetRepository;
        $this->promotionRepository = $promotionRepository;
        $this->productService = $productService;
        $this->productCatalogueRepository = $productCatalogueRepository;
    }

    public function paginate($request)
    {
        $condition['keyword'] = addslashes($request->input('keyword'));
        $condition['publish'] = $request->input('publish') != null ? $request->integer('publish') : -1;
        $perPage = $request->input('perpage') != null ? $request->integer('perpage') : 20;
        return $this->widgetRepository->pagination($this->paginateSelect(), $condition, [], $perPage, ['path' => 'widget/index']);
    }

    public function findWidgetByKeyword($keyword, $language, $param = [])
    {
        $widget = $this->widgetRepository->findByCondition(
            [
                ['keyword', '=', $keyword],
                config('apps.general.publish')
            ]
        );
        if (isset($widget)) {
            /**
             * @var Widget $widget
             */
            $class = loadClass($widget->model);
            $object = $class->findByCondition(...$this->widgetArgument($widget, $language, $param));
            $model = lcfirst(str_replace('Catalogue', '', $widget->model));
            if ($model === 'product' && isset($param['object']) && $param['object'] == true) {
                if (count($object)) {
                    foreach ($object as $keyObject => $valObject) {
                        if ($valObject->id == 2) continue;
                        // lấy một tập hợp các giá trị của một cột duy nhất từ cơ sở dữ liệu hoặc từ một collection
                        $productIds = $valObject->products->pluck('id')->toArray();
                        $valObject->products = $this->productService->combineProductAndPromotion($productIds, $valObject->products);
                    }
                }
            }
            if (isset($param['children']) && $param['children'] == true) {
                foreach ($object as $keyObject => $valObject) {
                    $valObject->children = $this->productCatalogueRepository->findByCondition([
                        ['lft', '>', $valObject->lft],
                        ['rgt', '<', $valObject->rgt],
                        config('apps.general.publish')
                    ], true);
                }
            }
            return $object;
        }
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
        if (strpos($widget->model, 'Catalogue') && isset($param['object'])) {
            $model = lcfirst(str_replace('Catalogue', '', $widget->model)) . 's';
            $relation[$model] = function ($query) use ($param, $language) {
                $query->with('languages', function ($query) use ($language) {
                    $query->where('language_id', $language);
                });
                $query->take($param['limit'] ?? 8);
                $query->where('publish', 1);
                $query->orderBy('order', 'DESC');
            };
            if (isset($param['countObject'])) {
                $withCount[] = $model;
            }
        }
        return [
            'condition' => [
                config('apps.general.publish')
            ],
            'flag' => true,
            'relation' => $relation,
            'param' => [
                'whereIn' => $widget->model_id,
                'whereInField' => 'id'
            ],
            'withCount' => $withCount,
        ];
    }
}
