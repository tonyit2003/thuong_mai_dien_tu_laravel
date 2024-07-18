<?php

namespace App\Http\Controllers\Backend;

use App\Classes\Nestedsetbie;
use App\Http\Controllers\Controller;
use App\Http\Requests\DeletePostCatalogueRequest;
use App\Http\Requests\StorePostCatalogueRequest;
use App\Http\Requests\UpdatePostCatalogueRequest;
use App\Models\Language;
use App\Repositories\PostCatalogueRepository;
use App\Services\PostCatalogueService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Gate;

class PostCatalogueController extends Controller
{
    protected $postCatalogueService;
    protected $postCatalogueRepository;
    protected $nestedset;
    protected $language;

    public function __construct(PostCatalogueService $postCatalogueService, PostCatalogueRepository $postCatalogueRepository)
    {
        $this->middleware(function ($request, $next) {
            $locale = App::getLocale();
            $language = Language::where('canonical', $locale)->first();
            $this->language = $language->id;
            $this->initialize();
            return $next($request);
        });
        $this->postCatalogueService = $postCatalogueService;
        $this->postCatalogueRepository = $postCatalogueRepository;
    }

    public function index(Request $request)
    {
        // kiểm tra quyền của người dùng hiện tại
        // param 1: tên của Gate trong Gate::define (AppServiceProvider)
        // param 2: Tên của quyền hoặc hành động cụ thể cần kiểm tra
        Gate::authorize('modules', 'post.catalogue.index');

        $postCatalogues = $this->postCatalogueService->paginate($request, $this->language);

        $config = [
            'js' => [
                'backend/js/plugins/switchery/switchery.js',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js'
            ],
            'css' => [
                'backend/css/plugins/switchery/switchery.css',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css'
            ],
            'model' => 'PostCatalogue'
        ];
        $config['seo'] = __('postCatalogue');
        $template = 'backend.post.catalogue.index';
        return view('backend.dashboard.layout', compact('template', 'config', 'postCatalogues'));
    }

    public function create()
    {
        Gate::authorize('modules', 'post.catalogue.create');
        $config = $this->configData();
        $config['seo'] = __('postCatalogue');
        $config['method'] = 'create';
        $dropdown = $this->nestedset->Dropdown();
        $template = 'backend.post.catalogue.store';
        return view('backend.dashboard.layout', compact('template', 'config', 'dropdown'));
    }

    public function store(StorePostCatalogueRequest $storePostCatalogueRequest)
    {
        if ($this->postCatalogueService->create($storePostCatalogueRequest, $this->language)) {
            flash()->success('Thêm mới bản ghi thành công');
            return redirect()->route('post.catalogue.index');
        }
        flash()->error('Thêm mới bản ghi không thành công');
        return redirect()->route('post.catalogue.index');
    }

    public function edit($id)
    {
        Gate::authorize('modules', 'post.catalogue.update');
        $postCatalogue = $this->postCatalogueRepository->getPostCatalogueById($id, $this->language);
        $config = $this->configData();
        $config['seo'] = __('postCatalogue');
        $config['method'] = 'edit';
        $dropdown = $this->nestedset->Dropdown();
        $album = json_decode($postCatalogue->album);
        $template = 'backend.post.catalogue.store';
        return view('backend.dashboard.layout', compact('template', 'config', 'postCatalogue', 'dropdown', 'album'));
    }

    public function update($id, UpdatePostCatalogueRequest $updatePostCatalogueRequest)
    {
        if ($this->postCatalogueService->update($id, $updatePostCatalogueRequest, $this->language)) {
            flash()->success('Cập nhật bản ghi thành công');
            return redirect()->route('post.catalogue.index');
        }
        flash()->error('Cập nhật bản ghi không thành công');
        return redirect()->route('post.catalogue.index');
    }

    public function delete($id)
    {
        Gate::authorize('modules', 'post.catalogue.destroy');
        $postCatalogue = $this->postCatalogueRepository->getPostCatalogueById($id, $this->language);
        $config['seo'] = __('postCatalogue');
        $template = 'backend.post.catalogue.delete';
        return view('backend.dashboard.layout', compact('template', 'postCatalogue', 'config'));
    }

    public function destroy($id, DeletePostCatalogueRequest $deletePostCatalogueRequest)
    {
        if ($this->postCatalogueService->delete($id)) {
            flash()->success('Xóa bản ghi thành công');
            return redirect()->route('post.catalogue.index');
        }
        flash()->error('Xóa bản ghi không thành công');
        return redirect()->route('post.catalogue.index');
    }

    private function initialize()
    {
        $this->nestedset = new Nestedsetbie([
            'table' => 'post_catalogues',
            'foreignkey' => 'post_catalogue_id',
            'language_id' => $this->language
        ]);
    }


    private function configData()
    {
        return [
            'js' => [
                'backend/plugins/ckeditor/ckeditor.js',
                'backend/plugins/ckfinder_2/ckfinder.js',
                'backend/library/finder.js',
                'backend/library/seo.js',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js'
            ],
            'css' => [
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css'
            ]
        ];
    }
}
