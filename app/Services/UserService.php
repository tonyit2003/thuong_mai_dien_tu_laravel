<?php

namespace App\Services;

use App\Repositories\UserRepository;
use App\Services\Interfaces\UserServiceInterface;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * Class UserService
 * @package App\Services
 */
class UserService extends BaseService implements UserServiceInterface
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function paginate($request)
    {
        $condition['keyword'] = addslashes($request->input('keyword'));
        $condition['user_catalogue_id'] = $request->input('user_catalogue_id') != null ? $request->input('user_catalogue_id') : 0;
        $join = [
            ['provinces', 'provinces.code', '=', 'users.province_id'], // Join với bảng provinces
            ['districts', 'districts.code', '=', 'users.district_id'], // Join với bảng districts
            ['wards', 'wards.code', '=', 'users.ward_id'] // Join với bảng wards
        ];
        // $request->input('publish') => trả về giá trị, không phải dạng mảng
        $condition['publish'] = $request->input('publish') != null ? $request->integer('publish') : -1;
        $perPage = $request->input('perpage') != null ? $request->integer('perpage') : 20;
        return $this->userRepository->pagination($this->paginateSelect(), $condition, $join, $perPage, ['path' => 'user/index']);
    }

    public function create($request)
    {
        DB::beginTransaction();
        try {
            $payload = $request->except('_token', 'send', 're_password'); // lấy tất cả nhưng ngoại trừ... => trả về dạng mảng

            $payload['birthday'] = $this->convertBirthdayDate($payload['birthday']);
            $payload['password'] = Hash::make($payload['password']);

            $this->userRepository->create($payload);

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
            $this->userRepository->update($id, $payload);
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
            $this->userRepository->delete($id);
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
            'users.id',
            'users.name',
            'users.email',
            'users.phone',
            'users.address',
            'users.publish',
            'users.user_catalogue_id',
            'users.province_id',
            'users.district_id',
            'users.ward_id',
        ];
    }
}
