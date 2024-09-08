<?php

namespace App\Services;

use App\Repositories\PromotionRepository;
use App\Services\Interfaces\PromotionServiceInterface;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Class PromotionService
 * @package App\Services
 */
class PromotionService extends BaseService implements PromotionServiceInterface
{
    protected $promotionRepository;

    public function __construct(PromotionRepository $promotionRepository)
    {
        $this->promotionRepository = $promotionRepository;
    }

    public function paginate($request)
    {
        $condition['keyword'] = addslashes($request->input('keyword'));
        $condition['publish'] = $request->input('publish') != null ? $request->integer('publish') : -1;
        $perPage = $request->input('perpage') != null ? $request->integer('perpage') : 20;
        return $this->promotionRepository->pagination($this->paginateSelect(), $condition, [], $perPage, ['path' => 'promotion/index']);
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
            $this->promotionRepository->create($payload);
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
            $promotion = $this->promotionRepository->findById($id);
            $promotionDescription = $promotion->description;
            unset($promotionDescription[$languageId]);
            $payload['description'] = $promotionDescription + $description;
            $this->promotionRepository->update($id, $payload);
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
            $promotionId = $request->input('promotionId');
            $translateId = $request->input('translateId');
            $description = [
                $translateId => $request->input('translate_description')
            ];
            $promotion = $this->promotionRepository->findById($promotionId);
            $promotionDescription = $promotion->description;
            unset($promotionDescription[$translateId]);
            $payload['description'] = $promotionDescription + $description;
            $this->promotionRepository->update($promotionId, $payload);
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
            $this->promotionRepository->delete($id);
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
