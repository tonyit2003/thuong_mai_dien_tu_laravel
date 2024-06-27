<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Repositories\ProvinceRepository;
use App\Repositories\UserRepository;
use App\Services\UserService;

class UserController extends Controller
{
    protected $userService;
    protected $provinceRepository;
    protected $userRepository;

    public function __construct(UserService $userService, ProvinceRepository $provinceRepository, UserRepository $userRepository)
    {
        $this->userService = $userService;
        $this->provinceRepository = $provinceRepository;
        $this->userRepository = $userRepository;
    }

    public function index()
    {
        $users = $this->userService->paginate();

        $config = [
            'js' => [
                'backend/js/plugins/switchery/switchery.js'
            ],
            'css' => [
                'backend/css/plugins/switchery/switchery.css'
            ]
        ];
        $config['seo'] = config('apps.user');

        $template = 'backend.user.index';
        return view('backend.dashboard.layout', compact('template', 'config', 'users'));
    }

    public function create()
    {
        $provinces = $this->provinceRepository->all();
        $config = [
            'css' => [
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css'
            ],
            'js' => [
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js',
                'backend/library/location.js',
            ]
        ];
        $config['seo'] = config('apps.user');
        $config['method'] = 'create';
        $template = 'backend.user.store';
        return view('backend.dashboard.layout', compact('template', 'config', 'provinces'));
    }

    public function store(StoreUserRequest $storeUserRequest)
    {
        if ($this->userService->create($storeUserRequest)) {
            flash()->success('Thêm mới bản ghi thành công');
            return redirect()->route('user.index');
        }
        flash()->error('Thêm mới bản ghi không thành công');
        return redirect()->route('user.index');
    }

    public function edit($id)
    {
        $user = $this->userRepository->findById($id);
        $provinces = $this->provinceRepository->all();
        $config = [
            'css' => [
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css'
            ],
            'js' => [
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js',
                'backend/library/location.js',
            ]
        ];
        $config['seo'] = config('apps.user');
        $config['method'] = 'edit';
        $template = 'backend.user.store';
        return view('backend.dashboard.layout', compact('template', 'config', 'provinces', 'user'));
    }

    public function update($id, UpdateUserRequest $updateUserRequest)
    {
        if ($this->userService->update($id, $updateUserRequest)) {
            flash()->success('Cập nhật bản ghi thành công');
            return redirect()->route('user.index');
        }
        flash()->error('Cập nhật bản ghi không thành công');
        return redirect()->route('user.index');
    }

    public function delete($id)
    {
        $user = $this->userRepository->findById($id);
        $config['seo'] = config('apps.user');
        $template = 'backend.user.delete';
        return view('backend.dashboard.layout', compact('template', 'user', 'config'));
    }

    public function destroy($id)
    {
        if ($this->userService->delete($id)) {
            flash()->success('Xóa bản ghi thành công');
            return redirect()->route('user.index');
        }
        flash()->error('Xóa bản ghi không thành công');
        return redirect()->route('user.index');
    }
}
