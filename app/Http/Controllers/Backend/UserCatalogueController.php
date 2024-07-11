<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserCatalogueRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Repositories\UserCatalogueRepository;
use App\Services\UserCatalogueService;
use Illuminate\Http\Request;

class UserCatalogueController extends Controller
{
    protected $userCatalogueService;
    protected $userCatalogueRepository;

    public function __construct(UserCatalogueService $userCatalogueService, UserCatalogueRepository $userCatalogueRepository)
    {
        $this->userCatalogueService = $userCatalogueService;
        $this->userCatalogueRepository = $userCatalogueRepository;
    }

    public function index(Request $request)
    {
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
        $config['seo'] = config('apps.userCatalogue');

        $template = 'backend.user.catalogue.index';
        return view('backend.dashboard.layout', compact('template', 'config', 'userCatalogues'));
    }

    public function create()
    {
        $config['seo'] = config('apps.userCatalogue');
        $config['method'] = 'create';
        $template = 'backend.user.catalogue.store';
        return view('backend.dashboard.layout', compact('template', 'config'));
    }

    public function store(StoreUserCatalogueRequest $storeUserCatalogueRequest)
    {
        if ($this->userCatalogueService->create($storeUserCatalogueRequest)) {
            flash()->success('Thêm mới bản ghi thành công');
            return redirect()->route('user.catalogue.index');
        }
        flash()->error('Thêm mới bản ghi không thành công');
        return redirect()->route('user.catalogue.index');
    }

    public function edit($id)
    {
        $userCatalogue = $this->userCatalogueRepository->findById($id);
        $config['seo'] = config('apps.userCatalogue');
        $config['method'] = 'edit';
        $template = 'backend.user.catalogue.store';
        return view('backend.dashboard.layout', compact('template', 'config', 'userCatalogue'));
    }

    public function update($id, StoreUserCatalogueRequest $storeUserCatalogueRequest)
    {
        if ($this->userCatalogueService->update($id, $storeUserCatalogueRequest)) {
            flash()->success('Cập nhật bản ghi thành công');
            return redirect()->route('user.catalogue.index');
        }
        flash()->error('Cập nhật bản ghi không thành công');
        return redirect()->route('user.catalogue.index');
    }

    public function delete($id)
    {
        $userCatalogue = $this->userCatalogueRepository->findById($id);
        $config['seo'] = config('apps.userCatalogue');
        $template = 'backend.user.catalogue.delete';
        return view('backend.dashboard.layout', compact('template', 'userCatalogue', 'config'));
    }

    public function destroy($id)
    {
        if ($this->userCatalogueService->delete($id)) {
            flash()->success('Xóa bản ghi thành công');
            return redirect()->route('user.catalogue.index');
        }
        flash()->error('Xóa bản ghi không thành công');
        return redirect()->route('user.catalogue.index');
    }
}
