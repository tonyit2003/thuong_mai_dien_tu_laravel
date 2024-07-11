<?php

namespace App\Http\Controllers\Backend;

use App\Classes\Nestedsetbie;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Repositories\PostRepository;
use App\Services\PostService;
use Illuminate\Http\Request;

class PostController extends Controller
{
    protected $postService;
    protected $postRepository;
    protected $nestedset;
    protected $language;

    public function __construct(PostService $postService, PostRepository $postRepository)
    {
        $this->postService = $postService;
        $this->postRepository = $postRepository;
        $this->nestedset = new Nestedsetbie([
            'table' => 'post_catalogues',
            'foreignkey' => 'post_catalogue_id',
            'language_id' => 1
        ]);
        $this->language = $this->currentLanguage();
    }

    public function index(Request $request)
    {
        $posts = $this->postService->paginate($request);

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
        $config['seo'] = config('apps.post');

        $template = 'backend.post.post.index';
        return view('backend.dashboard.layout', compact('template', 'config', 'posts'));
    }

    public function create()
    {
        $config = $this->configData();
        $config['seo'] = config('apps.post');
        $config['method'] = 'create';
        $dropdown = $this->nestedset->Dropdown();
        $template = 'backend.post.post.store';
        return view('backend.dashboard.layout', compact('template', 'config', 'dropdown'));
    }

    public function store(StorePostRequest $storePostRequest)
    {
        if ($this->postService->create($storePostRequest)) {
            flash()->success('Thêm mới bản ghi thành công');
            return redirect()->route('post.index');
        }
        flash()->error('Thêm mới bản ghi không thành công');
        return redirect()->route('post.index');
    }

    public function edit($id)
    {
        $post = $this->postRepository->getPostById($id, $this->language);
        $config = $this->configData();
        $config['seo'] = config('apps.post');
        $config['method'] = 'edit';
        $album = json_decode($post->album);
        $dropdown = $this->nestedset->Dropdown();
        $template = 'backend.post.post.store';
        return view('backend.dashboard.layout', compact('template', 'config', 'post', 'album', 'dropdown'));
    }

    public function update($id, UpdatePostRequest $updatePostRequest)
    {
        if ($this->postService->update($id, $updatePostRequest)) {
            flash()->success('Cập nhật bản ghi thành công');
            return redirect()->route('post.index');
        }
        flash()->error('Cập nhật bản ghi không thành công');
        return redirect()->route('post.index');
    }

    public function delete($id)
    {
        $post = $this->postRepository->getPostById($id, $this->language);
        $config['seo'] = config('apps.post');
        $template = 'backend.post.post.delete';
        return view('backend.dashboard.layout', compact('template', 'post', 'config'));
    }

    public function destroy($id)
    {
        if ($this->postService->delete($id)) {
            flash()->success('Xóa bản ghi thành công');
            return redirect()->route('post.index');
        }
        flash()->error('Xóa bản ghi không thành công');
        return redirect()->route('post.index');
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
