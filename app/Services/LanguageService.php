<?php

namespace App\Services;

use App\Repositories\LanguageRepository;
use App\Services\Interfaces\LanguageServiceInterface;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Class LanguageService
 * @package App\Services
 */
class LanguageService implements LanguageServiceInterface
{
    protected $languageRepository;

    public function __construct(LanguageRepository $languageRepository)
    {
        $this->languageRepository = $languageRepository;
    }

    public function paginate($request)
    {
        $condition['keyword'] = addslashes($request->input('keyword'));
        $condition['publish'] = $request->input('publish') != null ? $request->integer('publish') : -1;
        $perPage = $request->input('perpage') != null ? $request->integer('perpage') : 20;
        return $this->languageRepository->pagination($this->paginateSelect(), $condition, [], $perPage, ['path' => 'language/index'], []);
    }

    public function create($request)
    {
        DB::beginTransaction();
        try {
            $payload = $request->except('_token', 'send'); // lấy tất cả nhưng ngoại trừ... => trả về dạng mảng
            $payload['user_id'] = Auth::id(); //lấy id người dùng hiện tại đang đăng nhập

            $this->languageRepository->create($payload);

            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    public function update($id, $request)
    {
        DB::beginTransaction();
        try {
            $payload = $request->except('_token', 'send'); // lấy tất cả nhưng ngoại trừ... => trả về dạng mảng
            $this->languageRepository->update($id, $payload);
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
            $this->languageRepository->update($post['modelId'], $payload);
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
            $this->languageRepository->updateByWhereIn('id', $post['id'], $payload);
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
            $this->languageRepository->delete($id);
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    public function switch($id)
    {
        DB::beginTransaction();
        try {
            $this->languageRepository->update($id, ['current' => 1]);
            $where = [
                ['id', '!=', $id]
            ];
            $payload = ['current' => 0];
            $this->languageRepository->updateByWhere($where, $payload);

            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    public function saveTranslate($translateRequest)
    {
        DB::beginTransaction();
        try {
            $option = $translateRequest->input('option');
            $payload = [
                'name' => $translateRequest->input('translate_name'),
                'description' => $translateRequest->input('translate_description'),
                'content' => $translateRequest->input('translate_content'),
                'meta_title' => $translateRequest->input('translate_meta_title'),
                'meta_keyword' => $translateRequest->input('translate_meta_keyword'),
                'meta_description' => $translateRequest->input('translate_meta_description'),
                'canonical' => Str::slug($translateRequest->input('translate_canonical')),
                $this->convertModelToField($option['model']) => $option['id'],
                'language_id' => $option['languageId'],
            ];
            $repositoryNamespace = '\App\Repositories\\' . ucfirst($option['model']) . 'Repository';
            if (class_exists($repositoryNamespace)) {
                $repositoryInstance = app($repositoryNamespace);
            }
            $model = $repositoryInstance->findById($option['id']);
            $model->languages()->detach($option['languageId']);
            $repositoryInstance->createPivot($model, $payload, 'languages');
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    private function convertModelToField($model)
    {
        // PostCatalogue => post_catalogue_id
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $model)) . '_id';
    }

    private function paginateSelect()
    {
        return ['id', 'name', 'canonical', 'publish', 'description', 'image'];
    }
}
