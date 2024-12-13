<?php

namespace App\Services;

use App\Repositories\LanguageRepository;
use App\Repositories\SlideRepository;
use App\Services\Interfaces\SlideServiceInterface;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Class SlideService
 * @package App\Services
 */
class SlideService extends BaseService implements SlideServiceInterface
{
    protected $slideRepository;
    protected $languageRepository;

    public function __construct(SlideRepository $slideRepository, LanguageRepository $languageRepository)
    {
        $this->slideRepository = $slideRepository;
        $this->languageRepository = $languageRepository;
    }

    public function paginate($request)
    {
        $perPage = $request->input('perpage') != null ? $request->integer('perpage') : 20;
        $condition['keyword'] = addslashes($request->input('keyword'));
        $condition['publish'] = $request->input('publish') != null ? $request->integer('publish') : -1;
        $perPage = $request->input('perpage') != null ? $request->integer('perpage') : 20;
        return $this->slideRepository->pagination($this->paginateSelect(), $condition, [], $perPage, ['path' => 'slide/index']);
    }

    public function create($request, $languageId)
    {
        DB::beginTransaction();
        try {
            $payload = $request->only(['name', 'keyword', 'setting', 'short_code']);
            $payload['item'] = $this->getSlideItems($request, $languageId);
            $payload['user_id'] = Auth::id();
            $this->slideRepository->create($payload);
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
            $payload = $request->only(['name', 'keyword', 'setting', 'short_code']);
            $slide = $this->slideRepository->findById($id);
            $slideItem = $slide->item;
            // xóa một phần tử của một mảng.
            unset($slideItem[$languageId]);
            $payload['item'] = $this->handleSlideItem($request->input('slide'), $languageId) + $slideItem;
            $this->slideRepository->update($id, $payload);
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
            $this->slideRepository->delete($id);
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    public function convertSlideArray($slides = [])
    {
        $temp = [];
        $fields = ['image', 'description', 'window', 'canonical', 'name', 'alt'];
        foreach ($slides as $slide) {
            foreach ($fields as $field) {
                $temp[$field][] = $slide[$field];
            }
        }
        return $temp;
    }

    public function getSlides($array = [], $languages)
    {
        $slides = $this->slideRepository->findByCondition([
            config('apps.general.publish'),
        ], true, [], ['id', 'DESC'], ['whereIn' => $array, 'whereInField' => 'keyword']);
        $temp = [];
        foreach ($slides as $key => $val) {
            $temp[$val->keyword]['item'] = $val->item[$languages];
            $temp[$val->keyword]['setting'] = $val->setting;
        }
        return $temp;
    }

    private function getSlideItems($request, $languageId)
    {
        $items = $this->handleSlideItem($request->input('slide'), $languageId);

        $languages = $this->languageRepository->findByCondition([
            ['id', '!=', $languageId]
        ], true);

        if (isset($languages) && count($languages)) {
            foreach ($languages as $language) {
                $items += $this->handleSlideItem($request->input('slide'), $language->id, $language->canonical != 'vn' ? $language->canonical : "vi");
            }
        }

        return $items;
    }

    private function handleSlideItem($slide, $languageId, $canonicalLanguage = '')
    {
        $temp = [];
        foreach ($slide['image'] as $key => $val) {
            $temp[$languageId][] = [
                'image' => $val,
                'description' => empty($canonicalLanguage) ? $slide['description'][$key] : translateContent($slide['description'][$key], $canonicalLanguage),
                'canonical' => $slide['canonical'][$key],
                'window' => isset($slide['window'][$key]) ? $slide['window'][$key] : '',
                'name' => empty($canonicalLanguage) ? $slide['name'][$key] : translateContent($slide['name'][$key], $canonicalLanguage),
                'alt' => empty($canonicalLanguage) ? $slide['alt'][$key] : translateContent($slide['alt'][$key], $canonicalLanguage),
            ];
        }
        return $temp;
    }

    private function paginateSelect()
    {
        return ['id', 'name', 'keyword', 'item', 'publish'];
    }
}
