<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $locale = App::getLocale();
            $language = Language::where('canonical', $locale)->first();
            $this->language = $language->id;
            return $next($request);
        });
    }

    public function changeStatus(Request $request)
    {
        $post = $request->input(); // trả về dạng mảng
        // tạo 1 đường dẫn đến 1 lớp Service. vd: App\Services\UserService
        $serviceNamespace = '\App\Services\\' . ucfirst($post['model']) . 'Service';
        if (class_exists($serviceNamespace)) {
            // tạo 1 biến có kiểu là $serviceNamespace
            $serviceInstance = app($serviceNamespace);
        }
        $flag = $serviceInstance->updateStatus($post);
        return response()->json(['flag' => $flag]);
    }

    public function changeStatusAll(Request $request)
    {
        $post = $request->input(); // trả về dạng mảng
        $serviceNamespace = '\App\Services\\' . ucfirst($post['model']) . 'Service';
        if (class_exists($serviceNamespace)) {
            // tạo 1 biến có kiểu là $serviceNamespace
            $serviceInstance = app($serviceNamespace);
        }
        $flag = $serviceInstance->updateStatusAll($post);

        return response()->json(['flag' => $flag]);
    }

    public function getMenu(Request $request)
    {
        $model = $request->input('model');
        $keyword = $request->input('keyword') ?? null;
        $repositoryNamespace = '\App\Repositories\\' . ucfirst($model) . 'Repository';
        if (class_exists($repositoryNamespace)) {
            $repositoryInstance = app($repositoryNamespace);
        }
        $arguments = $this->paginationArgument($model, $keyword);
        // array_values() => trả về mảng các giá trị của mảng (chuyển đổi các key thành dạng 0, 1, 2,...)
        // ... =>  giải nén mảng được trả về từ array_values($arguments) và truyền các phần tử của mảng đó dưới dạng các tham số riêng lẻ
        $object = $repositoryInstance->pagination(...array_values($arguments));
        return response()->json($object);
    }

    private function paginationArgument($model, $keyword = '')
    {
        // các từ trong chuỗi được nối với nhau bằng dấu gạch dưới (_), và tất cả các chữ cái đều được chuyển thành chữ thường.
        $model = Str::snake($model);
        $join = [
            [$model . '_language', $model . '_language.' . $model . '_id', '=', $model . 's.id']
        ];
        if (strpos($model, '_catalogue') == false) {
            $join[] = [$model . '_catalogue_' . $model, $model . 's.id', '=', $model . '_catalogue_' . $model . '.' . $model . '_id'];
        }
        $condition = [
            'where' => [
                [$model . '_language.language_id', '=', $this->language]
            ],
        ];
        if (!is_null($keyword)) {
            // addslashes() => thêm ký tự gạch chéo ngược (\) trước các ký tự đặc biệt trong một chuỗi.
            $condition['keyword'] = addslashes($keyword);
        }
        return [
            'select' => ['id', 'name', 'canonical'],
            'condition' => $condition,
            'join' => $join,
            'perpage' => 20,
            'paginationConfig' => [
                'path' => $model . '/index',
                'groupBy' => ['id', 'name', 'canonical']
            ],
            'relation' => [],
            'orderBy' => [$model . 's.id', 'DESC'],
            []
        ];
    }
}
