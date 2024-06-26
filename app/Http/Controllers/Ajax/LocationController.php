<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Repositories\DistrictRepository;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    protected $districtRepository;

    public function __construct(DistrictRepository $districtRepository)
    {
        $this->districtRepository = $districtRepository;
    }

    public function getLocation(Request $request)
    {
        $province_id = $request->input('province_id');
        $districts = $this->districtRepository->fileDistrictsByProvinceId($province_id);
        $response = [
            'html' => $this->renderHtml($districts)
        ];
        return response()->json($response);
    }

    public function renderHtml($districts)
    {
        $html = '<option value="0">[Chọn quận/huyện]</option>';
        foreach ($districts as $district) {
            $html .= '<option value="' . $district->code . '">' . $district->name . '</option>';
        }
        return $html;
    }
}
