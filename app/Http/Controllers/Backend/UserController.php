<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Repositories\ProvinceRepository;
use App\Repositories\UserRepository;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

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

    public function index(Request $request)
    {
        Gate::authorize('modules', 'user.index');
        $users = $this->userService->paginate($request);

        $config = [
            'js' => [
                'backend/js/plugins/switchery/switchery.js',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js'
            ],
            'css' => [
                'backend/css/plugins/switchery/switchery.css',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css'
            ],
            'model' => 'User'
        ];
        $config['seo'] = __('user');

        $template = 'backend.user.user.index';
        return view('backend.dashboard.layout', compact('template', 'config', 'users'));
    }

    public function create()
    {
        Gate::authorize('modules', 'user.create');
        $provinces = $this->provinceRepository->all();
        $config = $this->configData();
        $config['seo'] = __('user');
        $config['method'] = 'create';
        $template = 'backend.user.user.store';
        return view('backend.dashboard.layout', compact('template', 'config', 'provinces'));
    }

    public function store(StoreUserRequest $storeUserRequest)
    {
        if ($this->userService->create($storeUserRequest)) {
            flash()->success(__('toast.store_success'));
            return redirect()->route('user.index');
        }
        flash()->error(__('toast.store_failed'));
        return redirect()->route('user.index');
    }

    public function edit($id)
    {
        Gate::authorize('modules', 'user.update');
        $user = $this->userRepository->findById($id);
        $provinces = $this->provinceRepository->all();
        $config = $this->configData();
        $config['seo'] = __('user');
        $config['method'] = 'edit';
        $template = 'backend.user.user.store';
        return view('backend.dashboard.layout', compact('template', 'config', 'provinces', 'user'));
    }

    public function update($id, UpdateUserRequest $updateUserRequest)
    {
        if ($this->userService->update($id, $updateUserRequest)) {
            flash()->success(__('toast.update_success'));
            return redirect()->route('user.index');
        }
        flash()->error(__('toast.update_failed'));
        return redirect()->route('user.index');
    }

    public function delete($id)
    {
        Gate::authorize('modules', 'user.destroy');
        $user = $this->userRepository->findById($id);
        $config['seo'] = __('user');
        $template = 'backend.user.user.delete';
        return view('backend.dashboard.layout', compact('template', 'user', 'config'));
    }

    public function destroy($id)
    {
        if ($this->userService->delete($id)) {
            flash()->success(__('toast.destroy_success'));
            return redirect()->route('user.index');
        }
        flash()->error(__('toast.destroy_failed'));
        return redirect()->route('user.index');
    }

    private function configData()
    {
        return [
            'css' => [
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css'
            ],
            'js' => [
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js',
                'backend/library/location.js',
                'backend/plugins/ckfinder_2/ckfinder.js',
                'backend/library/finder.js'
            ]
        ];
    }
}
