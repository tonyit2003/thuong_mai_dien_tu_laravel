<?php

namespace App\Services;

use App\Repositories\PostRepository;
use App\Repositories\RouterRepository;
use App\Services\Interfaces\PostServiceInterface;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Class PostCatalogueService
 * @package App\Services
 */
class PostService extends BaseService implements PostServiceInterface
{
    protected $postRepository;
    protected $controllerName = 'PostController';

    public function __construct(PostRepository $postRepository, RouterRepository $routerRepository)
    {
        $this->postRepository = $postRepository;
        parent::__construct($routerRepository);
    }

    public function paginate($request, $languageId)
    {
        $perPage = $request->input('perpage') != null ? $request->integer('perpage') : 20;
        $condition = [
            'keyword' => addslashes($request->input('keyword')),
            'publish' => $request->input('publish') != null ? $request->integer('publish') : -1,
            'post_catalogue_id' => $request->input('post_catalogue_id') != null ? $request->integer('post_catalogue_id') : 0,
            'where' => [
                ['post_language.language_id', '=', $languageId]
            ]
        ];
        // kết với bảng post_language và điều kiện kết
        $join = [
            ['post_language', 'post_language.post_id', '=', 'posts.id'],
            ['post_catalogue_post', 'post_catalogue_post.post_id', '=', 'posts.id']
        ];
        $orderBy = [
            'posts.id',
            'DESC'
        ];
        $extend = [
            'path' => 'post/index',
            // có thể select các cột trong group by
            'groupBy' => $this->paginateSelect()
        ];
        $relations = ['post_catalogues'];
        return $this->postRepository->pagination($this->paginateSelect(), $condition, $join, $perPage, $extend, $relations, $orderBy, $this->whereRaw($request, $languageId));
    }

    public function create($request, $languageId)
    {
        DB::beginTransaction();
        try {
            $post = $this->createPost($request);
            if ($post->id > 0) { // lấy id của trường vừa mới thêm vào
                $this->updateLanguageForPost($post, $request, $languageId);
                $this->updateCatalogueForPost($post, $request);
                $this->createRouter($post, $request, $this->controllerName, $languageId);
            }

            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
            return false;
        }
    }

    public function update($id, $request, $languageId)
    {
        DB::beginTransaction();
        try {
            // lấy post từ csdl (đã có đầy đủ các mối quan hệ)
            $post = $this->postRepository->findById($id);
            if ($this->updatePost($post, $request)) {
                $this->updateLanguageForPost($post, $request, $languageId);
                $this->updateCatalogueForPost($post, $request);
                $this->updateRouter($post, $request, $this->controllerName, $languageId);
            }
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            dd($e->getMessage(), $e->getLine());
            return false;
        }
    }

    public function delete($id)
    {
        DB::beginTransaction();
        try {
            $this->postRepository->delete($id);
            $this->routerRepository->forceDeleteByCondition([
                ['module_id', '=', $id],
                ['controllers', '=', 'App\Http\Controllers\Frontend\\' . $this->controllerName . '']
            ]);

            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    private function createPost($request)
    {
        $payload = $request->only($this->payload()); // lấy những trường được liệt kê trong only => trả về dạng mảng
        $payload['user_id'] = Auth::id(); //lấy id người dùng hiện tại đang đăng nhập
        $payload['album'] = $this->formatAlbum($payload['album'] ?? null);
        return $this->postRepository->create($payload);
    }

    private function updatePost($post, $request)
    {
        $payload = $request->only($this->payload()); // lấy những trường được liệt kê trong only => trả về dạng mảng
        $payload['album'] = $this->formatAlbum($payload['album'] ?? null);
        return $this->postRepository->update($post->id, $payload);
    }

    private function updateLanguageForPost($post, $request, $languageId)
    {
        $payload = $request->only($this->payloadLanguage()); // lấy những trường được liệt kê trong only => trả về dạng mảng
        $payload = $this->formatLanguagePayload($payload, $post->id, $languageId);
        // gỡ mối quan hệ giữa hai bảng (xóa dữ liệu trên bảng post_language)
        // detach chỉ làm việc dựa trên đối tượng đã được tải đầy đủ từ csdl (có id và đầy đủ thông tin về mối quan hệ)
        $post->languages()->detach($payload['language_id']);

        // thêm lại dữ liệu trên bảng post_language
        return $this->postRepository->createPivot($post, $payload, 'languages');
    }

    private function formatLanguagePayload($payload, $postId, $languageId)
    {
        $payload['canonical'] = Str::slug($payload['canonical']); //chuyển đổi một chuỗi văn bản thành dạng mà có thể sử dụng được trong URL
        $payload['language_id'] = $languageId;
        $payload['post_id'] = $postId;
        return $payload;
    }

    private function updateCatalogueForPost($post, $request)
    {
        $catalogue = $this->catalogue($request); // mảng chứa các post_catalogue_id
        /*
                - đồng bộ dữ liệu trong bảng trung gian từ hàm định nghĩa mối quan hệ
                - post_id từ $post
                - post_catalogue_id từ mảng $catalogue
                - laravel sẽ: + xóa các post_catalogue_id không có trong mảng $catalogue
                              + thêm các giá trị post_catalogue_id từ mảng $catalogue mà chưa tồn tại trong csdl
                */
        $post->post_catalogues()->sync($catalogue);
    }

    private function catalogue($request)
    {
        // gộp 2 mảng và loại bỏ phần tử trùng lặp
        return array_unique(array_merge(($request->input('catalogue') != null && is_array($request->input('catalogue'))) ? $request->input('catalogue') : [], [$request->post_catalogue_id])); // [$request->post_catalogue_id] => tạo mảng chứa một phần tử duy nhất
    }

    private function whereRaw($request, $languageId)
    {
        $rawCondition = [];
        $postCatalogueId = $request->input('post_catalogue_id') != null ? $request->integer('post_catalogue_id') : 0;
        if ($postCatalogueId > 0) {
            $rawCondition['whereRaw'] = [
                [
                    'post_catalogue_post.post_catalogue_id IN (
                        SELECT id
                        FROM post_catalogues
                        JOIN post_catalogue_language ON post_catalogues.id = post_catalogue_language.post_catalogue_id
                        WHERE lft >= (SELECT lft FROM post_catalogues WHERE post_catalogues.id = ?)
                        AND rgt <= (SELECT rgt FROM post_catalogues WHERE post_catalogues.id = ?)
                        AND post_catalogue_language.language_id = ?
                    )',
                    [$postCatalogueId, $postCatalogueId, $languageId] // truyền giá trị vào ? trong câu truy vấn
                ]
            ];
        }
        return $rawCondition;
    }

    public function getData($postid)
    {
        return $this->postRepository->findById($postid, ['*'], ['languages']);
    }

    private function paginateSelect()
    {
        return [
            'posts.id',
            'posts.publish',
            'posts.image',
            'posts.order',
            'post_language.name',
            'post_language.canonical'
        ];
    }

    private function payload()
    {
        return ['follow', 'publish', 'image', 'album', 'post_catalogue_id', 'catalogue'];
    }

    private function payloadLanguage()
    {
        return ['name', 'description', 'content', 'meta_title', 'meta_keyword', 'meta_description', 'canonical'];
    }
}
