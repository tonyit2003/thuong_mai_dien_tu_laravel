<?php

namespace App\Services;

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

    public function __construct(WidgetRepository $widgetRepository)
    {
        $this->widgetRepository = $widgetRepository;
    }

    public function paginate($request)
    {
        $condition['keyword'] = addslashes($request->input('keyword'));
        $condition['publish'] = $request->input('publish') != null ? $request->integer('publish') : -1;
        $perPage = $request->input('perpage') != null ? $request->integer('perpage') : 20;
        return $this->widgetRepository->pagination($this->paginateSelect(), $condition, [], $perPage, ['path' => 'widget/index']);
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
}
