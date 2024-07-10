<?php

namespace App\Services;

use App\Repositories\PostRepository;
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
    protected $language;

    public function __construct(PostRepository $postRepository)
    {
        $this->language = $this->currentLanguage();
        $this->postRepository = $postRepository;
    }

    public function paginate($request)
    {
        $condition['keyword'] = addslashes($request->input('keyword'));
        $condition['where'] = [
            ['post_language.language_id', '=', $this->language]
        ];
        $condition['publish'] = $request->input('publish') != null ? $request->integer('publish') : -1;
        $perPage = $request->input('perpage') != null ? $request->integer('perpage') : 20;
        // kết với bảng post_language và điều kiện kết
        $join = [
            ['post_language', 'post_language.post_id', '=', 'posts.id']
        ];
        $orderBy = [
            'posts.id', 'DESC'
        ];
        return $this->postRepository->pagination($this->paginateSelect(), $condition, $join, $perPage, ['path' => 'post/index'], [], $orderBy);
    }

    public function create($request)
    {
        DB::beginTransaction();
        try {
            $payload = $request->only($this->payload()); // lấy những trường được liệt kê trong only => trả về dạng mảng
            $payload['user_id'] = Auth::id(); //lấy id người dùng hiện tại đang đăng nhập
            $payload['album'] = (isset($payload['album']) && is_array($payload['album'])) ? json_encode($payload['album']) : ""; // // $payload['album']: mảng các đường dẫn từ input name="album[]"
            $post = $this->postRepository->create($payload);
            if ($post->id > 0) { // lấy id của trường vừa mới thêm vào
                $payloadLanguage = $request->only($this->payloadLanguage()); // lấy những trường được liệt kê trong only => trả về dạng mảng
                $payloadLanguage['canonical'] = Str::slug($payloadLanguage['canonical']); //chuyển đổi một chuỗi văn bản thành dạng mà có thể sử dụng được trong URL
                $payloadLanguage['language_id'] = $this->language;
                $payloadLanguage['post_id'] = $post->id;
                $this->postRepository->createPivot($post, $payloadLanguage, 'languages');

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
            $post = $this->postRepository->findById($id);
            $payload = $request->only($this->payload()); // lấy những trường được liệt kê trong only => trả về dạng mảng
            $payload['album'] = (isset($payload['album']) && is_array($payload['album'])) ? json_encode($payload['album']) : ""; // // $payload['album']: mảng các đường dẫn từ input name="album[]"
            $flag = $this->postRepository->update($id, $payload);
            if ($flag == TRUE) {
                $payloadLanguage = $request->only($this->payloadLanguage()); // lấy những trường được liệt kê trong only => trả về dạng mảng
                $payloadLanguage['language_id'] = $this->language;
                $payloadLanguage['post_id'] = $id;

                // gỡ mối quan hệ giữa hai bảng (xóa dữ liệu trên bảng post_language)
                // detach chỉ làm việc dựa trên đối tượng đã được tải đầy đủ từ csdl (có id và đầy đủ thông tin về mối quan hệ)
                $post->languages()->detach($payloadLanguage['language_id']);

                // thêm lại dữ liệu trên bảng post_language
                $this->postRepository->createPivot($post, $payloadLanguage, 'languages');
            }

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
            $this->postRepository->update($post['modelId'], $payload);
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
            $this->postRepository->updateByWhereIn('id', $post['id'], $payload);
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
            $this->postRepository->delete($id);

            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    private function catalogue($request)
    {
        // gộp 2 mảng và loại bỏ phần tử trùng lặp
        return array_unique(array_merge($request->input('catalogue'), [$request->post_catalogue_id])); // [$request->post_catalogue_id] => tạo mảng chứa một phần tử duy nhất
    }

    private function paginateSelect()
    {
        return [
            'posts.id',
            'posts.publish',
            'posts.image',
            'posts.level',
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
