<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostCatalogueRequest;
use App\Http\Requests\UpdatePostCatalogueRequest;
use App\Repositories\PostCatalogueRepository;
use App\Services\PostCatalogueService;
use Illuminate\Http\Request;

class PostCatalogueController extends Controller
{
    protected $postCatalogueService;
    protected $postCatalogueRepository;

    public function __construct(PostCatalogueService $postCatalogueService, PostCatalogueRepository $postCatalogueRepository)
    {
        $this->postCatalogueService = $postCatalogueService;
        $this->postCatalogueRepository = $postCatalogueRepository;
    }

    public function index(Request $request)
    {
        $postCatalogues = $this->postCatalogueService->paginate($request);

        $config = [
            'js' => [
                'backend/js/plugins/switchery/switchery.js',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js'
            ],
            'css' => [
                'backend/css/plugins/switchery/switchery.css',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css'
            ]
        ];
        $config['seo'] = config('apps.postCatalogue');

        $template = 'backend.post.catalogue.index';
        return view('backend.dashboard.layout', compact('template', 'config', 'postCatalogues'));
    }

    public function create()
    {
        $config = $this->configData();
        $config['seo'] = config('apps.postCatalogue');
        $config['method'] = 'create';
        $template = 'backend.post.catalogue.store';
        return view('backend.dashboard.layout', compact('template', 'config'));
    }

    public function store(StorePostCatalogueRequest $storePostCatalogueRequest)
    {
        if ($this->postCatalogueService->create($storePostCatalogueRequest)) {
            flash()->success('Thêm mới bản ghi thành công');
            return redirect()->route('post.catalogue.index');
        }
        flash()->error('Thêm mới bản ghi không thành công');
        return redirect()->route('post.catalogue.index');
    }

    public function edit($id)
    {
        $config = $this->configData();
        $postCatalogue = $this->postCatalogueRepository->findById($id);
        $config['seo'] = config('apps.postCatalogue');
        $config['method'] = 'edit';
        $template = 'backend.post.catalogue.store';
        return view('backend.dashboard.layout', compact('template', 'config', 'postCatalogue'));
    }

    public function update($id, UpdatePostCatalogueRequest $updatePostCatalogueRequest)
    {
        if ($this->postCatalogueService->update($id, $updatePostCatalogueRequest)) {
            flash()->success('Cập nhật bản ghi thành công');
            return redirect()->route('post.catalogue.index');
        }
        flash()->error('Cập nhật bản ghi không thành công');
        return redirect()->route('post.catalogue.index');
    }

    public function delete($id)
    {
        $postCatalogue = $this->postCatalogueRepository->findById($id);
        $config['seo'] = config('apps.postCatalogue');
        $template = 'backend.post.catalogue.delete';
        return view('backend.dashboard.layout', compact('template', 'postCatalogue', 'config'));
    }

    public function destroy($id)
    {
        if ($this->postCatalogueService->delete($id)) {
            flash()->success('Xóa bản ghi thành công');
            return redirect()->route('post.catalogue.index');
        }
        flash()->error('Xóa bản ghi không thành công');
        return redirect()->route('post.catalogue.index');
    }

    private function configData()
    {
        return [
            'js' => [
                'backend/plugins/ckeditor/ckeditor.js',
                'backend/plugins/ckfinder_2/ckfinder.js',
                'backend/library/finder.js',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js'
            ],
            'css' => [
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css'
            ]
        ];
    }
}
