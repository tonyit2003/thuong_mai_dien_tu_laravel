<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
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
}
