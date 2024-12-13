<?php

namespace App\Services;

use App\Repositories\SupplierRepository;
use App\Services\Interfaces\SupplierServiceInterface;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * Class UserService
 * @package App\Services
 */
class SupplierService extends BaseService implements SupplierServiceInterface
{
    protected $supplierRepository;

    public function __construct(SupplierRepository $supplierRepository)
    {
        $this->supplierRepository = $supplierRepository;
    }

    public function paginate($request)
    {
        $condition['keyword'] = addslashes($request->input('keyword'));
        $perPage = $request->input('perpage') != null ? $request->integer('perpage') : 80;

        $join = [
            ['product_catalogue_supplier', 'product_catalogue_supplier.supplier_id', '=', 'suppliers.id'],
            ['product_catalogues', 'product_catalogues.id', '=', 'product_catalogue_supplier.product_catalogue_id'],
            ['product_catalogue_language', 'product_catalogue_language.product_catalogue_id', '=', 'product_catalogues.id'],
            ['provinces', 'provinces.code', '=', 'suppliers.province_id'], // Join với bảng provinces
            ['districts', 'districts.code', '=', 'suppliers.district_id'], // Join với bảng districts
            ['wards', 'wards.code', '=', 'suppliers.ward_id'] // Join với bảng wards
        ];

        $orderBy = [
            'suppliers.id',
            'ASC'
        ];
        return $this->supplierRepository->pagination(
            $this->paginateSelect(),
            $condition,
            $join,
            $perPage,
            ['path' => 'supplier/index'],
            [],
            $orderBy
        );
    }

    public function create($request)
    {
        DB::beginTransaction();
        try {
            $payload = $request->except('_token', 'send'); // lấy tất cả nhưng ngoại trừ... => trả về dạng mảng
            $supplier = $this->supplierRepository->create($payload);

            if ($request->has('catalogue')) {
                $supplier->product_catalogues()->sync($request->input('catalogue')); // Sync the relationship
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
            $payload = $request->except('_token', 'send'); // lấy tất cả nhưng ngoại trừ... => trả về dạng mảng
            $supplier = $this->supplierRepository->update($id, $payload);

            if ($request->has('catalogue')) {
                $supplier->product_catalogues()->sync($request->input('catalogue')); // Sync the relationship
            }
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
            $this->supplierRepository->delete($id);
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

    public function paginateSelect()
    {
        return [
            'suppliers.id',
            'suppliers.name',
            'suppliers.email',
            'suppliers.phone',
            'suppliers.address',
            'suppliers.fax',
            'suppliers.province_id',
            'suppliers.district_id',
            'suppliers.ward_id',
            'suppliers.publish',
            'product_catalogue_language.name as product_type_name'
        ];
    }
}
