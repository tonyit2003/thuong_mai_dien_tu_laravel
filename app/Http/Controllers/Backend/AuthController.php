<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequest;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $system = $this->system;
        return view('backend.auth.login', compact('system'));
    }

    public function login(AuthRequest $authRequest)
    {
        $credentials = [
            'email' => $authRequest->input('email'),
            'password' => $authRequest->input('password')
        ];

        // Kiểm tra đăng nhập thành công
        if (Auth::guard('web')->attempt($credentials)) {
            // Lấy thông tin người dùng
            $user = Auth::user();

            // Xác định trang mà người dùng có quyền truy cập
            if (Gate::forUser($user)->allows('modules', 'dashboard.index')) { // Quản lý doanh nghiệp
                flash()->success(__('toast.login_success'));
                return redirect()->route('dashboard.index');
            } elseif (Gate::forUser($user)->allows('modules', 'post.index')) { //Nhân viên ceo
                flash()->success(__('toast.login_success'));
                return redirect()->route('post.index');
            } elseif (Gate::forUser($user)->allows('modules', 'dashboard.receipt.index')) { //Nhân viên kho
                flash()->success(__('toast.login_success'));
                return redirect()->route('statisticalReceipt.index');
            } elseif (Gate::forUser($user)->allows('modules', 'order.index')) { // Nhân viên bán hàng
                flash()->success(__('toast.login_success'));
                return redirect()->route('order.index');
            } elseif (Gate::forUser($user)->allows('modules', 'promotion.index')) { // Nhân viên Marketing
                flash()->success(__('toast.login_success'));
                return redirect()->route('promotion.index');
            } elseif (Gate::forUser($user)->allows('modules', 'warranty.index')) { // Nhân viên bảo hành
                flash()->success(__('toast.login_success'));
                return redirect()->route('warranty.index');
            } elseif (Gate::forUser($user)->allows('modules', 'review.index')) { // Nhân viên chăm sóc khách hàng
                flash()->success(__('toast.login_success'));
                return redirect()->route('review.index');
            }

            // Nếu không có quyền nào cụ thể, trả về trang mặc định hoặc thông báo
            flash()->error(__('toast.no_permission'));
            return redirect()->route('auth.admin');
        }

        flash()->error(__('toast.login_failed'));
        return redirect()->route('auth.admin');
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('auth.admin');
    }
}
