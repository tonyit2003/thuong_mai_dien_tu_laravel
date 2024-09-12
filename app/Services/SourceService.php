<?php

namespace App\Services;

use App\Repositories\SourceRepository;
use App\Services\Interfaces\SourceServiceInterface;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Class SourceService
 * @package App\Services
 */
class SourceService extends BaseService implements SourceServiceInterface
{
    protected $sourceRepository;

    public function __construct(SourceRepository $sourceRepository)
    {
        $this->sourceRepository = $sourceRepository;
    }

    public function paginate($request)
    {
        $condition['keyword'] = addslashes($request->input('keyword'));
        $condition['publish'] = $request->input('publish') != null ? $request->integer('publish') : -1;
        $perPage = $request->input('perpage') != null ? $request->integer('perpage') : 20;
        return $this->sourceRepository->pagination($this->paginateSelect(), $condition, [], $perPage, ['path' => 'source/index']);
    }

    public function create($request)
    {
        DB::beginTransaction();
        try {
            $payload = $request->only('name', 'keyword', 'description');
            $this->sourceRepository->create($payload);
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
            $payload = $request->only('name', 'keyword', 'description');
            $this->sourceRepository->update($id, $payload);
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
            $this->sourceRepository->delete($id);
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    private function paginateSelect()
    {
        return ['id', 'name', 'keyword', 'publish', 'description'];
    }
}
