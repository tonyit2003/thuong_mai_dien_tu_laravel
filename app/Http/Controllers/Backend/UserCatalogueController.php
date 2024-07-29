<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserCatalogueRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Repositories\PermissionRepository;
use App\Repositories\UserCatalogueRepository;
use App\Services\UserCatalogueService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class UserCatalogueController extends Controller
{
    protected $userCatalogueService;
    protected $userCatalogueRepository;
    protected $permissionRepository;

    public function __construct(UserCatalogueService $userCatalogueService, UserCatalogueRepository $userCatalogueRepository, PermissionRepository $permissionRepository)
    {
        $this->userCatalogueService = $userCatalogueService;
        $this->userCatalogueRepository = $userCatalogueRepository;
        $this->permissionRepository = $permissionRepository;
    }

    public function index(Request $request)
    {
        Gate::authorize('modules', 'user.catalogue.index');
        $userCatalogues = $this->userCatalogueService->paginate($request);

        $config = [
            'js' => [
                'backend/js/plugins/switchery/switchery.js',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js'
            ],
            'css' => [
                'backend/css/plugins/switchery/switchery.css',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css'
            ],
            'model' => 'UserCatalogue'
        ];
        $config['seo'] = __('userCatalogue');

        $template = 'backend.user.catalogue.index';
        return view('backend.dashboard.layout', compact('template', 'config', 'userCatalogues'));
    }

    public function create()
    {
        Gate::authorize('modules', 'user.catalogue.create');
        $config['seo'] = __('userCatalogue');
        $config['method'] = 'create';
        $template = 'backend.user.catalogue.store';
        return view('backend.dashboard.layout', compact('template', 'config'));
    }

    public function store(StoreUserCatalogueRequest $storeUserCatalogueRequest)
    {
        if ($this->userCatalogueService->create($storeUserCatalogueRequest)) {
            flash()->success(__('toast.store_success'));
            return redirect()->route('user.catalogue.index');
        }
        flash()->error(__('toast.store_failed'));
        return redirect()->route('user.catalogue.index');
    }

    public function edit($id)
    {
        Gate::authorize('modules', 'user.catalogue.update');
        $userCatalogue = $this->userCatalogueRepository->findById($id);
        $config['seo'] = __('userCatalogue');
        $config['method'] = 'edit';
        $template = 'backend.user.catalogue.store';
        return view('backend.dashboard.layout', compact('template', 'config', 'userCatalogue'));
    }

    public function update($id, StoreUserCatalogueRequest $storeUserCatalogueRequest)
    {
        if ($this->userCatalogueService->update($id, $storeUserCatalogueRequest)) {
            flash()->success(__('toast.update_success'));
            return redirect()->route('user.catalogue.index');
        }
        flash()->error(__('toast.update_failed'));
        return redirect()->route('user.catalogue.index');
    }

    public function delete($id)
    {
        Gate::authorize('modules', 'user.catalogue.destroy');
        $userCatalogue = $this->userCatalogueRepository->findById($id);
        $config['seo'] = __('userCatalogue');
        $template = 'backend.user.catalogue.delete';
        return view('backend.dashboard.layout', compact('template', 'userCatalogue', 'config'));
    }

    public function destroy($id)
    {
        if ($this->userCatalogueService->delete($id)) {
            flash()->success(__('toast.destroy_success'));
            return redirect()->route('user.catalogue.index');
        }
        flash()->error(__('toast.destroy_failed'));
        return redirect()->route('user.catalogue.index');
    }

    public function permission()
    {
        Gate::authorize('modules', 'user.catalogue.permission');
        // lấy thông tin tất cả các nhóm thành viêm && mỗi nhóm có thông tin các quyền
        $userCatalogues = $this->userCatalogueRepository->all(['permissions']);
        $permissions = $this->permissionRepository->all();
        $template = 'backend.user.catalogue.permission';
        $config['seo'] = __('userCatalogue');
        return view('backend.dashboard.layout', compact('template', 'userCatalogues', 'permissions', 'config'));
    }

    public function updatePermission(Request $request)
    {
        if ($this->userCatalogueService->setPermission($request)) {
            flash()->success(__('toast.permission_update_success'));
            return redirect()->route('user.catalogue.index');
        }
        flash()->error(__('toast.permission_update_failed'));
        return redirect()->route('user.catalogue.index');
    }
}
