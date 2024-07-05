<?php

namespace App\Services;

use App\Repositories\PostCatalogueRepository;
use App\Services\Interfaces\PostCatalogueServiceInterface;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Class PostCatalogueService
 * @package App\Services
 */
class PostCatalogueService implements PostCatalogueServiceInterface
{
    protected $postCatalogueRepository;

    public function __construct(PostCatalogueRepository $postCatalogueRepository)
    {
        $this->postCatalogueRepository = $postCatalogueRepository;
    }

    public function paginate($request)
    {
        $condition['keyword'] = addslashes($request->input('keyword'));
        $condition['publish'] = $request->input('publish') != null ? $request->integer('publish') : -1;
        $perPage = $request->input('perpage') != null ? $request->integer('perpage') : 20;
        return $this->postCatalogueRepository->pagination($this->paginateSelect(), $condition, [], $perPage, ['path' => 'post/catalogue/index'], []);
    }

    public function create($request)
    {
        DB::beginTransaction();
        try {
            $payload = $request->except('_token', 'send'); // lấy tất cả nhưng ngoại trừ... => trả về dạng mảng
            $payload['user_id'] = Auth::id(); //lấy id người dùng hiện tại đang đăng nhập

            $this->postCatalogueRepository->create($payload);

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
            $payload = $request->except('_token', 'send'); // lấy tất cả nhưng ngoại trừ... => trả về dạng mảng
            $this->postCatalogueRepository->update($id, $payload);
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
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    // private function changeUserStatus($post)
    // {
    //     DB::beginTransaction();
    //     try {
    //         $array = [];
    //         if (isset($post['modelId'])) {
    //             $array[] = $post['modelId'];
    //             $payload[$post['field']] = (($post['value'] == 1) ? 0 : 1);
    //         } else {
    //             $array = $post['id'];
    //             $payload[$post['field']] = $post['value'];
    //         }
    //         $this->userRepository->updateByWhereIn('user_catalogue_id', $array, $payload);
    //         DB::commit();
    //         return true;
    //     } catch (Exception $e) {
    //         DB::rollBack();
    //         return false;
    //     }
    // }

    private function paginateSelect()
    {
        return ['id', 'name', 'canonical', 'publish', 'description', 'image'];
    }
}