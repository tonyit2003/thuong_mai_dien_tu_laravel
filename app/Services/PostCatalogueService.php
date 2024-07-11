<?php

namespace App\Services;

use App\Classes\Nestedsetbie;
use App\Models\PostCatalogue;
use App\Repositories\PostCatalogueRepository;
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
    protected $language;

    public function __construct(PostCatalogueRepository $postCatalogueRepository)
    {
        $this->language = $this->currentLanguage();
        $this->postCatalogueRepository = $postCatalogueRepository;
        $this->nestedset = new Nestedsetbie([
            'table' => 'post_catalogues',
            'foreignkey' => 'post_catalogue_id',
            'language_id' => $this->language
        ]);
    }

    public function paginate($request)
    {
        $condition['keyword'] = addslashes($request->input('keyword'));
        $condition['where'] = [
            ['post_catalogue_language.language_id', '=', $this->language]
        ];
        $condition['publish'] = $request->input('publish') != null ? $request->integer('publish') : -1;
        $perPage = $request->input('perpage') != null ? $request->integer('perpage') : 20;
        // kết với bảng post_catalogue_language và điều kiện kết
        $join = [
            ['post_catalogue_language', 'post_catalogue_language.post_catalogue_id', '=', 'post_catalogues.id']
        ];
        $orderBy = [
            'post_catalogues.lft', 'ASC'
        ];
        return $this->postCatalogueRepository->pagination($this->paginateSelect(), $condition, $join, $perPage, ['path' => 'post/catalogue/index'], [], $orderBy);
    }

    public function create($request)
    {
        DB::beginTransaction();
        try {
            $payload = $request->only($this->payload()); // lấy những trường được liệt kê trong only => trả về dạng mảng
            $payload['user_id'] = Auth::id(); //lấy id người dùng hiện tại đang đăng nhập
            $payload['album'] = (isset($payload['album']) && is_array($payload['album'])) ? json_encode($payload['album']) : ""; // // $payload['album']: mảng các đường dẫn từ input name="album[]"
            $postCatalogue = $this->postCatalogueRepository->create($payload);
            if ($postCatalogue->id > 0) { // lấy id của trường vừa mới thêm vào
                $payloadLanguage = $request->only($this->payloadLanguage()); // lấy những trường được liệt kê trong only => trả về dạng mảng
                $payloadLanguage['canonical'] = Str::slug($payloadLanguage['canonical']); //chuyển đổi một chuỗi văn bản thành dạng mà có thể sử dụng được trong URL
                $payloadLanguage['language_id'] = $this->language;
                $payloadLanguage['post_catalogue_id'] = $postCatalogue->id;
                $this->postCatalogueRepository->createPivot($postCatalogue, $payloadLanguage, 'languages');
            }

            // tính giá trị left, right bằng Nestedsetbie (có sẵn)
            $this->nestedset->Get('level ASC, order ASC');
            $this->nestedset->Recursive(0, $this->nestedset->Set());
            $this->nestedset->Action();

            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    public function update($id, $request)
    {
        DB::beginTransaction();
        try {
            // lấy postCatalogue từ csdl (đã có đầy đủ các mối quan hệ)
            $postCatalogue = $this->postCatalogueRepository->findById($id);
            $payload = $request->only($this->payload()); // lấy những trường được liệt kê trong only => trả về dạng mảng
            $payload['album'] = (isset($payload['album']) && is_array($payload['album'])) ? json_encode($payload['album']) : ""; // // $payload['album']: mảng các đường dẫn từ input name="album[]"
            $flag = $this->postCatalogueRepository->update($id, $payload);
            if ($flag == TRUE) {
                $payloadLanguage = $request->only($this->payloadLanguage()); // lấy những trường được liệt kê trong only => trả về dạng mảng
                $payloadLanguage['canonical'] = Str::slug($payloadLanguage['canonical']); //chuyển đổi một chuỗi văn bản thành dạng mà có thể sử dụng được trong URL
                $payloadLanguage['language_id'] = $this->language;
                $payloadLanguage['post_catalogue_id'] = $id;

                // gỡ mối quan hệ giữa hai bảng (xóa dữ liệu trên bảng post_catalogue_language)
                // detach chỉ làm việc dựa trên đối tượng đã được tải đầy đủ từ csdl (có id và đầy đủ thông tin về mối quan hệ)
                $postCatalogue->languages()->detach($payloadLanguage['language_id']);

                // thêm lại dữ liệu trên bảng post_catalogue_language
                $this->postCatalogueRepository->createPivot($postCatalogue, $payloadLanguage, 'languages');
            }

            // tính giá trị left, right bằng Nestedsetbie (có sẵn)
            $this->nestedset->Get('level ASC, order ASC');
            $this->nestedset->Recursive(0, $this->nestedset->Set());
            $this->nestedset->Action();

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
            // $this->changeUserStatus($post);
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
            // $this->changeUserStatus($post);
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    public function delete($id)
    {
        DB::beginTransaction();
        try {
            $this->postCatalogueRepository->delete($id);

            // tính giá trị left, right bằng Nestedsetbie (có sẵn)
            $this->nestedset->Get('level ASC, order ASC');
            $this->nestedset->Recursive(0, $this->nestedset->Set());
            $this->nestedset->Action();

            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            return false;
        }
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
