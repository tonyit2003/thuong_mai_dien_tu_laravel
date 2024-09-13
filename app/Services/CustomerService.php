<?php

namespace App\Services;

use App\Repositories\CustomerRepository;
use App\Services\Interfaces\CustomerServiceInterface;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * Class CustomerService
 * @package App\Services
 */
class CustomerService extends BaseService implements CustomerServiceInterface
{
    protected $customerRepository;

    public function __construct(CustomerRepository $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    public function paginate($request)
    {
        $condition['keyword'] = addslashes($request->input('keyword'));
        $condition['customer_catalogue_id'] = $request->input('customer_catalogue_id') != null ? $request->input('customer_catalogue_id') : 0;
        $condition['source_id'] = $request->input('source_id') != null ? $request->input('source_id') : 0;
        $condition['publish'] = $request->input('publish') != null ? $request->integer('publish') : -1;
        $perPage = $request->input('perpage') != null ? $request->integer('perpage') : 20;
        return $this->customerRepository->pagination($this->paginateSelect(), $condition, [], $perPage, ['path' => 'customer/index']);
    }

    public function create($request)
    {
        DB::beginTransaction();
        try {
            $payload = $request->except('_token', 'send', 're_password'); // lấy tất cả nhưng ngoại trừ... => trả về dạng mảng

            $payload['birthday'] = $this->convertBirthdayDate($payload['birthday']);
            $payload['password'] = Hash::make($payload['password']);

            $this->customerRepository->create($payload);

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
            $payload['birthday'] = $this->convertBirthdayDate($payload['birthday']);
            $this->customerRepository->update($id, $payload);
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
            $this->customerRepository->delete($id);
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    private function convertBirthdayDate($birthday = '')
    {
        if ($birthday == null) return null;
        $carbonDate = Carbon::createFromFormat('Y-m-d', $birthday); // input type date trả về dạng Y-m-d
        return $carbonDate->format('Y-m-d H:i:s');
    }

    private function paginateSelect()
    {
        return ['id', 'name', 'email', 'phone', 'address', 'publish', 'customer_catalogue_id', 'source_id'];
    }
}
