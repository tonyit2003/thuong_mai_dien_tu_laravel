<?php

namespace App\Services;

use App\Repositories\CustomerCatalogueRepository;
use App\Repositories\CustomerRepository;
use App\Services\Interfaces\CustomerCatalogueServiceInterface;
use Exception;
use Illuminate\Support\Facades\DB;

/**
 * Class CustomerCatalogueService
 * @package App\Services
 */
class CustomerCatalogueService extends BaseService implements CustomerCatalogueServiceInterface
{
    protected $customerCatalogueRepository;
    protected $customerRepository;

    public function __construct(CustomerCatalogueRepository $customerCatalogueRepository, CustomerRepository $customerRepository)
    {
        $this->customerCatalogueRepository = $customerCatalogueRepository;
        $this->customerRepository = $customerRepository;
    }

    public function paginate($request)
    {
        $condition['keyword'] = addslashes($request->input('keyword'));
        $condition['publish'] = $request->input('publish') != null ? $request->integer('publish') : -1;
        $perPage = $request->input('perpage') != null ? $request->integer('perpage') : 20;
        return $this->customerCatalogueRepository->pagination($this->paginateSelect(), $condition, [], $perPage, ['path' => 'customer/catalogue/index'], ['customers']);
    }

    public function create($request)
    {
        DB::beginTransaction();
        try {
            $payload = $request->except('_token', 'send');

            $this->customerCatalogueRepository->create($payload);

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
            $payload = $request->except('_token', 'send');
            $this->customerCatalogueRepository->update($id, $payload);
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
            $this->customerCatalogueRepository->delete($id);
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    private function paginateSelect()
    {
        return ['id', 'name', 'description', 'publish'];
    }
}
