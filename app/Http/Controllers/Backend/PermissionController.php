<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePermissionRequest;
use App\Http\Requests\UpdatePermissionRequest;
use App\Repositories\PermissionRepository;
use App\Services\PermissionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PermissionController extends Controller
{
    protected $permissionService;
    protected $permissionRepository;

    public function __construct(PermissionService $permissionService, PermissionRepository $permissionRepository)
    {
        $this->permissionService = $permissionService;
        $this->permissionRepository = $permissionRepository;
    }

    public function index(Request $request)
    {
        Gate::authorize('modules', 'permission.index');
        $permissions = $this->permissionService->paginate($request);

        $config = [
            'js' => [
                'backend/js/plugins/switchery/switchery.js',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js'
            ],
            'css' => [
                'backend/css/plugins/switchery/switchery.css',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css'
            ],
            'model' => 'Permission'
        ];
        $config['seo'] = __('permission');

        $template = 'backend.permission.index';
        return view('backend.dashboard.layout', compact('template', 'config', 'permissions'));
    }

    public function create()
    {
        Gate::authorize('modules', 'permission.create');
        $config = $this->configData();
        $config['seo'] = __('permission');
        $config['method'] = 'create';
        $template = 'backend.permission.store';
        return view('backend.dashboard.layout', compact('template', 'config'));
    }

    public function store(StorePermissionRequest $storePermissionRequest)
    {
        if ($this->permissionService->create($storePermissionRequest)) {
            flash()->success(__('toast.store_success'));
            return redirect()->route('permission.create');
        }
        flash()->error(__('toast.store_failed'));
        return redirect()->route('permission.index');
    }

    public function edit($id)
    {
        Gate::authorize('modules', 'permission.update');
        $config = $this->configData();
        $permission = $this->permissionRepository->findById($id);
        $config['seo'] = __('permission');
        $config['method'] = 'edit';
        $template = 'backend.permission.store';
        return view('backend.dashboard.layout', compact('template', 'config', 'permission'));
    }

    public function update($id, UpdatePermissionRequest $updatePermissionRequest)
    {
        if ($this->permissionService->update($id, $updatePermissionRequest)) {
            flash()->success(__('toast.update_success'));
            return redirect()->route('permission.index');
        }
        flash()->error(__('toast.update_failed'));
        return redirect()->route('permission.index');
    }

    public function delete($id)
    {
        Gate::authorize('modules', 'permission.destroy');
        $permission = $this->permissionRepository->findById($id);
        $config['seo'] = __('permission');
        $template = 'backend.permission.delete';
        return view('backend.dashboard.layout', compact('template', 'permission', 'config'));
    }

    public function destroy($id)
    {
        if ($this->permissionService->delete($id)) {
            flash()->success(__('toast.destroy_success'));
            return redirect()->route('permission.index');
        }
        flash()->error(__('toast.destroy_failed'));
        return redirect()->route('permission.index');
    }

    private function configData()
    {
        return [];
    }
}
