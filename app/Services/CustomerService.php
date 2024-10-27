<?php

namespace App\Services;

use App\Repositories\CustomerRepository;
use App\Repositories\SourceRepository;
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
    protected $sourceRepository;

    public function __construct(CustomerRepository $customerRepository, SourceRepository $sourceRepository)
    {
        $this->customerRepository = $customerRepository;
        $this->sourceRepository = $sourceRepository;
    }

    public function paginate($request)
    {
        $condition['keyword'] = addslashes($request->input('keyword'));
        $condition['customer_catalogue_id'] = $request->input('customer_catalogue_id') != null ? $request->input('customer_catalogue_id') : 0;
        $condition['source_id'] = $request->input('source_id') != null ? $request->input('source_id') : 0;
        $condition['publish'] = $request->input('publish') != null ? $request->integer('publish') : -1;
        $join = [
            ['provinces', 'provinces.code', '=', 'customers.province_id'], // Left join với bảng provinces
            ['districts', 'districts.code', '=', 'customers.district_id'], // Left join với bảng districts
            ['wards', 'wards.code', '=', 'customers.ward_id'] // Left join với bảng wards
        ];
        $perPage = $request->input('perpage') != null ? $request->integer('perpage') : 20;
        return $this->customerRepository->pagination($this->paginateSelect(), $condition, $join, $perPage, ['path' => 'customer/index']);
    }

    public function signup($request)
    {
        DB::beginTransaction();
        try {
            $payload = $request->except('_token', 'send', 're_password'); // lấy tất cả nhưng ngoại trừ... => trả về dạng mảng
            $payload['password'] = Hash::make($payload['password']);
            $source = $this->sourceRepository->findByCondition([['keyword', '=', 'website']]);
            $payload['source_id'] = $source->id;
            $payload['customer_catalogue_id'] = 1;
            $this->customerRepository->create($payload);
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            return false;
        }
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

    public function updateInfo($id, $request)
    {
        DB::beginTransaction();
        try {
            $payload = $request->input();
            $payload['birthday'] = $this->convertBirthdayDate($payload['birthday']);
            $this->customerRepository->update($id, $payload);
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    public function updateAddress($id, $request)
    {
        DB::beginTransaction();
        try {
            $payload = $request->input();
            $payload['province_id'] = $request->integer('province_id');
            $payload['district_id'] = $request->integer('district_id');
            $payload['ward_id'] = $request->integer('ward_id');
            $this->customerRepository->update($id, $payload);
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
        return [
            'customers.id',
            'customers.name',
            'customers.email',
            'customers.phone',
            'customers.address',
            'customers.publish',
            'customers.province_id',
            'customers.district_id',
            'customers.ward_id',
            'customers.customer_catalogue_id',
            'customers.source_id'
        ];
    }
}
