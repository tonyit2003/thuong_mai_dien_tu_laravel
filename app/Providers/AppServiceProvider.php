<?php

namespace App\Providers;

use App\Http\Controllers\Frontend\AuthClientController;
use App\Http\ViewComposers\CartComposer;
use App\Http\ViewComposers\LanguageComposer;
use App\Http\ViewComposers\MenuComposer;
use App\Http\ViewComposers\SystemComposer;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(255);
        // định nghĩa 1 Gate để kiểm tra quyền (dùng trong Controller)
        // $user: laravel tự động truyền vào
        Gate::define('modules', function ($user, $permissionName) {
            if ($user->publish == 0 || $user->publish == -1) {
                return false;
            }
            if ($user->hasPermission($permissionName)) {
                return true;
            }
            return false;
        });

        // tạo ra các quy tắc xác thực tùy chỉnh
        Validator::extend('custom_date_format', function ($attribute, $value, $parameters, $validator) {
            // kiểm tra định dạng của ngày và giờ:
            return DateTime::createFromFormat('d/m/Y H:i', $value) !== false;
        });

        /*
            $attribute: Là tên của thuộc tính (field) đang được xác thực.
            $value: Giá trị của thuộc tính đó (dữ liệu người dùng nhập vào).
            $parameters: Mảng chứa các tham số bổ sung (sau dấu :).
            $validator->getData(): Trả về tất cả dữ liệu đầu vào, từ đó ta có thể lấy giá trị của trường mà chúng ta cần so sánh.
        */

        Validator::extend('custom_after', function ($attribute, $value, $parameters, $validator) {
            // chuyển đổi hai chuỗi thời gian thành đối tượng ngày giờ và so sánh
            $startDate = Carbon::createFromFormat('d/m/Y H:i', $validator->getData()[$parameters[0]]);
            $endDate = Carbon::createFromFormat('d/m/Y H:i', $value);
            return $endDate->greaterThan($startDate) !== false;
        });

        // đăng ký composer => chia sẽ dữ liệu cho view cụ thể từ composer đăng ký
        // view()->composer('frontend.homepage.layout', SystemComposer::class);
        view()->composer('backend.dashboard.layout', SystemComposer::class);
        view()->composer('frontend.homepage.layout', MenuComposer::class);
        view()->composer('frontend.homepage.layout', LanguageComposer::class);
        view()->composer('frontend.homepage.layout', CartComposer::class);
    }
}
