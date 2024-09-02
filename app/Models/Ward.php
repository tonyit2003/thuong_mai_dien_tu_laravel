<?php

namespace App\Models;

use App\Traits\QueryScopes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ward extends Model
{
    use HasFactory, QueryScopes;

    protected $fillable = [
        'name'
    ];

    // Tên bảng tương ứng trong mysql
    protected $table = 'wards';
    // Thông báo khóa chính là cột 'code'
    protected $primaryKey = 'code';
    // khóa chính không tự động tăng
    public $incrementing = false;

    public function districts()
    {
        // quan hệ n --- 1: (class thiết lập mối quan hệ, khóa ngoại trong bảng wards, khóa chính trong bảng districts)
        return $this->belongsTo(District::class, 'district_code', 'code');
    }
}
