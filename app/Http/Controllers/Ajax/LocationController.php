<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Repositories\DistrictRepository;
use App\Repositories\ProvinceRepository;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    protected $districtRepository;
    protected $provinceRepository;

    public function __construct(DistrictRepository $districtRepository, ProvinceRepository $provinceRepository)
    {
        $this->districtRepository = $districtRepository;
        $this->provinceRepository = $provinceRepository;
    }

    public function getLocation(Request $request)
    {
        $get = $request->input();
        $html = '';
        if ($get['target'] == 'district') {
            // Lấy province theo code và các district theo province
            $provinces = $this->provinceRepository->findById($get['data']['location_id'], ['code', 'name'], ['districts']);
            $districts = $provinces->districts;
            $html = $this->renderHtml($districts, __('form.select_district'));
        } else if ($get['target'] == 'ward') {
            // Lấy district theo code và các ward theo district
            $districts = $this->districtRepository->findById($get['data']['location_id'], ['code', 'name'], ['wards']);
            $wards = $districts->wards;
            $html = $this->renderHtml($wards, __('form.select_ward'));
        }

        // Cách khác để lấy danh sách district
        // $districts = $this->districtRepository->fileDistrictsByProvinceId($province_id);

        $response = [
            'html' => $html
        ];
        return response()->json($response);
    }

    public function renderHtml($districts, $root)
    {
        $html = '<option value="0">' . $root . '</option>';
        foreach ($districts as $district) {
            $html .= '<option value="' . $district->code . '">' . $district->name . '</option>';
        }
        return $html;
    }
}
