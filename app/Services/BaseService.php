<?php

namespace App\Services;

use App\Repositories\RouterRepository;
use App\Services\Interfaces\BaseServiceInterface;
use Illuminate\Support\Str;

/**
 * Class BaseService
 * @package App\Services
 */
class BaseService implements BaseServiceInterface
{
    protected $model;
    protected $routerRepository;

    public function __construct(RouterRepository $routerRepository)
    {
        $this->routerRepository = $routerRepository;
    }

    public function currentLanguage()
    {
        return 1;
    }

    public function formatAlbum($album)
    {
        // $payload['album']: mảng các đường dẫn từ input name="album[]"
        return (isset($album) && is_array($album)) ? json_encode($album) : "";
    }

    public function nestedset($nestedset)
    {
        // tính giá trị left, right bằng Nestedsetbie (có sẵn)
        $nestedset->Get('level ASC, order ASC');
        $nestedset->Recursive(0, $nestedset->Set());
        $nestedset->Action();
    }

    public function createRouter($model, $request, $controllerName, $languageId)
    {
        $payload = $this->formatRouterPayload($model, $request, $controllerName, $languageId);
        return $this->routerRepository->create($payload);
    }

    public function updateRouter($model, $request, $controllerName, $languageId)
    {
        $payload = $this->formatRouterPayload($model, $request, $controllerName, $languageId);
        $condition = [
            ['module_id', '=', $model->id],
            ['controllers', '=', 'App\Http\Controllers\Frontend\\' . $controllerName . '']
        ];
        $router = $this->routerRepository->findByCondition($condition);
        return $this->routerRepository->update($router->id, $payload);
    }

    public function formatRouterPayload($model, $request, $controllerName, $languageId)
    {
        return [
            'canonical' => Str::slug($request->input('canonical')), //chuyển đổi một chuỗi văn bản thành dạng mà có thể sử dụng được trong URL
            'module_id' => $model->id,
            'controllers' => 'App\Http\Controllers\Frontend\\' . $controllerName . '',
            'language_id' => $languageId,
        ];
    }

    public function formatJson($request, $inputName)
    {
        return $request->input($inputName) && !empty($request->input($inputName)) ? json_encode($request->input($inputName)) : '';
    }
}
