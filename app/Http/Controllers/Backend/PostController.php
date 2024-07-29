<?php

namespace App\Http\Controllers\Backend;

use App\Classes\Nestedsetbie;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Language;
use App\Repositories\PostRepository;
use App\Services\PostService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Gate;

class PostController extends Controller
{
    protected $postService;
    protected $postRepository;
    protected $nestedset;
    protected $language;

    public function __construct(PostService $postService, PostRepository $postRepository)
    {
        $this->middleware(function ($request, $next) {
            $locale = App::getLocale();
            $language = Language::where('canonical', $locale)->first();
            $this->language = $language->id;
            $this->initialize();
            return $next($request);
        });
        $this->postService = $postService;
        $this->postRepository = $postRepository;
        $this->initialize();
    }

    public function index(Request $request)
    {
        Gate::authorize('modules', 'post.index');
        $posts = $this->postService->paginate($request, $this->language);

        $config = [
            'js' => [
                'backend/js/plugins/switchery/switchery.js',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js'
            ],
            'css' => [
                'backend/css/plugins/switchery/switchery.css',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css'
            ],
            'model' => 'Post'
        ];
        $config['seo'] = __('post');
        $dropdown = $this->nestedset->Dropdown();
        $template = 'backend.post.post.index';
        return view('backend.dashboard.layout', compact('template', 'config', 'posts', 'dropdown'));
    }

    public function create()
    {
        Gate::authorize('modules', 'post.create');
        $config = $this->configData();
        $config['seo'] = __('post');
        $config['method'] = 'create';
        $dropdown = $this->nestedset->Dropdown();
        $template = 'backend.post.post.store';
        return view('backend.dashboard.layout', compact('template', 'config', 'dropdown'));
    }

    public function store(StorePostRequest $storePostRequest)
    {
        if ($this->postService->create($storePostRequest, $this->language)) {
            flash()->success(__('toast.store_success'));
            return redirect()->route('post.index');
        }
        flash()->error(__('toast.store_failed'));
        return redirect()->route('post.index');
    }

    public function edit($id)
    {
        Gate::authorize('modules', 'post.update');
        $post = $this->postRepository->getPostById($id, $this->language);
        $config = $this->configData();
        $config['seo'] = __('post');
        $config['method'] = 'edit';
        $album = json_decode($post->album);
        $dropdown = $this->nestedset->Dropdown();
        $template = 'backend.post.post.store';
        return view('backend.dashboard.layout', compact('template', 'config', 'post', 'album', 'dropdown'));
    }

    public function update($id, UpdatePostRequest $updatePostRequest)
    {
        if ($this->postService->update($id, $updatePostRequest, $this->language)) {
            flash()->success(__('toast.update_success'));
            return redirect()->route('post.index');
        }
        flash()->error(__('toast.update_failed'));
        return redirect()->route('post.index');
    }

    public function delete($id)
    {
        Gate::authorize('modules', 'post.destroy');
        $post = $this->postRepository->getPostById($id, $this->language);
        $config['seo'] = __('post');
        $template = 'backend.post.post.delete';
        return view('backend.dashboard.layout', compact('template', 'post', 'config'));
    }

    public function destroy($id)
    {
        if ($this->postService->delete($id)) {
            flash()->success(__('toast.destroy_success'));
            return redirect()->route('post.index');
        }
        flash()->error(__('toast.destroy_failed'));
        return redirect()->route('post.index');
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
