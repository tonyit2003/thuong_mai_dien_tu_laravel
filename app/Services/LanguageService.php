<?php

namespace App\Services;

use App\Repositories\CustomerRepository;
use App\Repositories\LanguageRepository;
use App\Repositories\RouterRepository;
use App\Services\Interfaces\LanguageServiceInterface;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Class LanguageService
 * @package App\Services
 */
class LanguageService extends BaseService implements LanguageServiceInterface
{
    protected $languageRepository;
    protected $routerRepository;
    protected $customerRepository;

    public function __construct(LanguageRepository $languageRepository, RouterRepository $routerRepository, CustomerRepository $customerRepository)
    {
        $this->languageRepository = $languageRepository;
        $this->routerRepository = $routerRepository;
        $this->customerRepository = $customerRepository;
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

    public function switchBackend($id)
    {
        DB::beginTransaction();
        try {
            $language = $this->languageRepository->findById($id);
            if ($language != null) {
                $payload['language'] = $language->canonical;
                $customerId = Auth::guard('customers')->id();
                if (isset($customerId)) {
                    $this->customerRepository->update($customerId, $payload);
                }
            }
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
            $controllerName = $option['model'] . 'Controller';
            $repositoryNamespace = '\App\Repositories\\' . ucfirst($option['model']) . 'Repository';
            if (class_exists($repositoryNamespace)) {
                $repositoryInstance = app($repositoryNamespace);
            }
            $model = $repositoryInstance->findById($option['id']);
            $model->languages()->detach($option['languageId']);
            $repositoryInstance->createPivot($model, $payload, 'languages');

            $this->routerRepository->forceDeleteByCondition([
                ['module_id', '=', $option['id']],
                ['controllers', '=', 'App\Http\Controllers\Frontend\\' . $controllerName . ''],
                ['language_id', '=', $option['languageId']]
            ]);

            $router = [
                'canonical' => Str::slug($translateRequest->input('translate_canonical')),
                'module_id' => $model->id,
                'controllers' => 'App\Http\Controllers\Frontend\\' . $controllerName . '',
                'language_id' => $option['languageId'],
            ];
            $this->routerRepository->create($router);

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
