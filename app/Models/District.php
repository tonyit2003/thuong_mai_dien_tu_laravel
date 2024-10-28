<?php

namespace App\Models;

use App\Traits\QueryScopes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use HasFactory, QueryScopes;

    protected $fillable = [
        'code',
        'name',
        'full_name',
    ];

    // Tên bảng tương ứng trong mysql
    protected $table = 'districts';
    // Thông báo khóa chính là cột 'code'
    protected $primaryKey = 'code';
    // khóa chính không tự động tăng
    public $incrementing = false;

    public function provinces()
    {
        // quan hệ n --- 1: (class thiết lập mối quan hệ, khóa ngoại trong bảng districts, khóa chính trong bảng provinces)
        return $this->belongsTo(Province::class, 'province_code', 'code');
    }

    public function wards()
    {
        // quan hệ 1 --- n: (class thiết lập mối quan hệ, khóa ngoại trong bảng wards, khóa chính trong bảng districts)
        return $this->hasMany(Ward::class, 'district_code', 'code');
    }
}
