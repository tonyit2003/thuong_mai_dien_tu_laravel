<?php

namespace App\Services;

use App\Classes\Nestedsetbie;
use App\Models\PostCatalogue;
use App\Models\PostCatalogueLanguage;
use App\Repositories\PostCatalogueRepository;
use App\Repositories\RouterRepository;
use App\Services\Interfaces\PostCatalogueServiceInterface;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Class PostCatalogueService
 * @package App\Services
 */
class PostCatalogueService extends BaseService implements PostCatalogueServiceInterface
{
    protected $postCatalogueRepository;
    protected $nestedset;
    protected $controllerName = 'PostCatalogueController';

    public function __construct(PostCatalogueRepository $postCatalogueRepository, RouterRepository $routerRepository)
    {
        $this->postCatalogueRepository = $postCatalogueRepository;
        parent::__construct($routerRepository);
    }

    public function paginate($request, $languageId)
    {
        $perPage = $request->input('perpage') != null ? $request->integer('perpage') : 20;
        $condition = [
            'keyword' => addslashes($request->input('keyword')),
            'publish' => $request->input('publish') != null ? $request->integer('publish') : -1,
            'where' => [
                ['post_catalogue_language.language_id', '=', $languageId]
            ],
        ];
        // kết với bảng post_catalogue_language và điều kiện kết
        $join = [
            ['post_catalogue_language', 'post_catalogue_language.post_catalogue_id', '=', 'post_catalogues.id']
        ];
        $orderBy = [
            'post_catalogues.lft', 'ASC'
        ];
        $extend = ['path' => 'post/catalogue/index'];
        return $this->postCatalogueRepository->pagination($this->paginateSelect(), $condition, $join, $perPage, $extend, [], $orderBy);
    }

    public function create($request, $languageId)
    {
        DB::beginTransaction();
        try {
            $postCatalogue = $this->createPostCatalogue($request);
            if ($postCatalogue->id > 0) { // lấy id của trường vừa mới thêm vào
                $this->updateLanguageForPostCatalogue($postCatalogue, $request, $languageId);
                $this->createRouter($postCatalogue, $request, $this->controllerName, $languageId);
            }

            $this->nestedset = new Nestedsetbie([
                'table' => 'post_catalogues',
                'foreignkey' => 'post_catalogue_id',
                'language_id' =>  $languageId,
            ]);
            $this->nestedset($this->nestedset);

            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    public function update($id, $request, $languageId)
    {
        DB::beginTransaction();
        try {
            // lấy postCatalogue từ csdl (đã có đầy đủ các mối quan hệ)
            $postCatalogue = $this->postCatalogueRepository->findById($id);
            if ($this->updatePostCatalogue($postCatalogue, $request)) {
                $this->updateLanguageForPostCatalogue($postCatalogue, $request, $languageId);
                $this->updateRouter($postCatalogue, $request, $this->controllerName, $languageId);
            }

            $this->nestedset = new Nestedsetbie([
                'table' => 'post_catalogues',
                'foreignkey' => 'post_catalogue_id',
                'language_id' =>  $languageId,
            ]);
            $this->nestedset($this->nestedset);

            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    public function updateStatus($post = [])
    {
        DB::beginTransaction();
        try {
            $payload[$post['field']] = (($post['value'] == 1) ? 0 : 1);
            $this->postCatalogueRepository->update($post['modelId'], $payload);
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    public function updateStatusAll($post = [])
    {
        DB::beginTransaction();
        try {
            $payload[$post['field']] = $post['value'];
            $this->postCatalogueRepository->updateByWhereIn('id', $post['id'], $payload);
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    public function delete($id, $languageId)
    {
        DB::beginTransaction();
        try {
            $this->postCatalogueRepository->delete($id);
            $this->routerRepository->forceDeleteByCondition([
                ['module_id', '=', $id],
                ['controllers', '=', 'App\Http\Controllers\Frontend\\' . $this->controllerName . '']
            ]);

            $this->nestedset = new Nestedsetbie([
                'table' => 'post_catalogues',
                'foreignkey' => 'post_catalogue_id',
                'language_id' =>  $languageId,
            ]);
            $this->nestedset($this->nestedset);

            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    private function createPostCatalogue($request)
    {
        $payload = $request->only($this->payload()); // lấy những trường được liệt kê trong only => trả về dạng mảng
        $payload['user_id'] = Auth::id(); //lấy id người dùng hiện tại đang đăng nhập
        $payload['album'] = $this->formatAlbum($payload['album'] ?? null);
        return $this->postCatalogueRepository->create($payload);
    }

    private function updatePostCatalogue($postCatalogue, $request)
    {
        $payload = $request->only($this->payload()); // lấy những trường được liệt kê trong only => trả về dạng mảng
        $payload['album'] = $this->formatAlbum($payload['album'] ?? null);
        return $this->postCatalogueRepository->update($postCatalogue->id, $payload);
    }

    private function updateLanguageForPostCatalogue($postCatalogue, $request, $languageId)
    {
        $payload = $request->only($this->payloadLanguage()); // lấy những trường được liệt kê trong only => trả về dạng mảng
        $payload = $this->formatLanguagePayload($payload, $postCatalogue, $languageId);

        // gỡ mối quan hệ giữa hai bảng (xóa dữ liệu trên bảng post_catalogue_language)
        // detach chỉ làm việc dựa trên đối tượng đã được tải đầy đủ từ csdl (có id và đầy đủ thông tin về mối quan hệ)
        $postCatalogue->languages()->detach($payload['language_id']);

        // thêm lại dữ liệu trên bảng post_catalogue_language
        return $this->postCatalogueRepository->createPivot($postCatalogue, $payload, 'languages');
    }

    private function formatLanguagePayload($payload, $postCatalogue, $languageId)
    {
        $payload['canonical'] = Str::slug($payload['canonical']); //chuyển đổi một chuỗi văn bản thành dạng mà có thể sử dụng được trong URL
        $payload['language_id'] = $languageId;
        $payload['post_catalogue_id'] = $postCatalogue->id;
        return $payload;
    }

    private function paginateSelect()
    {
        return [
            'post_catalogues.id',
            'post_catalogues.publish',
            'post_catalogues.image',
            'post_catalogues.level',
            'post_catalogues.order',
            'post_catalogue_language.name',
            'post_catalogue_language.canonical'
        ];
    }

    private function payload()
    {
        return ['parent_id', 'follow', 'publish', 'image', 'album'];
    }

    private function payloadLanguage()
    {
        return ['name', 'description', 'content', 'meta_title', 'meta_keyword', 'meta_description', 'canonical'];
    }
}
